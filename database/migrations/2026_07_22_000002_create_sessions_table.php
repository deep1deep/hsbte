<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * config/session.php ka default 'database' hai, lekin sessions table kabhi
     * bani hi nahi thi. Site sirf isliye chal rahi hai kyunki env me
     * SESSION_DRIVER=file set hai — wo ek var hat jaaye to har request 500 ho.
     *
     * Table bana dene se wo landmine hat jaata hai. Driver ABHI nahi badla ja
     * raha — driver badalne se sab logged-out ho jaayenge, wo alag decision hai.
     */
    public function up(): void
    {
        if (Schema::hasTable('sessions')) {
            return;
        }

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
