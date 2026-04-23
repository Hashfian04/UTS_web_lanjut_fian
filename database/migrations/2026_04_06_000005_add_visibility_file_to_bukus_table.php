<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->enum('visibility', ['hidden', 'data_only', 'data_synopsis', 'full'])
                  ->default('data_only')
                  ->after('isbn');
            $table->string('file_path', 255)->nullable()->after('visibility');
        });
    }

    public function down(): void
    {
        Schema::table('bukus', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'file_path']);
        });
    }
};
