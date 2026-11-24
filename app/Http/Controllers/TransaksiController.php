<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Transaksi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransaksiController extends Controller
{
    public function index(): View
    {
        $transaksis = Transaksi::with(['layanan', 'user'])->latest()->get();

        return view('transaksi.index', compact('transaksis'));
    }

    public function create(): View
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();

        return view('transaksi.create', compact('layanans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'layanan_id' => ['required', 'exists:layanans,id'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'berat' => ['required', 'numeric', 'gt:0', 'max:999999.99'],
            'tanggal_masuk' => ['required', 'date'],
            'status' => ['sometimes', 'required', 'in:proses,selesai'],
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);
        $validated['total_harga'] = $layanan->harga * $validated['berat'];
        $validated['user_id'] = $request->user()->id;
        $validated['status'] ??= 'proses';

        Transaksi::create($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dicatat.');
    }

    public function edit(Transaksi $transaksi): View
    {
        $layanans = Layanan::orderBy('nama_layanan')->get();

        return view('transaksi.edit', compact('transaksi', 'layanans'));
    }

    public function update(Request $request, Transaksi $transaksi): RedirectResponse
    {
        $validated = $request->validate([
            'layanan_id' => ['required', 'exists:layanans,id'],
            'nama_pelanggan' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'berat' => ['required', 'numeric', 'gt:0', 'max:999999.99'],
            'tanggal_masuk' => ['required', 'date'],
            'status' => ['required', 'in:proses,selesai'],
        ]);

        $layanan = Layanan::findOrFail($validated['layanan_id']);
        $validated['total_harga'] = $layanan->harga * $validated['berat'];

        $transaksi->update($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diubah.');
    }

    public function updateStatus(Transaksi $transaksi): RedirectResponse
    {
        $transaksi->update([
            'status' => $transaksi->status === 'proses' ? 'selesai' : 'proses',
        ]);

        return redirect()->route('transaksi.index')->with('success', 'Status transaksi diperbarui.');
    }

    public function destroy(Transaksi $transaksi): RedirectResponse
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
