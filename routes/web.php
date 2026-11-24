<?php

use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $hariIni = now()->startOfDay();
    $namaHari = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    $pendapatanHarian = Transaksi::query()
        ->whereDate('tanggal_masuk', '>=', $hariIni->copy()->subDays(6))
        ->get(['tanggal_masuk', 'total_harga'])
        ->groupBy(fn (Transaksi $transaksi): string => $transaksi->tanggal_masuk->toDateString())
        ->map(fn ($transaksis): float => (float) $transaksis->sum('total_harga'));

    $pendapatanHarian = collect(range(6, 0))
        ->map(function (int $hariYangLalu) use ($hariIni, $namaHari, $pendapatanHarian): array {
            $tanggal = $hariIni->copy()->subDays($hariYangLalu);
            $kunciTanggal = $tanggal->toDateString();

            return [
                'label' => $namaHari[$tanggal->dayOfWeek],
                'tanggal' => $tanggal->format('d M'),
                'pendapatan' => $pendapatanHarian->get($kunciTanggal, 0),
            ];
        });

    $pendapatanMaksimum = max(1, (float) $pendapatanHarian->max('pendapatan'));
    $grafikPendapatan = $pendapatanHarian
        ->values()
        ->map(function (array $hari, int $indeks) use ($pendapatanMaksimum): array {
            return [
                ...$hari,
                'x' => round(48 + ($indeks * 111.5), 1),
                'y' => round(184 - (($hari['pendapatan'] / $pendapatanMaksimum) * 145), 1),
            ];
        });

    $titikGrafik = $grafikPendapatan
        ->map(fn (array $titik, int $indeks): string => ($indeks === 0 ? 'M ' : 'L ').$titik['x'].' '.$titik['y'])
        ->implode(' ');
    $titikPertama = $grafikPendapatan->first();
    $titikTerakhir = $grafikPendapatan->last();

    return view('dashboard', [
        'totalTransaksi' => Transaksi::count(),
        'transaksiProses' => Transaksi::where('status', 'proses')->count(),
        'transaksiSelesai' => Transaksi::where('status', 'selesai')->count(),
        'totalPendapatan' => Transaksi::sum('total_harga'),
        'grafikPendapatan' => $grafikPendapatan,
        'jalurGrafik' => $titikGrafik,
        'areaGrafik' => $titikGrafik.' L '.$titikTerakhir['x'].' 194 L '.$titikPertama['x'].' 194 Z',
        'transaksiTerbaru' => Transaksi::with('layanan')
            ->latest('tanggal_masuk')
            ->latest('id')
            ->limit(6)
            ->get(),
    ]);
})->middleware(['auth', 'role:admin,kasir'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin,kasir'])->group(function () {
    Route::resource('layanan', LayananController::class)
        ->except(['show', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('layanan', LayananController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->middleware('role:admin');

    Route::resource('transaksi', TransaksiController::class)->except(['show']);
    Route::patch('/transaksi/{transaksi}/status', [TransaksiController::class, 'updateStatus'])->name('transaksi.status');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

require __DIR__.'/auth.php';
