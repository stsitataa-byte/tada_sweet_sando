<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('short_name')->nullable();
            $table->string('accent_color', 9)->nullable();
            $table->string('header_color', 9)->nullable();
            $table->json('fruits')->nullable();
            $table->unsignedTinyInteger('rating')->default(0);
            $table->boolean('detail_enabled')->default(false);
            $table->text('note')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'short_name',
                'accent_color',
                'header_color',
                'fruits',
                'rating',
                'detail_enabled',
                'note',
            ]);
        });
    }
};
