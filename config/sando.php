﻿<?php

/*
|--------------------------------------------------------------------------
| TADA SWEETSANDO - SATU-SATUNYA FILE UNTUK MENGUBAH ISI WEBSITE
|--------------------------------------------------------------------------
| Nilai null  = "belum diisi". Website menampilkan keterangan "belum diisi"
|               dan TIDAK mengarang data.
| Harga null  = produk tidak bisa masuk keranjang sampai diisi.
| Harga ditulis angka biasa tanpa titik. Contoh: 25000
*/

return [

    'currency' => 'Rp',

    // Nomor WhatsApp penjual (format internasional tanpa +, contoh 6281234567890). Kosongkan jika belum ada.
    'whatsapp' => '',

    // Teks berjalan di bagian paling atas.
    'announcement' => [
        'REAL FRUITS',
        'SOFT CREAM',
        'SWEET MOMENTS WITH TADA SWEETSANDO!',
        'FRESH FRUIT SANDO MADE DAILY',
    ],

    // Gambar non-produk. Letakkan file di public/assets/ lalu samakan namanya di sini.
    'images' => [
        'logo'    => 'assets/logo-tada-sweetsando.png',
        'hero'    => null,                       // null = pakai foto Mix Fruit
        'story'   => 'assets/story.png',
        'mission' => 'assets/mission.png',
        'about'   => 'assets/about.png',
    ],

    'hero' => [
        'kicker'   => 'Fruit sando made daily',
        'headline' => 'FRESH FRUIT SANDO',
        'text'     => 'Roti tawar lembut, whipping cream vanila, dan buah pilihan. Ada Mangga, Grape, Strawberry, dan Mix Fruit.',
        'button'   => 'Order sekarang',
    ],

    'story' => [
        'tabs' => [
            [
                'label' => 'The Fruit',
                'lines' => ['FRESH FRUIT', 'IN EVERY BITE'],
                'text'  => 'Empat varian rasa: Mangga, Grape, Strawberry, dan Mix Fruit. Ganti teks ini dengan cerita buah pilihan Anda.',
            ],
            [
                'label' => 'The Cream',
                'lines' => ['SOFT CREAM', 'SWEET & LIGHT'],
                'text'  => 'Whipping cream vanila yang lembut menyatu dengan buah. Ganti teks ini dengan cerita krim Anda.',
            ],
            [
                'label' => 'The Bread',
                'lines' => ['SOFT BREAD', 'MADE DAILY'],
                'text'  => 'Roti tawar yang lembut sebagai pembungkus. Ganti teks ini dengan cerita roti Anda.',
            ],
        ],
    ],

    'mission' => [
        'title'  => 'FROM THE FRUIT MOVE TO YOUR HAND',
        'points' => [
            ['title' => 'Real fruit', 'text' => 'Buah asli di setiap varian. juicy dan legitnya mangga, anggur yang watery tampa biji, dan strawberry yang manis asam yang dapat membuatmu selalu menikmatinya.'],
            ['title' => 'Soft cream', 'text' => 'Whipping cream yang lembut. dengan tambahan vanila yang khas membuat rasa soft balance.'],
            ['title' => 'Made daily', 'text' => 'Fruit sando dibuat setiap hari. dapat di pastikan fresh dan cocok untuk anak yang suka dessert.'],
        'images' => [
            'assets/logo-tada-sweetsando.png',
            ],
    ],

    'about' => [
        'title' => 'ABOUT TADA SWEETSANDO',
        'text'  => [
            'Dibuat dengan kreatif dan ketelitian, serta keinginan untuk membuat dessert yang nikmat dansehat'],
            'Desert yang kami kenalkan bukan hanya tentang cream vanila yang khas, namun kami menghadirkan buah lembut yg menyajikan dessert manis alami.'],

    // Kosong = tampil kartu placeholder. Isi hanya dengan ulasan pelanggan asli. Contoh item:
    // ['name' => 'Nama', 'text' => 'Isi ulasan', 'photo' => 'assets/review-1.png'],
    'testimonials' => [],

    // answer null = tampil "belum diisi"
    'faq' => [
        ['q' => 'Apa itu fruit sando?',
         'a' => 'Fruit sando adalah sandwich roti tawar lembut berisi whipping cream dan potongan buah.'],
        ['q' => 'Ada varian dan ukuran apa saja?',
         'a' => 'Ada empat varian: Mangga, Grape, Strawberry, dan Mix Fruit. Setiap varian tersedia dalam ukuran Small dan Regular.'],
        ['q' => 'Bagaimana cara memesan?',
         'a' => 'Pilih varian dan ukuran di halaman Shop, tekan Add to Cart, lalu lanjut ke Checkout.'],
        ['q' => 'Untuk usia berapa fruit sando ini?',
         'a' => 'Untuk usia 5 tahun ke atas. Tidak untuk dikonsumsi berlebihan.'],
        ['q' => 'Bagaimana cara penyimpanan dan masa simpannya?', 'a' => null],
        ['q' => 'Bagaimana pengiriman dan pembayarannya?',        'a' => null],
    ],

    // Isi URL lengkap (https://...). Kosong = teks tampil tanpa link.
    'socials' => [
        'TikTok'    => '',
        'Twitter'   => '',
        'Instagram' => '',
        'Tumblr'    => '',
    ],

    'contact' => [
        'email'   => 'sandoEXAMPLE@gmail.com',
        'phone'   => '+62 815-1188-9523',
        'address' => '',
    ],

    // Mini game. Hadiah hanya menampilkan teks ini, TIDAK otomatis memotong harga.
    'game' => [
        'target'      => 10,
        'reward_text' => '',
    ],

    // Catatan di bawah kolom ingredients (bisa dioverride per produk lewat 'note').
    'product_note' => "untuk usia 5 tahun ke atas.\ntidak untuk di konsumsi berlebihan.",

    /*
    | PRODUK (4 varian, masing-masing Small & Regular, hanya 4 foto)
    | image       : path di dalam folder public/. Samakan dengan NAMA FILE ASLI
    |               foto Anda di public/assets/ (cek dengan: dir public\assets)
    | color       : warna latar kolom kiri + tengah (boleh gradient CSS)
    | color_right : warna latar kolom kanan (ingredients)
    | accent      : warna judul nama varian
    | rating      : angka 1-5, atau null (tidak ditampilkan)
    | price_*     : angka, atau null (belum diisi)
    */
    'products' => [

        'mango' => [
            'name'        => 'Mangga',
            'title'       => 'mangga',
            'tagline'     => 'sweet, creamy, tropical',
            'description' => 'anda akan mendapatkan nikmatnya  kelembutan roti tawar dengan whipping cream vanila bercampur dengan raa mangga yang khas dengan wangi yang mengugah selera.',
            'image'       => 'assets/mangga-sando.png',
            'color'       => '#f6dc6e',
            'color_right' => '#f6dc6e',
            'accent'      => '#5f8f4a',
            'rating'      => 6,
            'available'   => true,
            'price_small'   => 9000,   
            'price_regular' => 13000,   
            'ingredients' => ['Roti tawar', 'Whipping cream', 'Gula halus', 'Perisa vanila', 'mangga'],
            'nutrition'   => [
                'energi' => '220–250 kkal', 'karbohidrat' => '25–30 g', 'protein' => '4–5 g',
                'lemak'  => '11–14 g',      'serat'       => '2–3 g',   'gula'    => '10–14 g',
            ],
            'note'        => null,
        ],

        'grape' => [
            'name'        => 'Grape',
            'title'       => 'grape',
            'tagline'     => 'juicy, fresh, sweet',
            'description' => null,        
            'image'       => 'assets/grape-sando.png',
            'color'       => '#d6efb4',
            'color_right' => '#ffffff',
            'accent'      => '#5a2f8f',
            'rating'      => null,
            'available'   => true,
            'price_small'   => 9000,  
            'price_regular' => 13000,   
            'ingredients' => null,            // <-- ISI BAHAN GRAPE
            'nutrition'   => null,            // <-- ISI GIZI GRAPE
            'note'        => null,
        ],

        'strawberry' => [
            'name'        => 'Strawberry',
            'title'       => 'strawberry',
            'tagline'     => 'sweet, tangy, bright',
            'description' => 'anda akan mendapatkan nikmatnya dessert manis berpadu dengan  rasa asam asli buah strawberry yang dapat membuat mood bost dan segar yang balance.',
            'image'       => 'assets/strawberry-sando.png',
            'color'       => '#fbd0dc',
            'color_right' => '#ffffff',
            'accent'      => '#d3122b',
            'rating'      => null,
            'available'   => true,
            'price_small'   => 9000, 
            'price_regular' => 13000,  
            'ingredients' => ['Roti tawar', 'Whipping cream', 'Gula halus', 'Perisa vanila', 'Stroberi'],
            'nutrition'   => [
                'energi' => '220–250 kkal', 'karbohidrat' => '25–30 g', 'protein' => '4–5 g',
                'lemak'  => '11–14 g',      'serat'       => '2–3 g',   'gula'    => '10–14 g',
            ],
            'note'        => null,
        ],

        'mix-fruit' => [
            'name'        => 'Mix Fruit',
            'title'       => 'mix fruit sando',
            'tagline'     => 'fruity, soft, colorful',
            'description' => 'anda akan mendapatkan  rasa sekaligus dalam 1 pcs fruit sando, anda dapat merasakan sensasi',
            'image'       => 'assets/mix-fruit.png',
            'color'       => 'linear-gradient(120deg, #fff59d 0%, #f8c3d4 55%, #f9d6dd 100%)',
            'color_right' => '#ffffff',
            'accent'      => '#4a1c18',
            'rating'      => null,
            'available'   => true,
            'price_small'   => 9000, 
            'price_regular' => 13000,   
            'ingredients' => ['Roti tawar', 'Whipping cream', 'Gula halus', 'Perisa vanila', 'random fruits'],
            'nutrition'   => [
                'energi' => '220–250 kkal', 'karbohidrat' => '25–30 g', 'protein' => '4–5 g',
                'lemak'  => '11–14 g',      'serat'       => '2–3 g',   'gula'    => '10–14 g',
            ],
            'note'        => null,
        ],
    ],

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background: #101010;
    overflow: hidden;
}

.container {
    width: min(85vw, 1000px);
    aspect-ratio: 16 / 9;
    perspective: 1200px;
}

.photo {
    width: 100%;
    height: 100%;
    border-radius: 15px;
    overflow: hidden;
    transform-style: preserve-3d;
    transition: transform 0.15s ease-out;
    box-shadow: 0 25px 70px rgba(0,0,0,.6);
}

.photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transform: scale(1.08);
}

</style>
</head>

<body>

<div class="container">
    <div class="photo" id="photo">
        <img src="foto.jpg" alt="My Photo">
    </div>
</div>

<script>
const photo = document.getElementById("photo");

document.addEventListener("mousemove", (e) => {
    const x = (window.innerWidth / 2 - e.clientX) / 30;
    const y = (window.innerHeight / 2 - e.clientY) / 30;

    photo.style.transform =
        `rotateY(${-x}deg) rotateX(${y}deg)`;
});

document.addEventListener("mouseleave", () => {
    photo.style.transform = "rotateY(0deg) rotateX(0deg)";
});

];
