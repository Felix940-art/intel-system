<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sre_events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sre_selector_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamp('observed_at');

            $table->string('imei')->nullable();
            $table->string('imsi')->nullable();
            $table->string('lac')->nullable();
            $table->string('cid')->nullable();
            $table->string('bts_location')->nullable();
            $table->decimal('bts_lat', 10, 7)->nullable();
            $table->decimal('bts_lng', 10, 7)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sre_events');
    }
};
