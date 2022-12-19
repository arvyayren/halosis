## Installation
Create Database and .env
```bash
  composer update
  
  php artisan migrate

  php artisan jwt:secret

  php artisan serve
```
**⚡️Enjoy**

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

#### Get data barang

```http
  GET /api/barang
```

#### Store data barang

```http
  POST /api/barang
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `nama`      | `string` | **Required** |
| `kode`      | `string` | **Required,Uniqe** |
| `harga`      | `numeric` | **Required** |

#### Update data barang

```http
  PUT|PATCH /api/barang/{$id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `nama`      | `string` | **Required** |
| `kode`      | `string` | **Required,Uniqe** |
| `harga`      | `numeric` | **Required** |

#### Delete data barang

```http
  DELETE /api/barang/{$id}
```

#### Get data cart

```http
  GET /api/cart
```

#### Add to cart

```http
  POST /api/cart
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `kode_barang`      | `string` | **Required** |
| `qty`      | `numeric` | **Required** |

#### Update data barang in cart

```http
  PUT /api/cart/update
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `kode_barang`      | `string` | **Required** |
| `qty`      | `numeric` | **Required** |

#### Empty cart

```http
  POST /api/cart/empty
```

#### Checkout

```http
  POST /api/cart/checkout
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `nama_customer`      | `string` | **Required** |
| `alamat`      | `string` | **Required** |

## Tech Stack

**Framework:** Laravel