<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ek enrollment = ek certificate. Pehle sirf application code me check tha
     * (`if ($enrollment->certificate)`) — do request ek saath aayein to dono pass
     * ho jaati thheen aur student ke do certificate ban jaate the.
     */
    public function up(): void
    {
        // Agar purana duplicate data hai to pehle saaf karo — warna unique index
        // banega hi nahi aur migrate fail hone se poora deploy ruk jaayega.
        $duplicates = DB::table('certificates')
            ->select('enrollment_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('enrollment_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('certificates')
                ->where('enrollment_id', $duplicate->enrollment_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();   // sabse purana (MIN id) rakha jaata hai
        }

        Schema::table('certificates', function (Blueprint $table) {
            $table->unique('enrollment_id');
        });
    }

    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropUnique(['enrollment_id']);
        });
    }
};
