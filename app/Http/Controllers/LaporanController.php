<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'dari' => ['nullable', 'date'],
            'sampai' => ['nullable', 'date', 'after_or_equal:dari'],
            'layanan_id' => ['nullable', 'integer', 'exists:layanans,id'],
        ]);

        $applyFilters = static function ($query) use ($validated): void {
            if (! empty($validated['dari'])) {
                $query->whereDate('tanggal_masuk', '>=', $validated['dari']);
            }

            if (! empty($validated['sampai'])) {
                $query->whereDate('tanggal_masuk', '<=', $validated['sampai']);
            }

            if (! empty($validated['layanan_id'])) {
                $query->where('layanan_id', $validated['layanan_id']);
            }
        };

        $query = Transaksi::with('layanan');
        $applyFilters($query);
        $transaksis = $query->latest('tanggal_masuk')->latest('id')->get();

        $totalTransaksi = $transaksis->count();
        $totalPendapatan = $transaksis->sum('total_harga');
        $totalProses = $transaksis->where('status', 'proses')->count();
        $totalSelesai = $transaksis->where('status', 'selesai')->count();
        $layanans = Layanan::orderBy('nama_layanan')->get();

        $perLayanan = Layanan::withCount(['transaksis' => $applyFilters])
            ->withSum(['transaksis as pendapatan' => $applyFilters], 'total_harga')
            ->get();

        return view('laporan.index', compact(
            'validated',
            'layanans',
            'transaksis',
            'totalTransaksi',
            'totalPendapatan',
            'totalProses',
            'totalSelesai',
            'perLayanan',
        ));
    }
}
