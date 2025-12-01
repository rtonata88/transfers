<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('id');
            $table->boolean('is_admin')->default(false)->after('remember_token');
            $table->boolean('is_active')->default(true)->after('is_admin');
        });

        // Rename 'name' to keep it for display but we'll use first/last name in profile
        // Keep the name column as it's used by Fortify
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'is_admin', 'is_active']);
        });
    }
};
