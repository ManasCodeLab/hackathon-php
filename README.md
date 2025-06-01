# CodeGenX Hackathon Website

This is the official website for the CodeGenX Hackathon, built with PHP and MongoDB.

## Features

- User authentication (register/login)
- Hackathon registration (solo/team)
- Real-time countdown timer
- Responsive design
- Admin dashboard
- Email notifications

## Tech Stack

- Frontend: HTML, CSS (Tailwind), JavaScript
- Backend: Node.js, Express
- Database: MongoDB
- Authentication: JWT

## Prerequisites

- PHP 8.0 or higher
- Composer
- MongoDB
- Vercel CLI (for deployment)

## Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd codegenx-hackathon
```

2. Install dependencies:
```bash
composer install
```

3. Copy the environment file:
```bash
cp .env.example .env
```

4. Update the `.env` file with your MongoDB connection string and JWT secret.

## Development

The project uses PHP's built-in development server for local development:

```bash
php -S localhost:8000 -t public
```

## API Endpoints

### Authentication

- POST `/api/auth`
  - Login endpoint
  - Body: `{ "email": "user@example.com", "password": "password" }`

### Registration

- POST `/api/registration`
  - Register new user
  - Body: `{ "email": "user@example.com", "password": "password" }`

## Deployment

This project is configured for deployment on Vercel. To deploy:

1. Install Vercel CLI:
```bash
npm i -g vercel
```

2. Deploy:
```bash
vercel
```

## Project Structure

```
├── api/              # PHP API endpoints
├── public/           # Static files and frontend
├── src/             # PHP source code
│   ├── Config/      # Configuration classes
│   ├── Controllers/ # API controllers
│   └── Models/      # Data models
├── composer.json    # PHP dependencies
├── vercel.json      # Vercel configuration
└── .env            # Environment variables
```

## Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a new Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details. 