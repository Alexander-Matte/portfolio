# Multi-stage build for Nuxt static site
FROM node:20-alpine AS base

# Install dependencies only when needed
FROM base AS deps
RUN apk add --no-cache libc6-compat
WORKDIR /app

# Copy package files
COPY package.json package-lock.json* ./
RUN npm ci

# Rebuild the source code only when needed
FROM base AS builder
WORKDIR /app
COPY --from=deps /app/node_modules ./node_modules
COPY . .

# Build arguments for environment variables
ARG NUXT_PUBLIC_API_BASE_URL
ARG NUXT_PUBLIC_MERCURE_URL

ENV NUXT_PUBLIC_API_BASE_URL=$NUXT_PUBLIC_API_BASE_URL
ENV NUXT_PUBLIC_MERCURE_URL=$NUXT_PUBLIC_MERCURE_URL

# Build the application
RUN npm run build

# Production image, copy all the files and serve with nginx
FROM nginx:alpine AS runner
WORKDIR /app

# Copy built assets
COPY --from=builder /app/.output/public /usr/share/nginx/html

# Copy nginx configuration
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]

