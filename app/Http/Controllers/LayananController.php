<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(): View
    {
        $layanans = Layanan::withCount('transaksis')->latest()->get();

        return view('layanan.index', compact('layanans'));
    }

    public function create(): View
    {
        return view('layanan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'estimasi' => ['required', 'string', 'max:100'],
        ]);

        Layanan::create($validated);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan): View
    {
        return view('layanan.edit', compact('layanan'));
    }

    public function update(Request $request, Layanan $layanan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'numeric', 'min:0'],
            'estimasi' => ['required', 'string', 'max:100'],
        ]);

        $layanan->update($validated);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diubah.');
    }

    public function destroy(Layanan $layanan): RedirectResponse
    {
        if ($layanan->transaksis()->exists()) {
            return redirect()->route('layanan.index')
                ->with('error', 'Layanan yang sudah digunakan dalam transaksi tidak dapat dihapus.');
        }

        $layanan->delete();

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
