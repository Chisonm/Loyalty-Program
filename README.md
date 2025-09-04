# Backend API

## Requirements

- **PHP**: ^8.2
- **Laravel**: ^12.0
- **MySQL**: 5.7+ or 8.0+
- **Composer**: 2.0+

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Chisonm/Loyalty-Program
cd backend-api
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit your `.env` file with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=backend_api
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

This will create:
- Badge tiers (Bronze, Silver, Gold, etc.)
- Sample achievements (First Purchase, Loyal Customer, etc.)
- Test users with progress data


## Development

### Running the Application

This command starts:
- Laravel development server (http://localhost:8000)

```bash
# Laravel server
php artisan serve
```

### Code Formatting

```bash
# Format code with Laravel Pint
./vendor/bin/pint
```

### Run Tests

```bash
# Run test
php artisan test
```

## API Endpoints

### Authentication
- `POST /api/login` - User login
- body: { 
    "email": "test@example.com", 
    "password": "password" 
}

### Users & Achievements
- `GET /api/users/{user}/achievements` - Get user achievements and progress
- `GET /api/users` - Get all users
- `PUT /api/users/{user}` - Update a user's progress

### Purchases
- `POST /api/checkout` - Process a purchase (requires auth)
- body: { "amount": 100 }


### Key Models
- `User` - User model with authentication
- `UserStats` - Tracks user purchase metrics
- `Badge` - Badge definitions with unlock requirements
- `Achievement` - Achievement definitions with progress tracking
- `Purchase` - Purchase records with amount and user


### Event System
- `PurchaseMade` - Fired when user completes purchase
- `BadgeUnlocked` - Fired when user unlocks new badge
- Background listeners update achievements and badges automatically


# Frontend

## Requirements
- **Node.js**: ^21.7.3
- **NPM**: ^8.19.2

## Installation
### 1. Clone the Repository
```bash
git clone https://github.com/Chisonm/Loyalty-Program
cd frontend
```
### 2. Install Dependencies
```bash
npm install
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env


### 3. Start the Development Server
```bash
npm run dev
```

## Running the Application
- Visit `http://localhost:5173` in your browser to access the frontend application.

## Screenshots
- Users
![Users](/Screenshot%202025-2.png)
- Achievements
![Achievements](/Screenshot%202025-1.png)
