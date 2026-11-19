<?php

namespace Database\Seeders;

<<<<<<< HEAD
=======
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
<<<<<<< HEAD
        $this->call(ProductSeeder::class);
=======
        if (! Product::exists()) {
            foreach ([
                ['name' => 'Strawberry Cream', 'description' => 'Roti lembut dengan krim vanilla dan stroberi segar.', 'price' => 28000, 'stock' => 24],
                ['name' => 'Choco Berry', 'description' => 'Perpaduan cokelat lembut dan manisnya buah beri.', 'price' => 30000, 'stock' => 18],
                ['name' => 'Grape Milk', 'description' => 'Krim susu ringan dengan anggur pilihan.', 'price' => 28000, 'stock' => 20],
                ['name' => 'Peachy Cream', 'description' => 'Krim lembut dengan potongan buah persik.', 'price' => 30000, 'stock' => 16],
                ['name' => 'Cheese Cloud', 'description' => 'Krim keju gurih dalam roti susu yang empuk.', 'price' => 26000, 'stock' => 20],
                ['name' => 'Matcha Strawberry', 'description' => 'Matcha harum bertemu stroberi yang segar.', 'price' => 32000, 'stock' => 14],
            ] as $product) {
                $product['slug'] = Str::slug($product['name']);
                Product::create($product);
            }
        }

        $email = config('store.admin_email');
        $password = config('store.admin_password');

        if ($email && $password) {
            $admin = User::firstOrNew(['email' => $email]);
            if (! $admin->exists) {
                $admin->name = 'Admin Tada';
                $admin->password = $password;
            }
            $admin->is_admin = true;
            $admin->save();
        }
>>>>>>> a0a87ebae7737a00bf320e48c90d969c5abd53be
    }
}