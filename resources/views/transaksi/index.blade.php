@extends('layouts.app')

@section('title', 'Transaksi Laundry')

@section('content')
    <div class="app-content">
        <div class="container-fluid app-container">
            @if (session('success'))
                <div class="alert app-alert app-alert-success">{{ session('success') }}</div>
            @endif

            <section class="page-toolbar">
                <div>
                    <span class="page-eyebrow">OPERASIONAL</span>
                    <h2 class="section-title">Transaksi Laundry</h2>
                    <p class="page-subtitle">Catat pesanan dan pantau status pengerjaan laundry.</p>
                </div>
                <a href="{{ route('transaksi.create') }}" class="btn btn-primary app-button">
                    <span aria-hidden="true">+</span> Transaksi Baru
                </a>
            </section>

            <section class="panel-card">
                <div class="panel-heading">
                    <div>
                        <h3>Semua transaksi</h3>
                        <p data-search-status>{{ $transaksis->count() }} transaksi tercatat</p>
                    </div>
                    <div class="panel-heading-actions">
                        <label class="table-search" for="transaction-search">
                            <span class="sr-only">Cari transaksi</span>
                            <input id="transaction-search" type="search" data-table-search data-table-target="#transaction-table"
                                   placeholder="Cari pelanggan..." autocomplete="off">
                        </label>
                        <a href="{{ route('laporan.index') }}" class="text-link">Buka rekap <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="transaction-table" class="table app-table align-middle mb-0" data-searchable-table>
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Nomor HP</th>
                                <th>Layanan</th>
                                <th>Berat</th>
                                <th>Total Harga</th>
                                <th>Tanggal Masuk</th>
                                <th>Kasir</th>
                                <th>Status</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksis as $transaksi)
                                <tr data-search-row>
                                    <td class="table-primary-text">{{ $transaksi->nama_pelanggan }}</td>
                                    <td>{{ $transaksi->no_hp }}</td>
                                    <td>{{ $transaksi->layanan->nama_layanan }}</td>
                                    <td>{{ number_format($transaksi->berat, 2, ',', '.') }} kg</td>
                                    <td class="table-primary-text">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                    <td>{{ $transaksi->tanggal_masuk->format('d/m/Y') }}</td>
                                    <td>{{ $transaksi->user?->name ?? '-' }}</td>
                                    <td>
                                        <span class="laundry-status {{ $transaksi->status === 'selesai' ? 'status-complete' : 'status-pending' }}">
                                            <span aria-hidden="true"></span>{{ ucfirst($transaksi->status) }}
                                        </span>
                                    </td>
                                    <td class="text-end text-nowrap">
                                        <a href="{{ route('transaksi.edit', $transaksi) }}" class="table-action-link">Edit</a>
                                        <form action="{{ route('transaksi.status', $transaksi) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="table-action-link status-action">
                                                {{ $transaksi->status === 'proses' ? 'Selesai' : 'Proses' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('transaksi.destroy', $transaksi) }}" method="POST" class="d-inline"
                                              onsubmit="return confirm('Hapus transaksi ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="table-action-link danger-action">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <strong>Belum ada transaksi</strong>
                                            <span>Mulai dengan mencatat pesanan pelanggan.</span>
                                            <a href="{{ route('transaksi.create') }}" class="btn btn-primary app-button">Catat Transaksi</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
