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
        Schema::create('bts_sites', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('mgrs_location');

            $table->enum('network', [
                'SMART',
                'SUN',
                'TNT',
                'GLOBE',
                'TM',
                'GOMO'
            ]);

            $table->string('network_mode');

            $table->string('lac');
            $table->string('cid');

            $table->string('neighboring_cid')->nullable();

            $table->string('barangay');
            $table->string('municipality');
            $table->string('province');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bts_sites');
    }
};
