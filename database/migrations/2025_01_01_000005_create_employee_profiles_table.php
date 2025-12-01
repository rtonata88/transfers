<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('employee_number')->nullable();
            $table->string('job_grade')->nullable();
            $table->foreignId('employer_id')->constrained();
            $table->foreignId('current_region_id')->constrained('regions');
            $table->foreignId('current_town_id')->constrained('towns');
            $table->string('contact_number');
            $table->string('alternative_contact_number')->nullable();
            $table->enum('preferred_communication', ['email', 'phone', 'both'])->default('email');
            $table->string('profile_picture')->nullable();
            $table->enum('probation_status', ['completed', 'on_probation', 'sick_leave', 'rehabilitation', 'under_investigation'])->default('completed');
            $table->text('probation_notes')->nullable();
            $table->boolean('is_available_for_transfer')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['current_region_id', 'current_town_id']);
            $table->index('employer_id');
            $table->index('is_available_for_transfer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_profiles');
    }
};
