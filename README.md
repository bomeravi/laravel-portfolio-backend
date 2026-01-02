> [!CAUTION]
> Currently hosting in Cpanel so, npm is disabled on production.yml for github workflows

# Saroj Personal Website

A modern personal portfolio website built with Laravel 12, featuring a comprehensive admin panel for managing blogs, projects, skills, experiences, and contact messages.

## 🚀 Features

-   **Admin Dashboard** - Statistics overview with real-time counts
-   **Blog Management** - Full CRUD with tags, categories, and featured images
-   **Project Portfolio** - Showcase your work with project details
-   **Skills & Experiences** - Manage your professional journey
-   **Contact Messages** - Receive and manage visitor inquiries (with Telegram notifications)
-   **User Authentication** - Secure login via Laravel Passport
-   **Profile Management** - Update avatar (file/URL), password, and user info

## 📋 Requirements

-   **PHP** >= 8.4
-   **Node.js** >= 20
-   **Composer** >= 2.x
-   **MySQL** >= 8.0 (or compatible database)

## 🛠️ Tech Stack

| Backend          | Frontend    |
| ---------------- | ----------- |
| Laravel 12       | Vite 7      |
| Laravel Passport |
| Laravel UI       | Bootstrap 5 |

## ⚙️ Installation

### 1. Clone the repository

```bash
git clone <repository-url>

```

### 2. Install dependencies

```bash
composer install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure the `.env` file

```env
APP_NAME="Saroj Personal Website"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=saroj_website
DB_USERNAME=root
DB_PASSWORD=

# Optional: Telegram notifications for contact messages
TELEGRAM_BOT_TOKEN=your_bot_token
TELEGRAM_CHAT_ID=your_chat_id

# Optional: Passport OAuth (if using API)
PASSPORT_PASSWORD_CLIENT_ID=
PASSPORT_PASSWORD_CLIENT_SECRET=
```

### 5. Run migrations

```bash
php artisan migrate
```

### 6. Install Node.js dependencies and build frontend assets

```bash
npm install
npm run build
```

## 🏃 Running the Application

### Development Mode

Using the composer dev script (runs server, queue, and vite concurrently):

```bash
composer dev
```

Or manually:

```bash
# Terminal 1: Laravel server
php artisan serve
```

### Production Build

```bash
npm run build
```

## 📁 Quick Setup (All-in-One)

Run the complete setup with a single command:

```bash
composer setup
```

This will:

1. Install PHP dependencies
2. Copy `.env.example` to `.env` (if not exists)
3. Generate application key
4. Run database migrations

## 🔄 CI/CD

This project supports automated deployments via **GitLab CI/CD** and **GitHub Actions**.

### Deployment Environments

| Environment | Branch    | Path                                  |
| ----------- | --------- | ------------------------------------- |
| Production  | `master`  | `/var/www/`    |
| Staging     | `staging` | `/var/www/html` |

---

### GitHub Actions

Workflows are located in `.github/workflows/`.

#### Required Secrets

Configure in **GitHub → Settings → Secrets and variables → Actions**:

| Secret     | Description                    |
| ---------- | ------------------------------ |
| `VPS_HOST` | VPS server IP address          |
| `VPS_USER` | SSH username for the server    |
| `VPS_KEY`  | SSH private key for VPS access |

#### Workflows

| File             | Branch    | Description                 |
| ---------------- | --------- | --------------------------- |
| `production.yml` | `master`  | Deploy to production server |
| `staging.yml`    | `staging` | Deploy to staging server    |

#### Deployment Steps

1. Push to `master` or `staging` branch
2. GitHub Actions connects to VPS via SSH
3. Pulls latest code, installs dependencies, runs migrations
4. Clears and rebuilds Laravel caches
5. Builds frontend assets with npm

---

### GitLab CI/CD

Pipeline is defined in `.gitlab-ci.yml`.

#### Required Variables

Configure in **GitLab → Settings → CI/CD → Variables**:

| Variable             | Description                              |
| -------------------- | ---------------------------------------- |
| `SSH_PRIVATE_KEY`    | SSH private key (ed25519) for VPS access |
| `SERVER_IP`          | VPS server IP address                    |
| `SSH_USER`           | SSH username for the server              |
| `DEPLOY_SCRIPT_PATH` | Path to deployment script on server      |

#### Deployment Flow

1. Push to `master` branch triggers the pipeline
2. Pipeline connects to VPS via SSH
3. Executes the deployment script on the server

## 🧪 Testing

```bash
composer test
```

## 📂 Project Structure

```
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   └── Api/            # API controllers
│   └── Models/             # Eloquent models
├── config/                 # Configuration files
├── database/
│   ├── migrations/         # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   ├── views/
│   │   ├── admin/          # Admin panel views
│   │   └── layouts/        # Layout templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript files
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes
└── zappz/                  # JSON schema files
```

## 🔑 Admin Panel

Access the admin panel at `/admin/dashboard` after logging in.

### Available Modules:

-   `/admin/dashboard` - Overview statistics
-   `/admin/users` - User management
-   `/admin/blogs` - Blog post management
-   `/admin/projects` - Project portfolio
-   `/admin/skills` - Skills management
-   `/admin/experiences` - Work experience
-   `/admin/categories` - Blog categories
-   `/admin/contact-messages` - Contact form submissions
-   `/admin/portfolio-items` - Portfolio items
-   `/admin/profile` - User profile settings

## 📝 Environment Variables

| Variable             | Description                    | Default          |
| -------------------- | ------------------------------ | ---------------- |
| `APP_NAME`           | Application name               | Laravel          |
| `APP_ENV`            | Environment (local/production) | local            |
| `APP_DEBUG`          | Enable debug mode              | true             |
| `APP_URL`            | Application URL                | http://localhost |
| `APP_REDIRECT_URL`   | Home page redirect URL         | -                |
| `DB_*`               | Database configuration         | MySQL            |
| `QUEUE_CONNECTION`   | Queue driver                   | database         |
| `TELEGRAM_BOT_TOKEN` | Telegram bot token             | -                |
| `TELEGRAM_CHAT_ID`   | Telegram chat ID               | -                |

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
