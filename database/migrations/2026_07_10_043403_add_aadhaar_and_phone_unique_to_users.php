<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Guard: local pe column already hai (skip), live pe nahi hai (create)
            if (! Schema::hasColumn('users', 'aadhaar_hash')) {
                $table->string('aadhaar_hash')->nullable()->unique();
            }
            $table->unique('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_aadhaar_hash_unique');
            $table->dropColumn('aadhaar_hash');
            $table->dropUnique('users_phone_unique');
        });
    }
};