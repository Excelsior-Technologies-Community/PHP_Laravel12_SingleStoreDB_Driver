# PHP_Laravel12_SingleStoreDB_Driver


## Project Description

PHP_Laravel12_SingleStoreDB_Driver is a Laravel 12-based application that demonstrates how to integrate SingleStore with Laravel using its official driver.

The project shows how to configure a custom database connection, create tables using migrations, and perform full CRUD operations using Laravel’s Eloquent ORM.

It also demonstrates handling of JSON data, making it suitable for modern high-performance applications that require flexible data storage.


## Features

- Laravel 12 setup
- SingleStore database integration
- Custom database configuration
- RESTful API (CRUD operations)
- JSON column support
- Eloquent ORM usage
- API testing with Postman
- Clean project structure


## How It Works

- Laravel is installed and configured
- SingleStore driver is added via Composer
- Database connection is updated in database.php
- Migration creates a products table
- Model interacts with database using Eloquent
- Controller handles API logic
- Routes expose CRUD APIs
- API requests perform database operations



## Technologies Used

- PHP
- Laravel 12
- MySQL Protocol
- Composer
- Postman

---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_SingleStoreDB_Driver "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_SingleStoreDB_Driver

```

#### Explanation:

Creates a fresh Laravel 12 project and moves into the project directory to start development.




## STEP 2: Database Setup 

### Update database details:

```
DB_CONNECTION=singlestore
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_SingleStoreDB_Driver
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_SingleStoreDB_Driver

```

### Then Run:

```
php artisan migrate

```


#### Explanation:

Connects Laravel to MySQL and creates default system tables like users, cache, and jobs.




## STEP 3: Install Driver 

### Run:

```
composer require singlestoredb/singlestoredb-laravel

```

#### Explanation:

Installs the official SingleStore Laravel driver to enable database integration.



## STEP 4: Configure Database

### Open:

```
config/database.php

```

### Replace default connection:

```
'default' => env('DB_CONNECTION', 'singlestore'),

```


### Add new connection:

```
  'singlestore' => [
            'driver' => 'singlestore',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'singlestore_db'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,

            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_PERSISTENT => true,
            ]) : [],
        ],

```

#### Explanation:

Sets SingleStore as the default database and defines its connection settings.




## STEP 5: Create Migration

### Run:

```
php artisan make:migration create_products_table

```

### database/migrations/create_products_table.php

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->json('meta')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

```

### Then Run:

```
php artisan migrate

```

#### Explanation:

Creates a new table structure for products and applies it to the database.




## STEP 6: Create Model

### Run:

```
php artisan make:model Product

```

### app/Models/Product.php

```
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'price', 'meta'];

    protected $casts = [
        'meta' => 'array',
    ];
}

```

#### Explanation:

Creates a model to interact with the products table using Eloquent ORM.




## STEP 7: Create Controller

### Run:

```
php artisan make:controller ProductController

```

### app/Http/Controllers/ProductController.php

```
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // ✅ INDEX (GET all)
    public function index()
    {
        return response()->json(Product::all());
    }

    // ✅ STORE (POST)
    public function store(Request $request)
    {
        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'meta' => ['color' => 'red']
        ]);

        return response()->json([
            'message' => 'Product Created',
            'data' => $product
        ]);
    }

    // ✅ SHOW (GET single)
    public function show($id)
    {
        return response()->json(Product::findOrFail($id));
    }

    // ✅ UPDATE (PUT)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Product Updated',
            'data' => $product
        ]);
    }

    // ✅ DELETE (DELETE)
    public function destroy($id)
    {
        Product::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Product Deleted'
        ]);
    }
}

```

#### Explanation:

Creates a controller to handle CRUD operations (Create, Read, Update, Delete).




## STEP 8: API Routes

### routes/api.php

```
<?php

use App\Http\Controllers\ProductController;

Route::apiResource('products', ProductController::class);

```

#### Explanation:

Defines REST API endpoints using apiResource for automatic CRUD routing.




## STEP 9: Run the App  

### Start dev server:

```
php artisan serve

```

### Open in browser:

```
http://127.0.0.1:8000

```

#### Explanation:

Starts the Laravel development server to access the application in the browser.




## STEP 10: Test API


### POST (Insert)

1. Method: POST 

2. URL: 

```
http://127.0.0.1:8000/api/products

```

3. Body (JSON):

```
{
  "name": "Mobile",
  "price": 20000
}

```

#### Expected Output:


<img src="screenshots/Screenshot 2026-03-26 125131.png" width="900">



### GET (All Products)

1. Method: GET 

2. URL: 

```
http://127.0.0.1:8000/api/products/

```

#### Expected Output:


<img src="screenshots/Screenshot 2026-03-26 125149.png" width="900">




### GET Single

1. Method: GET 

2. URL: 

```
http://127.0.0.1:8000/api/products/1

```


#### Expected Output:


<img src="screenshots/Screenshot 2026-03-26 145721.png" width="900">




### UPDATE

1. Method: PUT 

2. URL: 

```
http://127.0.0.1:8000/api/products/2

```

3. Body (JSON):

```
{
  "name": "Apple Mobile",
  "price": 20000
}

```

#### Expected Output:


<img src="screenshots/Screenshot 2026-03-26 125329.png" width="900">




### DELETE

1. Method: DELETE 

2. URL: 

```
http://127.0.0.1:8000/api/products/2

```


#### Expected Output:


<img src="screenshots/Screenshot 2026-03-26 125429.png" width="900">





---

## Project Folder Structure:

```
PHP_Laravel12_SingleStoreDB_Driver/
│
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── ProductController.php
│   │   │
│   │   ├── Middleware/
│   │   │
│   │   └── Requests/
│   │
│   ├── Models/
│   │   └── Product.php
│   │
│   └── Providers/
│
├── bootstrap/
│
├── config/
│   └── database.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   │   └── xxxx_create_products_table.php
│   └── seeders/
│
├── public/
│   └── index.php
│
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
│
├── routes/
│   ├── api.php
│   └── web.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│
├── vendor/
│
├── .env
├── artisan
├── composer.json
└── README.md

```
