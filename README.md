# Library Management API

This is a Library Management API built with Laravel, allowing you to manage books, authors, users, and borrowing records. The API supports user roles such as Admin, Librarian, and Member, with different permissions for each role.

## Features

- **Books:** Create, update, delete, search, borrow, and return books.
- **Authors:** Manage authors.
- **Users:** Manage users, with authentication and role-based access control.
- **Borrow Records:** Manage and track book borrowings.
- **Authentication:** Token-based authentication using Laravel Sanctum.
- **Rate Limiting:** Throttled routes to prevent abuse.
- **Unit Tests:** Unit tests are implemented for each route

## Requirements

- PHP >= 8.0
- Composer
- Laravel >= 9.x
- MySQL or any relational database supported by Laravel

## Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/Emmo00/library-management-api.git
cd library-management-api
```

### 2. Install Dependencies

Install the PHP dependencies:

```bash
composer install
```

### 3. Set Up Environment Variables

Create a `.env` file by copying `.env.example`:

```bash
cp .env.example .env
```

Update the following settings in the `.env` file:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password

SANCTUM_STATEFUL_DOMAINS="localhost"
SESSION_DOMAIN="localhost"
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Run Migrations and Seed the Database

Run the database migrations:

```bash
php artisan migrate:fresh
```

### 6. Serve the Application Locally

```bash
php artisan serve
```

The application will now be running at `http://127.0.0.1:8000`.

### 7. Running Tests

To run the feature and unit tests for your application:

```bash
php artisan test
```

## API Endpoints

Here is an overview of the available API endpoints.

### Authentication

- **POST** `/login`: Authenticate a user and retrieve a Sanctum token.

### Books

- **GET** `/books`: Retrieve a list of all books.
- **GET** `/books/search`: Search for books by title, author, or ISBN.
- **GET** `/books/{id}`: Retrieve details of a specific book.
- **POST** `/books`: Create a new book (Admin/Librarian only).
- **PUT** `/books/{id}`: Update an existing book (Admin/Librarian only).
- **DELETE** `/books/{id}`: Delete a book (Admin only).
- **POST** `/books/{id}/borrow`: Borrow a book (Member only, if available).
- **POST** `/books/{id}/return`: Return a borrowed book (Member only).

### Authors

- **GET** `/authors`: Retrieve a list of all authors.
- **GET** `/authors/{id}`: Retrieve details of a specific author.
- **POST** `/authors`: Create a new author (Admin/Librarian only).
- **PUT** `/authors/{id}`: Update an author (Admin/Librarian only).
- **DELETE** `/authors/{id}`: Delete an author (Admin only).

### Users

- **GET** `/users`: Retrieve a list of users (Admin only).
- **GET** `/users/{id}`: Retrieve details of a specific user (Admin only).
- **POST** `/users`: Register a new user.
- **PUT** `/users/{id}`: Update user details (Admin only or self).
- **DELETE** `/users/{id}`: Delete a user (Admin only).

- **POST** `/create-librarian`: Register a new (Librarian) user.

### Borrow Records

- **GET** `/borrow-records`: Retrieve all borrow records (Admin/Librarian only).
- **GET** `/borrow-records/{id}`: Retrieve details of a specific borrow record (Admin/Librarian only).

### Roles and Permissions

The application uses role-based access control with the following roles:
- **Admin:** Full access to manage books, authors, users, and borrow records.
- **Librarian:** Can manage books, authors, and borrow records.
- **Member:** Can borrow and return books, view personal details.

## Contribution Guidelines

Feel free to submit a pull request or open an issue for any bug fixes or feature requests.
