#!/bin/bash
set -e

# Initial server setup script
# Run this on your Digital Ocean droplet before first deployment

echo "🚀 Setting up production server..."

# Check if running as root
if [ "$EUID" -ne 0 ]; then 
    echo "Please run as root (use sudo)"
    exit 1
fi

# Update system
echo "📦 Updating system packages..."
apt update && apt upgrade -y

# Install required packages
echo "📦 Installing required packages..."
apt install -y curl git ufw

# Verify Docker and Docker Compose are installed
echo "🐳 Verifying Docker installation..."
if ! command -v docker &> /dev/null; then
    echo "❌ Error: Docker is not installed. Please install Docker first."
    exit 1
fi

if ! docker compose version &> /dev/null; then
    echo "❌ Error: Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

echo "✓ Docker and Docker Compose are installed"

# Configure firewall
echo "🔥 Configuring firewall..."
ufw --force enable
ufw allow 22/tcp   # SSH
ufw allow 80/tcp   # HTTP
ufw allow 443/tcp  # HTTPS
ufw allow 443/udp  # HTTP/3

# Create deployment directory
DEPLOY_DIR="/opt/portfolio"
if [ ! -d "$DEPLOY_DIR" ]; then
    echo "📁 Creating deployment directory..."
    mkdir -p $DEPLOY_DIR
else
    echo "✓ Deployment directory already exists"
fi

# Create deploy user (optional, if you want a dedicated user)
echo "👤 Creating deploy user (optional)..."
read -p "Create a dedicated deploy user? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    read -p "Enter username: " DEPLOY_USER
    if ! id "$DEPLOY_USER" &>/dev/null; then
        useradd -m -s /bin/bash $DEPLOY_USER
        usermod -aG docker $DEPLOY_USER
        echo "✓ User $DEPLOY_USER created and added to docker group"
    else
        echo "✓ User $DEPLOY_USER already exists"
    fi
fi

echo ""
echo "✅ Server setup complete!"
echo ""
echo "Next steps:"
echo "1. Clone your repository:"
echo "   cd $DEPLOY_DIR"
echo "   git clone <your-repo-url> ."
echo ""
echo "2. Copy and configure environment:"
echo "   cp .env.prod.example .env.prod"
echo "   nano .env.prod"
echo ""
echo "3. Generate secrets:"
echo "   openssl rand -hex 32  # For APP_SECRET"
echo "   openssl rand -hex 32  # For CADDY_MERCURE_JWT_SECRET"
echo ""
echo "4. Run deployment:"
echo "   chmod +x deploy.sh"
echo "   ./deploy.sh"
echo ""

