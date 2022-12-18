## Installation
Create Database and .env
```bash
  composer update
  
  php artisan migrate

  php artisan jwt:secret

  php artisan serve
```
**Enjoy**

## API Reference

#### Get item

```http
  POST /api/register
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `name`      | `string` | **Required** |
| `email`      | `email` | **Required** |
| `password`      | `string` | **Required, Min 6 Char** |
| `password_confirmation`      | `string` | **Required** |

```http
  POST /api/login
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `email`      | `email` | **Required** |
| `password`      | `string` | **Required** |

## API Reference (Middleware Bearer Token)

#### Get data user

```http
  GET /api/user
```

## Tech Stack

**Framework:** Laravel