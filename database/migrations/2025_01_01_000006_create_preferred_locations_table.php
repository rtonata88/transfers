<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preferred_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->constrained();
            $table->foreignId('town_id')->nullable()->constrained();
            $table->unsignedTinyInteger('priority'); // 1, 2, or 3
            $table->timestamps();

            $table->unique(['employee_profile_id', 'priority']);
            $table->index(['region_id', 'town_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preferred_locations');
    }
};
