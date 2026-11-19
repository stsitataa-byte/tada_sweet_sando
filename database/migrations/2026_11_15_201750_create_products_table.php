<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('name');
            $t->string('category');
            $t->string('tagline');
            $t->text('description');
            $t->unsignedInteger('price');
            $t->string('color', 9);
            $t->text('tasting_notes');
            $t->text('ingredients');
            $t->unsignedSmallInteger('calories');
            $t->json('nutrition');
            $t->string('image');
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};