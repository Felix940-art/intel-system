<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('geo_ints', function (Blueprint $table) {

            $table->id();

            $table->dateTime('mission_datetime');

            $table->string('uav');

            $table->string('home_point_mgrs');

            $table->string('threat_confronted');

            $table->string('classification')->default('UNCLASSIFIED');

            $table->string('document_path')->nullable();

            $table->timestamps();

            $table->decimal('latitude', 10, 7)->nullable();

            $table->decimal('longitude', 10, 7)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geo_ints');
    }
};
