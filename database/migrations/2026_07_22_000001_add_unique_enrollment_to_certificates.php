<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * One enrollment = one certificate. Previously the check lived only in the
     * application code (`if ($enrollment->certificate)`) — if two requests arrived
     * at once both passed and the student ended up with two certificates.
     */
    public function up(): void
    {
        // Clean up any old duplicate data first — otherwise the unique index
        // won't build and the failed migration will halt the whole deploy.
        $duplicates = DB::table('certificates')
            ->select('enrollment_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('enrollment_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('certificates')
                ->where('enrollment_id', $duplicate->enrollment_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();   // the oldest one (MIN id) is kept
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
