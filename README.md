# 💼 Alexander Matte - Portfolio

> Full-stack portfolio showcasing my work as a Junior Developer specializing in PHP/Symfony backend development and modern frontend with Nuxt.

[![Built with Nuxt](https://img.shields.io/badge/Built%20with-Nuxt%204-00DC82?style=flat&logo=nuxt.js)](https://nuxt.com)
[![Powered by Symfony](https://img.shields.io/badge/Powered%20by-Symfony-000000?style=flat&logo=symfony)](https://symfony.com)
[![API Platform](https://img.shields.io/badge/API-Platform-38A9E4?style=flat&logo=api)](https://api-platform.com)

## 🚀 Project Overview

This portfolio demonstrates my full-stack development capabilities through a real-world application featuring:

- **Frontend**: Modern, responsive UI built with Nuxt 4, Vue 3, and Nuxt UI
- **Backend**: RESTful API powered by Symfony and API Platform
- **Interactive API Playground**: Live demonstration of backend API skills

## 📁 Project Structure

```
portfolio/
├── client/          # Nuxt 4 frontend application
│   ├── app/
│   │   ├── pages/   # Portfolio pages
│   │   └── assets/  # Styles and assets
│   ├── nuxt.config.ts
│   └── package.json
│
├── api/             # Symfony API Platform backend
│   └── api/
│       ├── src/
│       │   ├── Entity/      # API entities
│       │   ├── Controller/  # Custom controllers
│       │   └── Repository/  # Data repositories
│       ├── config/
│       └── composer.json
│
└── TODO.md          # Development roadmap
```

## 🛠️ Tech Stack

### Frontend
- **Framework**: Nuxt 4
- **UI Library**: Nuxt UI
- **Styling**: Tailwind CSS
- **Icons**: Nuxt Icon (Heroicons, Skill Icons)
- **Language**: TypeScript

### Backend
- **Framework**: Symfony 7
- **API**: API Platform
- **Database**: PostgreSQL
- **ORM**: Doctrine
- **Real-time**: Mercure (planned)

### DevOps
- **Containerization**: Docker & Docker Compose
- **Version Control**: Git
- **Package Managers**: npm, Composer

## 🎯 Features

### Current Features
- ✅ Responsive portfolio website
- ✅ Professional experience showcase
- ✅ Technical skills display
- ✅ Contact information with social links
- ✅ Dark mode support
- ✅ Modern, animated UI

### Planned Features
- 🔄 Interactive API playground
- 🔄 Backend API for portfolio data
- 🔄 Real-time updates with Mercure
- 🔄 Admin panel for content management
- 🔄 Blog/articles section

## 🚦 Getting Started

### Prerequisites
- Node.js 18+ and npm
- PHP 8.2+
- Composer
- Docker & Docker Compose (optional)

### Frontend Setup

```bash
cd client
npm install
npm run dev
```

The frontend will be available at `http://localhost:3000`

### Backend Setup

```bash
cd api/api
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
symfony server:start
```

The API will be available at `http://localhost:8000`

## 📚 API Documentation

Once the backend is running, access the API documentation at:
- Swagger UI: `http://localhost:8000/api/docs`
- OpenAPI JSON: `http://localhost:8000/api/docs.json`

## 🎨 Design Philosophy

- **Clean & Modern**: Gradient backgrounds, smooth animations, and professional aesthetics
- **User-Centric**: Intuitive navigation and clear information hierarchy
- **Performance**: Optimized for fast loading and smooth interactions
- **Accessibility**: Semantic HTML and ARIA labels

## 📝 About Me

I'm Alexander Matte, a Junior Developer from Canada currently based in Baden-Württemberg, Germany. I'm completing my apprenticeship (Ausbildung) at Allnatura, specializing in:

- Backend development with PHP/Symfony
- E-commerce middleware architecture
- API Platform and REST API design
- Frontend development with Nuxt/Vue
- DevOps and CI/CD operations

## 🔗 Links

- **GitHub**: [@Alexander-Matte](https://github.com/Alexander-Matte)
- **LinkedIn**: [alex-matte71](https://www.linkedin.com/in/alex-matte71/)
- **Email**: alex.matte71@gmail.com

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

---

**By Alexander Matte**
