<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'slug' => 'mango',
                'name' => 'MANGO SANDO',
                'short_name' => 'Mango',
                'category' => 'single',
                'tagline' => 'Mangga matang yang manis legit, lembut, dan wangi tropis.',
                'description' => 'Mangga matang, lembut, dan wangi tropis yang memberi rasa manis alami pada setiap gigitan.',
                'price' => 38000,
                'color' => '#fff1b0',
                'accent_color' => '#ffb627',
                'header_color' => '#ffe27a',
                'tasting_notes' => 'Manis legit, lembut, tropis',
                'fruits' => ['mango'],
                'ingredients' => [
                    ['mango', 'Mangga matang segar'],
                    ['bread', 'Roti susu lembut (Japanese-style shokupan)'],
                    ['cream', 'Dairy whipping cream'],
                    ['sugar', 'Gula halus'],
                    ['vanilla', 'Ekstrak vanila'],
                ],
                'calories' => 240,
                'nutrition' => [
                    ['Energi', '220–250 kkal'],
                    ['Karbohidrat', '25–30 g'],
                    ['Protein', '4–5 g'],
                    ['Lemak', '11–14 g'],
                    ['Serat', '2–3 g'],
                    ['Gula', '10–14 g'],
                ],
                'image' => 'assets/mangga-sando.png',
                'rating' => 5,
                'detail_enabled' => false,
                'note' => null,
            ],
            [
                'slug' => 'strawberry',
                'name' => 'STRAWBERRY SANDO',
                'short_name' => 'Strawberry',
                'category' => 'single',
                'tagline' => 'Stroberi segar dengan rasa manis-asam yang lembut dan menyegarkan.',
                'description' => 'Dessert manis berpadu dengan rasa asam asli buah stroberi yang segar dan seimbang.',
                'price' => 38000,
                'color' => '#ffd3dd',
                'accent_color' => '#ff7a9a',
                'header_color' => '#ffdfe9',
                'tasting_notes' => 'Manis, asam segar, lembut',
                'fruits' => ['strawberry'],
                'ingredients' => [
                    ['strawberry', 'Stroberi segar'],
                    ['bread', 'Roti susu lembut (Japanese-style shokupan)'],
                    ['cream', 'Dairy whipping cream'],
                    ['sugar', 'Gula halus'],
                    ['vanilla', 'Ekstrak vanila'],
                ],
                'calories' => 240,
                'nutrition' => [
                    ['Energi', '220–250 kkal'],
                    ['Karbohidrat', '25–30 g'],
                    ['Protein', '4–5 g'],
                    ['Lemak', '11–14 g'],
                    ['Serat', '2–3 g'],
                    ['Gula', '10–14 g'],
                ],
                'image' => 'assets/strawberry-sando.png',
                'rating' => 4,
                'detail_enabled' => true,
                'note' => "untuk usia 5 tahun ke atas.\ntidak untuk di konsumsi berlebihan.",
            ],
            [
                'slug' => 'grape',
                'name' => 'GRAPE SANDO',
                'short_name' => 'Grape',
                'category' => 'single',
                'tagline' => 'Anggur manis dan juicy yang dipadukan dengan krim lembut serta roti susu yang empuk.',
                'description' => 'Anggur yang juicy dengan rasa manis alami dan tekstur lembut, cocok untuk sensasi segar yang ringan.',
                'price' => 38000,
                'color' => '#e3d6f7',
                'accent_color' => '#8e5bb5',
                'header_color' => '#d9c9f2',
                'tasting_notes' => 'Manis, juicy, menyegarkan',
                'fruits' => ['grape'],
                'ingredients' => [
                    ['grape', 'Anggur segar tanpa biji'],
                    ['bread', 'Roti susu lembut (Japanese-style shokupan)'],
                    ['cream', 'Dairy whipping cream'],
                    ['sugar', 'Gula halus'],
                    ['vanilla', 'Ekstrak vanila'],
                ],
                'calories' => 240,
                'nutrition' => [
                    ['Energi', '220–250 kkal'],
                    ['Karbohidrat', '25–30 g'],
                    ['Protein', '4–5 g'],
                    ['Lemak', '11–14 g'],
                    ['Serat', '2–3 g'],
                    ['Gula', '10–14 g'],
                ],
                'image' => 'assets/grape-sando.png',
                'rating' => 4,
                'detail_enabled' => false,
                'note' => null,
            ],
            [
                'slug' => 'mix',
                'name' => 'MIX SANDO',
                'short_name' => 'Mix',
                'category' => 'mix',
                'tagline' => 'Tiga buah favorit dalam satu sandwich lezat: stroberi, mangga, dan anggur.',
                'description' => 'Paduan stroberi, mangga, dan anggur segar dengan krim lembut serta roti susu empuk.',
                'price' => 48000,
                'color' => '#cfeccf',
                'accent_color' => '#ff8fb8',
                'header_color' => '#bfe8c4',
                'tasting_notes' => 'Manis, asam segar, tropis',
                'fruits' => ['strawberry', 'mango', 'grape'],
                'ingredients' => [
                    ['strawberry', 'Stroberi segar'],
                    ['mango', 'Mangga segar'],
                    ['grape', 'Anggur segar tanpa biji'],
                    ['bread', 'Roti susu lembut (Japanese-style shokupan)'],
                    ['cream', 'Dairy whipping cream'],
                    ['sugar', 'Gula halus'],
                    ['vanilla', 'Ekstrak vanila'],
                ],
                'calories' => 280,
                'nutrition' => [
                    ['Energi', '260–300 kkal'],
                    ['Karbohidrat', '30–35 g'],
                    ['Protein', '4–5 g'],
                    ['Lemak', '12–16 g'],
                    ['Serat', '3–4 g'],
                    ['Gula', '12–18 g'],
                ],
        ];
                'image' => 'strawberry-sando.png',
                'rating' => 5,
                'detail_enabled' => false,
                'note' => null,
            ],
        ];

        foreach ($items as $sortOrder => $item) {
            Product::updateOrCreate(
                ['slug' => $item['slug']],
                [...$item, 'sort_order' => $sortOrder]
            );
        }
    }
}