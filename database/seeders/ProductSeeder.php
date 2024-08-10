<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            [
                'name' => 'Laptop',
                'price' => 1500.00,
                'image' => 'laptop.png',
                'product_category_id' => 1 // Electronics
            ],
            [
                'name' => 'T-Shirt',
                'price' => 25.00,
                'image' => 'tshirt.png',
                'product_category_id' => 2 // Clothing
            ],
            [
                'name' => 'Book',
                'price' => 12.00,
                'image' => 'book.png',
                'product_category_id' => 3 // Books
            ],
            [
                'name' => 'Sofa',
                'price' => 300.00,
                'image' => 'sofa.png',
                'product_category_id' => 4 // Furniture
            ],
        ]);
    }
}
