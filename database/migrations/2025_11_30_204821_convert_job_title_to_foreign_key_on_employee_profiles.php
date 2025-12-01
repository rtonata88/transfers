<?php

use App\Models\JobTitle;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the new job_title_id column
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->foreignId('job_title_id')->nullable()->after('job_grade')->constrained('job_titles');
        });

        // Migrate existing data: try to match existing job_title strings to job_titles
        $profiles = DB::table('employee_profiles')->whereNotNull('job_title')->get();

        foreach ($profiles as $profile) {
            $jobTitle = JobTitle::whereRaw('LOWER(name) = ?', [strtolower(trim($profile->job_title))])->first();

            if ($jobTitle) {
                DB::table('employee_profiles')
                    ->where('id', $profile->id)
                    ->update(['job_title_id' => $jobTitle->id]);
            }
        }

        // Drop the old job_title column
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropColumn('job_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-add the job_title string column
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('job_grade');
        });

        // Migrate data back from job_title_id to job_title string
        $profiles = DB::table('employee_profiles')->whereNotNull('job_title_id')->get();

        foreach ($profiles as $profile) {
            $jobTitle = JobTitle::find($profile->job_title_id);

            if ($jobTitle) {
                DB::table('employee_profiles')
                    ->where('id', $profile->id)
                    ->update(['job_title' => $jobTitle->name]);
            }
        }

        // Drop the foreign key and column
        Schema::table('employee_profiles', function (Blueprint $table) {
            $table->dropForeign(['job_title_id']);
            $table->dropColumn('job_title_id');
        });
    }
};
