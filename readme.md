# StaffTrack - Employee Management System

StaffTrack is a Laravel, AJAX, jQuery, DataTables, Bootstrap 5, and MySQL employee management system built from the `yajra/laravel-datatables-demo` base project.

Live Demo: [https://stafftrack-production.up.railway.app](https://stafftrack-production.up.railway.app)

Built by **V Augustin Prabakar**  
GitHub: [vaugustin250](https://github.com/vaugustin250)  
Email: vaugustin250@gmail.com

## Features

- Employee dashboard with statistics cards
- Server-side DataTables employee listing
- AJAX add and edit employee modal
- AJAX delete confirmation with SweetAlert2
- Department filter dropdown
- CSV and PDF export buttons
- Bootstrap 5 layout and components
- Active and inactive status badges
- Indian employee dummy data

## Employee Fields

- Name
- Email
- Department
- Salary
- Join date
- Status: active or inactive

## Tech Stack

- Laravel 5.1
- MySQL
- jQuery
- Yajra Laravel DataTables
- DataTables Buttons
- Bootstrap 5
- SweetAlert2

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Update `.env` with your MySQL credentials:

```env
DB_HOST=127.0.0.1
DB_DATABASE=stafftrack
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations and seeders:

```bash
php artisan migrate --seed
```

Start the application:

```bash
php artisan serve
```

Open:

```text
http://localhost:8000
```

## Recommended Deployment

The easiest production-style deployment for StaffTrack is **Railway with Docker and MySQL**.

1. Push this repository to GitHub.
2. Open [Railway](https://railway.app/) and create a new project from the GitHub repository.
3. Add a MySQL database service in the same Railway project.
4. Set these variables on the web service:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=SomeRandomStringForStaffTrackDemo
DB_CONNECTION=mysql
DB_HOST=${{ MySQL.MYSQLHOST }}
DB_PORT=${{ MySQL.MYSQLPORT }}
DB_DATABASE=${{ MySQL.MYSQLDATABASE }}
DB_USERNAME=${{ MySQL.MYSQLUSER }}
DB_PASSWORD=${{ MySQL.MYSQLPASSWORD }}
RUN_SEEDERS=true
```

5. Deploy. The Docker startup script waits for MySQL, runs migrations, optionally seeds Indian employee data, and starts Apache against Laravel's `public` directory.

For later production use, set `RUN_SEEDERS=false` after the first successful deployment.

## Main Files

- `database/migrations/2026_05_28_000000_create_employees_table.php`
- `database/seeds/EmployeesTableSeeder.php`
- `app/Employee.php`
- `app/Http/Controllers/EmployeeController.php`
- `resources/views/employees/index.blade.php`
- `resources/views/app.blade.php`
- `public/css/stafftrack.css`
- `app/Http/routes.php`
- `Dockerfile`
- `railway.json`
- `docker/start.sh`
