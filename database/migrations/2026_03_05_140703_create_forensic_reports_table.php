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
        Schema::create('forensic_reports', function (Blueprint $table) {
            $table->id();

            $table->date('extraction_date');
            $table->string('location');

            $table->string('equipment_type');

            $table->enum('remarks', ['Extracted', 'Not Extracted']);

            $table->text('reason_not_extracted')->nullable();

            $table->string('examiner_name');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forensic_reports');
    }
};
