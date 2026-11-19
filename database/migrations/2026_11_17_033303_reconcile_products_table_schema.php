<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'slug' => fn (Blueprint $table) => $table->string('slug')->nullable()->unique(),
            'name' => fn (Blueprint $table) => $table->string('name')->nullable(),
            'category' => fn (Blueprint $table) => $table->string('category')->nullable(),
            'tagline' => fn (Blueprint $table) => $table->string('tagline')->nullable(),
            'description' => fn (Blueprint $table) => $table->text('description')->nullable(),
            'price' => fn (Blueprint $table) => $table->unsignedInteger('price')->nullable(),
            'color' => fn (Blueprint $table) => $table->string('color', 9)->nullable(),
            'tasting_notes' => fn (Blueprint $table) => $table->text('tasting_notes')->nullable(),
            'ingredients' => fn (Blueprint $table) => $table->text('ingredients')->nullable(),
            'calories' => fn (Blueprint $table) => $table->unsignedSmallInteger('calories')->nullable(),
            'nutrition' => fn (Blueprint $table) => $table->json('nutrition')->nullable(),
            'image' => fn (Blueprint $table) => $table->string('image')->nullable(),
            'sort_order' => fn (Blueprint $table) => $table->unsignedSmallInteger('sort_order')->default(0),
        ];

        foreach ($columns as $name => $addColumn) {
            if (! Schema::hasColumn('products', $name)) {
                Schema::table('products', $addColumn);
            }
        }
    }

    public function down(): void
    {
        // Keep reconciled columns to avoid removing fields created by the original products migration.
    }
};
