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
        Schema::table('sre_events', function (Blueprint $table) {
            $table->string('bts_location')->nullable();
            $table->decimal('bts_lat', 10, 7)->nullable();
            $table->decimal('bts_lng', 10, 7)->nullable();
        });
    }

    public function down()
    {
        Schema::table('sre_events', function (Blueprint $table) {
            $table->dropColumn(['bts_location', 'bts_lat', 'bts_lng']);
        });
    }
};
