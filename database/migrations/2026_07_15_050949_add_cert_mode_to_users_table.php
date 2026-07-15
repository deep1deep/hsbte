<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // trainer ki choice: manual = khud upload karega | auto = system banayega
            $table->enum('cert_mode', ['manual', 'auto'])->default('auto')->after('qualification');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('cert_mode');
        });
    }
};