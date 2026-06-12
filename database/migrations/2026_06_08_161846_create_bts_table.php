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
        Schema::create('bts', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('mgrs_location');

            $table->enum('network', [
                'GLOBE',
                'TM',
                'GOMO',
                'SMART',
                'TNT',
                'SUN'
            ]);

            $table->enum('network_mode', [
                '2G',
                '3G',
                '4G LTE',
                '5G'
            ]);

            $table->string('lac')->nullable();

            $table->string('cid')->nullable();

            $table->string('neighbor_cid')->nullable();

            $table->string('barangay')->nullable();

            $table->string('municipality')->nullable();

            $table->string('province')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bts');
    }
};
