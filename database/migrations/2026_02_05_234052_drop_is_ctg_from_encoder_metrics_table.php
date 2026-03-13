<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('encoder_metrics', function (Blueprint $table) {
            if (Schema::hasColumn('encoder_metrics', 'is_ctg')) {
                $table->dropColumn('is_ctg');
            }
        });
    }

    public function down(): void
    {
        Schema::table('encoder_metrics', function (Blueprint $table) {
            $table->boolean('is_ctg')->default(false);
        });
    }
};
