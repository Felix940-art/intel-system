<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sre_selectors', function (Blueprint $table) {

            if (!Schema::hasColumn('sre_selectors', 'threat_group')) {
                $table->string('threat_group')->nullable()->after('selector_type');
            }

            if (!Schema::hasColumn('sre_selectors', 'code_name')) {
                $table->string('code_name')->nullable()->after('threat_group');
            }

            if (!Schema::hasColumn('sre_selectors', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sre_selectors', function (Blueprint $table) {
            $table->dropColumn(['threat_group', 'code_name', 'is_active']);
        });
    }
};
