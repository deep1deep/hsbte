<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            // pending = student ne complete kiya, trainer ne abhi upload nahi kiya
            // issued  = ready, student download kar sakta hai
            $table->enum('status', ['pending', 'issued'])->default('issued')->after('certificate_no');

            // manual = trainer ne PDF upload kiya | auto = dompdf ne banaya
            $table->enum('source', ['auto', 'manual'])->default('auto')->after('status');

            // base64 PDF — DB me, disk pe NAHI (Render disk ephemeral hai)
            $table->longText('file_blob')->nullable()->after('file_path');
            $table->string('file_mime')->nullable()->after('file_blob');

            $table->foreignId('uploaded_by')->nullable()->after('file_mime')
                  ->constrained('users')->nullOnDelete();

            // auto mode ke liye — abhi khali rahega
            $table->json('template_snapshot')->nullable()->after('uploaded_by');

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropForeign(['uploaded_by']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'status', 'source', 'file_blob',
                'file_mime', 'uploaded_by', 'template_snapshot',
            ]);
        });
    }
};