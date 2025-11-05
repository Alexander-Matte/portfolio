# Quick Start - Production Deployment

## Quick Answer: CORS Configuration

**Use `api.yourdomain.com`** - This is the standard approach and provides:
- Better security (different origins)
- Cleaner separation of concerns
- Easier CORS configuration
- Industry standard practice

## Tag-Based Deployment

The deployment system uses tags with the format: `release-YYYYMMDD-HHMM`

Example: `release-20251105-1858`

### Creating a Deployment

```bash
# 1. Make sure you're on main branch
git checkout main
git pull origin main

# 2. Create and push a release tag
git tag release-20251105-1858
git push origin release-20251105-1858
```

**Important**: Only tags matching this format on the `main` branch will trigger deployments.

## Required GitHub Secrets

Go to: Settings → Secrets and variables → Actions

- `SERVER_HOST` - Your server IP or hostname
- `SERVER_USER` - SSH username (usually `root`)
- `SSH_PRIVATE_KEY` - Your SSH private key
- `DOMAIN` - Your domain (e.g., `yourdomain.com`)
- `DEPLOY_PATH` - Path on server (e.g., `/opt/portfolio`)

## Server Setup (First Time)

1. **Run setup script on server:**
   ```bash
   curl -o setup-server.sh https://raw.githubusercontent.com/yourusername/portfolio/main/setup-server.sh
   chmod +x setup-server.sh
   sudo ./setup-server.sh
   ```

2. **Clone repository:**
   ```bash
   cd /opt/portfolio
   git clone <your-repo-url> .
   ```

3. **Configure environment:**
   ```bash
   cp .env.prod.example .env.prod
   nano .env.prod
   ```

4. **Generate secrets:**
   ```bash
   openssl rand -hex 32  # For APP_SECRET
   openssl rand -hex 32  # For CADDY_MERCURE_JWT_SECRET
   ```

5. **Manual first deployment:**
   ```bash
   git checkout main
   chmod +x deploy.sh
   ./deploy.sh
   ```

## Environment Variables Required

See `.env.prod.example` for all required variables. Key ones:

- `DOMAIN` - Your main domain
- `API_DOMAIN` - API subdomain (e.g., `api.yourdomain.com`)
- `POSTGRES_PASSWORD` - Strong database password
- `APP_SECRET` - Symfony secret (generate with `openssl rand -hex 32`)
- `CADDY_MERCURE_JWT_SECRET` - Mercure secret (generate with `openssl rand -hex 32`)
- `CADDY_EMAIL` - Email for Let's Encrypt certificates

## DNS Configuration

Point these DNS records to your server IP:
- A record: `yourdomain.com` → server IP
- A record: `api.yourdomain.com` → server IP

## Deployment Flow

1. Push code to `main` branch
2. Create tag: `release-YYYYMMDD-HHMM`
3. Push tag to trigger GitHub Actions
4. GitHub Actions validates tag format
5. GitHub Actions deploys to server
6. Server runs migrations and health checks

## Troubleshooting

**Check logs:**
```bash
docker compose -f docker-compose.prod.yml logs -f
```

**Check status:**
```bash
docker compose -f docker-compose.prod.yml ps
```

**Restart services:**
```bash
docker compose -f docker-compose.prod.yml restart
```

For more details, see `DEPLOYMENT.md`.

