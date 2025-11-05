# Production Deployment Guide

This guide explains how to deploy the application to production on a Digital Ocean droplet using Docker Compose and Caddy as a reverse proxy.

## Architecture

The production setup consists of:
- **Client**: Nuxt.js static site served by nginx
- **API**: Symfony/FrankenPHP backend
- **Database**: PostgreSQL
- **Reverse Proxy**: Caddy (handles HTTPS, SSL certificates, routing)

All services run in separate Docker containers on the same network.

## Prerequisites

1. A Digital Ocean droplet (Ubuntu 22.04+ recommended)
2. Domain name pointing to your droplet's IP address
3. Docker and Docker Compose installed on the server
4. SSH access to the server
5. GitHub repository with secrets configured

## Domain Setup

For optimal CORS configuration, use a subdomain for the API:
- Main domain: `yourdomain.com` (client)
- API subdomain: `api.yourdomain.com` (backend)

**Why `api.yourdomain.com` instead of `yourdomain.com/api`?**
- Better CORS configuration (different origins)
- Cleaner separation of concerns
- Easier to scale independently
- Standard practice for API services

## Initial Server Setup

### 1. Verify Docker and Docker Compose

```bash
# Verify Docker is installed
docker --version

# Verify Docker Compose is installed
docker compose version

# If not installed, install Docker and Docker Compose first
# Then add your user to docker group (optional, to run without sudo)
sudo usermod -aG docker $USER
```

### 2. Clone Repository

```bash
cd /opt  # or your preferred directory
git clone https://github.com/yourusername/portfolio.git
cd portfolio
```

### 3. Configure Environment Variables

```bash
# Copy the example file
cp .env.prod.example .env.prod

# Edit with your values
nano .env.prod
```

**Important**: Generate secure secrets:
```bash
# Generate APP_SECRET
openssl rand -hex 32

# Generate CADDY_MERCURE_JWT_SECRET
openssl rand -hex 32
```

### 4. Configure DNS

Point your domain to the server:
- A record: `yourdomain.com` → server IP
- A record: `api.yourdomain.com` → server IP

Wait for DNS propagation (can take up to 48 hours, usually faster).

## GitHub Actions Setup

### 1. Configure GitHub Secrets

Go to your repository → Settings → Secrets and variables → Actions, and add:

- `SERVER_HOST`: Your server IP or hostname
- `SERVER_USER`: SSH username (usually `root` or your user)
- `SSH_PRIVATE_KEY`: Your SSH private key for server access
- `DOMAIN`: Your domain name (e.g., `yourdomain.com`)
- `DEPLOY_PATH`: Path to your repository on server (e.g., `/opt/portfolio`)

### 2. Generate SSH Key (if needed)

On your local machine:
```bash
ssh-keygen -t ed25519 -C "github-actions" -f ~/.ssh/github_actions
```

Add the public key to your server:
```bash
cat ~/.ssh/github_actions.pub | ssh user@your-server "cat >> ~/.ssh/authorized_keys"
```

Add the private key content to GitHub Secrets as `SSH_PRIVATE_KEY`.

## Deployment Process

### Manual Deployment (First Time)

On your server:
```bash
cd /opt/portfolio  # or your deploy path
git checkout main
git pull origin main
chmod +x deploy.sh
./deploy.sh
```

### Automated Deployment via GitHub Actions

Deployments are triggered automatically when you push a tag matching the pattern `release-YYYYMMDD-HHMM` to the `main` branch.

#### Creating a Release Tag

```bash
# Make sure you're on main branch
git checkout main
git pull origin main

# Create and push a release tag
git tag release-20251105-1858
git push origin release-20251105-1858
```

The GitHub Actions workflow will:
1. Validate the tag format
2. Verify the tag is on the main branch
3. Deploy to your server
4. Run database migrations
5. Perform health checks

## Monitoring and Maintenance

### Check Container Status

```bash
docker compose -f docker-compose.prod.yml ps
```

### View Logs

```bash
# All services
docker compose -f docker-compose.prod.yml logs -f

# Specific service
docker compose -f docker-compose.prod.yml logs -f api
docker compose -f docker-compose.prod.yml logs -f client
docker compose -f docker-compose.prod.yml logs -f caddy
```

### Restart Services

```bash
docker compose -f docker-compose.prod.yml restart [service-name]
```

### Update and Redeploy

1. Make your changes and commit to main
2. Create a new release tag: `release-YYYYMMDD-HHMM`
3. Push the tag to trigger deployment

### Database Backups

```bash
# Create backup
docker compose -f docker-compose.prod.yml exec database pg_dump -U app app > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore from backup
docker compose -f docker-compose.prod.yml exec -T database psql -U app app < backup_20251105_120000.sql
```

## Security Considerations

1. **Firewall**: Only expose ports 80 and 443
   ```bash
   sudo ufw allow 80/tcp
   sudo ufw allow 443/tcp
   sudo ufw enable
   ```

2. **Environment Variables**: Never commit `.env.prod` to git

3. **SSH Keys**: Use SSH keys instead of passwords

4. **Database**: Database is not exposed externally, only accessible within Docker network

5. **CORS**: Configured to only allow requests from your main domain

6. **HTTPS**: Automatically handled by Caddy with Let's Encrypt

## Troubleshooting

### Services Won't Start

```bash
# Check logs
docker compose -f docker-compose.prod.yml logs

# Check container status
docker compose -f docker-compose.prod.yml ps -a
```

### SSL Certificate Issues

Caddy automatically obtains SSL certificates. If there are issues:
1. Check DNS records are correct
2. Ensure ports 80 and 443 are open
3. Check Caddy logs: `docker compose -f docker-compose.prod.yml logs caddy`

### Database Connection Issues

```bash
# Check database is running
docker compose -f docker-compose.prod.yml ps database

# Test connection
docker compose -f docker-compose.prod.yml exec api php bin/console dbal:run-sql "SELECT 1"
```

### CORS Issues

Verify CORS configuration in `.env.prod`:
- `CORS_ALLOW_ORIGIN` should match your client domain
- Check API logs for CORS errors

## File Structure

```
portfolio/
├── docker-compose.prod.yml    # Production Docker Compose config
├── deploy.sh                   # Deployment script
├── .env.prod.example          # Environment variables template
├── caddy/
│   └── Caddyfile              # Caddy reverse proxy config
├── client/
│   ├── Dockerfile             # Client container
│   └── nginx.conf             # Nginx config for client
└── api/
    └── api/
        └── Dockerfile         # API container (FrankenPHP)
```

## Support

For issues or questions, please check:
- GitHub Issues
- Docker logs
- Caddy logs

