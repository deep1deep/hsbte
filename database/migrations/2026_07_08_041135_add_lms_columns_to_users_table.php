<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['student','trainer','admin'])->default('student')->after('password');
            $table->string('phone')->nullable()->after('role');
            $table->boolean('is_active')->default(true)->after('phone');
            // nullable profile fields — students fill enrollment stuff, trainers fill designation/qualification
            $table->string('enrollment_no')->unique()->nullable()->after('is_active');
            $table->string('institute')->nullable()->after('enrollment_no');
            $table->string('semester')->nullable()->after('institute');
            $table->foreignId('department_id')->nullable()->after('semester')
                  ->constrained()->nullOnDelete();
            $table->string('designation')->nullable()->after('department_id');
            $table->string('qualification')->nullable()->after('designation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn([
                'role','phone','is_active','enrollment_no','institute',
                'semester','department_id','designation','qualification',
            ]);
        });
    }
};