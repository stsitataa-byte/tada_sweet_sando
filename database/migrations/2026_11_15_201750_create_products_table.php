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
            $t->string('category')->nullable();
            $t->string('tagline')->nullable();
            $t->text('description');
            $t->unsignedInteger('price');
            $t->string('color', 9)->nullable();
            $t->text('tasting_notes')->nullable();
            $t->text('ingredients')->nullable();
            $t->unsignedSmallInteger('calories')->nullable();
            $t->json('nutrition')->nullable();
            $t->string('image')->nullable();
            $t->unsignedSmallInteger('sort_order')->default(0);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};