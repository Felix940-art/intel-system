<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sre_selectors', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('selector_type', [
                'MSISDN',
                'IMSI',
                'IMEI',
                'OTHER'
            ]);

            $table->string('selector_value')->index();

            $table->string('threat_group')->nullable();
            $table->string('code_name')->nullable();
            $table->text('remarks')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sre_selectors');
    }
};
