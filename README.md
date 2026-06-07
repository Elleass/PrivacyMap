# PrivacyMap

PrivacyMap is a custom PHP MVC web application for manually mapping a user's digital data footprint. Users add internet services they use, select which personal data types are connected to each service, and the app calculates privacy risk, shows dashboard statistics, and generates local privacy recommendations.

The app does not store passwords, API tokens, or login data for external services. It does not connect to Google, Meta, banks, stores, or other external accounts. All service exposure data is entered manually by the user.

## Features

- User registration, login, logout and session-protected pages.
- Roles: `user` and `admin`.
- User dashboard with service counts, data points, email/phone/address exposure, high-risk count and privacy health score.
- CRUD for user services with category, URL, notes and selected data types.
- Many-to-many relation between user services and data types.
- Automatic risk score and risk level calculation from data type sensitivity weights.
- Local privacy recommendations that can be marked as completed.
- Insights page with data type distribution and risk overview.
- Admin panel for categories, data types, global service catalog and user overview.
- Audit logs for important actions.
- Responsive layout for desktop and mobile.

## Technologies

- PHP 8 with a custom lightweight MVC structure.
- PostgreSQL.
- PDO prepared statements.
- Docker, Nginx and PHP-FPM.
- HTML, CSS and small vanilla JavaScript for the mobile navigation.

## Project Structure

```text
.
├── docker/                 Docker images and database init scripts
├── docs/                   ER diagram and project documentation assets
├── public/
│   ├── scripts/            Frontend JavaScript
│   ├── styles/             CSS
│   └── views/              PHP-rendered HTML views
├── src/
│   ├── controllers/        MVC controllers
│   └── repositories/       Database access classes
├── Database.php            PDO database connection
├── Routing.php             Custom router
├── config.php              Database config with env overrides
└── index.php               Front controller
```

## Database Model

The schema is stored in [docker/db/init/schema.sql](docker/db/init/schema.sql). Seed data is stored in [docker/db/init/seed.sql](docker/db/init/seed.sql).
An export-style restore script is available at [docs/privacy_map_backup.sql](docs/privacy_map_backup.sql).

Main tables:

- `roles`
- `users`
- `categories`
- `services`
- `user_services`
- `data_types`
- `service_data_types`
- `recommendations`
- `audit_logs`

The schema also includes a PostgreSQL view (`user_service_summary`), trigger-backed `updated_at` maintenance, and database functions for risk/health calculations.

ER diagram: [docs/er-diagram.md](docs/er-diagram.md)

## Running Locally

1. Start the containers:

```bash
docker compose up --build
```

2. Open the application:

```text
http://localhost:8080
```

3. Open pgAdmin if needed:

```text
http://localhost:5050
```

Database defaults:

```text
host: db
database: db
user: docker
password: docker
```

The first Docker database startup loads:

```text
docker/db/init/schema.sql
docker/db/init/seed.sql
```

If the database volume already exists, recreate it to rerun seed scripts.

## Test Accounts

```text
Admin:
email: admin@example.com
password: password

User:
email: user@example.com
password: password
```

New accounts created through the registration form use `password_hash()` and are verified with `password_verify()`.

## Routes

- `/login`
- `/register`
- `/logout`
- `/dashboard`
- `/services`
- `/services/add`
- `/services/{id}`
- `/services/{id}/edit`
- `/insights`
- `/admin`

## Security Notes

- Passwords are stored as hashes.
- SQL queries use PDO prepared statements.
- Views escape user-controlled output with `htmlspecialchars()`.
- POST forms use CSRF tokens.
- Users can only view and modify their own services.
- Admin-only routes require the `admin` role.
- Demo data is fictional.

 
