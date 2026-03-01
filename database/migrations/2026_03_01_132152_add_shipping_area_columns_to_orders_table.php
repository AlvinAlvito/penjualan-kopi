<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_province_id')->nullable()->after('promotion_id');
            $table->string('shipping_province_name')->nullable()->after('shipping_province_id');
            $table->string('shipping_city_id')->nullable()->after('shipping_province_name');
            $table->string('shipping_city_name')->nullable()->after('shipping_city_id');
            $table->string('shipping_district_id')->nullable()->after('shipping_city_name');
            $table->string('shipping_district_name')->nullable()->after('shipping_district_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_province_id',
                'shipping_province_name',
                'shipping_city_id',
                'shipping_city_name',
                'shipping_district_id',
                'shipping_district_name',
            ]);
        });
    }
};
