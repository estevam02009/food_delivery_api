<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->nullable();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('average_rating', 3, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('average_rating');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('average_rating');
        });
    }
};
