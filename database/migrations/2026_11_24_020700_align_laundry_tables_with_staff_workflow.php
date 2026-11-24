<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->renameColumn('deskripsi', 'estimasi');
        });

        DB::table('layanans')->where('estimasi', 'like', '%3-4 hari%')->update(['estimasi' => '3-4 hari']);
        DB::table('layanans')->where('estimasi', 'like', '%1-2 hari%')->update(['estimasi' => '1-2 hari']);
        DB::table('layanans')->where('estimasi', 'like', '%5 jam%')->update(['estimasi' => '5 jam']);
        DB::table('layanans')->whereNull('estimasi')->update(['estimasi' => 'Belum ditentukan']);
        DB::table('layanans')->where('nama_layanan', 'Ekspress')->update(['nama_layanan' => 'Ekspres']);

        Schema::table('transaksis', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->decimal('berat', 8, 2)->nullable();
        });

        DB::table('transaksis')->whereNull('berat')->update(['berat' => DB::raw('jumlah')]);

        Schema::table('transaksis', function (Blueprint $table) {
            $table->renameColumn('tanggal_transaksi', 'tanggal_masuk');
            $table->dropColumn(['jumlah', 'alamat', 'catatan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->renameColumn('tanggal_masuk', 'tanggal_transaksi');
            $table->renameColumn('berat', 'jumlah');
            $table->text('alamat')->nullable();
            $table->text('catatan')->nullable();
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('layanans', function (Blueprint $table) {
            $table->renameColumn('estimasi', 'deskripsi');
        });
    }
};
