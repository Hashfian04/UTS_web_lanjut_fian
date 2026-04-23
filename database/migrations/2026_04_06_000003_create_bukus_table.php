<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->string('penulis', 255);
            $table->string('penerbit', 255);
            $table->smallInteger('tahun_terbit');
            $table->string('edisi', 50)->nullable();
            $table->unsignedInteger('jumlah_halaman')->nullable();
            $table->string('bahasa', 50)->default('Indonesia');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('restrict');
            $table->foreignId('rak_id')->nullable()->constrained('raks')->onDelete('set null');
            $table->text('deskripsi')->nullable();
            $table->string('cover', 255)->nullable();
            $table->unsignedSmallInteger('stok')->default(1);
            $table->string('isbn', 20)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
