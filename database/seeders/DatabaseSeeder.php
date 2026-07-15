<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'iPhone 15 Pro',      'price' => 134900, 'status' => true,  'meta' => ['color' => 'black',  'brand' => 'Apple']],
            ['name' => 'Samsung Galaxy S24', 'price' => 79999,  'status' => true,  'meta' => ['color' => 'white',  'brand' => 'Samsung']],
            ['name' => 'OnePlus 12',         'price' => 64999,  'status' => true,  'meta' => ['color' => 'green',  'brand' => 'OnePlus']],
            ['name' => 'MacBook Air M2',     'price' => 114900, 'status' => true,  'meta' => ['color' => 'silver', 'brand' => 'Apple']],
            ['name' => 'Dell XPS 15',        'price' => 189000, 'status' => false, 'meta' => ['color' => 'black',  'brand' => 'Dell']],
            ['name' => 'Sony WH-1000XM5',   'price' => 29990,  'status' => true,  'meta' => ['color' => 'black',  'brand' => 'Sony']],
            ['name' => 'iPad Pro 12.9',      'price' => 112900, 'status' => true,  'meta' => ['color' => 'gray',   'brand' => 'Apple']],
            ['name' => 'Realme GT 5 Pro',    'price' => 35999,  'status' => false, 'meta' => ['color' => 'blue',   'brand' => 'Realme']],
            ['name' => 'Logitech MX Master', 'price' => 9995,   'status' => true,  'meta' => ['color' => 'gray',   'brand' => 'Logitech']],
            ['name' => 'Mi Smart TV 55"',    'price' => 44999,  'status' => false, 'meta' => ['color' => 'black',  'brand' => 'Xiaomi']],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
