<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false);
            });
        }

        if (Schema::hasColumn('users', 'role')) {
            DB::table('users')->where('role', 'admin')->update(['is_admin' => true]);
        }

        if (! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->text('description')->nullable();
                $table->decimal('price', 12, 2);
                $table->unsignedInteger('stock')->default(0);
                $table->unsignedInteger('sold_quantity')->default(0);
                $table->string('image_url')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        } else {
            Schema::table('products', function (Blueprint $table) {
                if (! Schema::hasColumn('products', 'stock')) {
                    $table->unsignedInteger('stock')->default(0);
                }
                if (! Schema::hasColumn('products', 'sold_quantity')) {
                    $table->unsignedInteger('sold_quantity')->default(0);
                }
                if (! Schema::hasColumn('products', 'image_url')) {
                    $table->string('image_url')->nullable();
                }
                if (! Schema::hasColumn('products', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('order_code')->unique();
                $table->string('customer_name');
                $table->string('customer_email')->nullable();
                $table->string('customer_phone', 30);
                $table->text('customer_address')->nullable();
                $table->text('notes')->nullable();
                $table->decimal('subtotal', 12, 2);
                $table->decimal('shipping_cost', 12, 2)->default(0);
                $table->decimal('total', 12, 2);
                $table->string('payment_method', 20)->default('COD');
                $table->string('payment_status', 20)->nullable();
                $table->string('order_status', 20)->default('pending');
                $table->text('admin_note')->nullable();
                $table->timestamps();
            });
        } elseif (! Schema::hasColumn('orders', 'admin_note')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->text('admin_note')->nullable();
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
                $table->string('product_name');
                $table->decimal('product_price', 12, 2);
                $table->unsignedInteger('quantity');
                $table->decimal('subtotal', 12, 2);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('cash_flows')) {
            Schema::create('cash_flows', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
                $table->string('type', 10);
                $table->decimal('amount', 12, 2);
                $table->string('description');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_flows');

        if (Schema::hasColumn('orders', 'admin_note')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('admin_note');
            });
        }

        if (Schema::hasColumn('products', 'sold_quantity')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('sold_quantity');
            });
        }

        if (Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin');
            });
        }
    }
};
