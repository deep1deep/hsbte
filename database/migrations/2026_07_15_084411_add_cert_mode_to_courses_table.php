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
        Schema::table('courses', function (Blueprint $table) {
            // manual = trainer har student ka file upload karega
            // auto   = HTML template se apne aap banega
            $table->enum('cert_mode', ['manual', 'auto'])->default('auto')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('cert_mode');
        });
    }
};
