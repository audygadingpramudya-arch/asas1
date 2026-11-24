@extends('layouts.app')

@section('title', 'Rekap Transaksi')

@section('content')
    <div class="app-content">
        <div class="container-fluid app-container">
            @if ($errors->any())
                <div class="alert app-alert app-alert-error" role="alert">
                    <strong>Filter belum dapat diterapkan.</strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="page-toolbar report-toolbar">
                <div>
                    <span class="page-eyebrow">ANALISIS LAUNDRY</span>
                    <h2 class="section-title">Rekap Transaksi</h2>
                    <p class="page-subtitle">Pantau transaksi dan pendapatan berdasarkan periode.</p>
                </div>
                <button type="button" class="btn btn-outline-primary app-button" data-print-report>
                    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M7 8V4.5h10V8M7 17H5.5A1.5 1.5 0 0 1 4 15.5v-6A1.5 1.5 0 0 1 5.5 8h13A1.5 1.5 0 0 1 20 9.5v6a1.5 1.5 0 0 1-1.5 1.5H17M7 14h10v6H7v-6Z" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M16.5 11h.01" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                    Cetak Laporan
                </button>
            </section>

            <div class="report-print-header">
                <span class="print-brand">Laundry<span>Kami</span></span>
                <h1>Rekap Transaksi Laundry</h1>
                <p>Periode:
                    <time data-report-start>{{ $validated['dari'] ?? 'Semua tanggal' }}</time>
                    —
                    <time data-report-end>{{ $validated['sampai'] ?? 'Semua tanggal' }}</time>
                </p>
                <p>Dicetak pada <time data-print-date></time></p>
            </div>

            <form method="GET" action="{{ route('laporan.index') }}" class="panel-card report-filters">
                <div class="form-field">
                    <label class="form-label" for="dari">Dari tanggal</label>
                    <input class="form-control" id="dari" name="dari" type="date" value="{{ $validated['dari'] ?? '' }}">
                </div>
                <div class="form-field">
                    <label class="form-label" for="sampai">Sampai tanggal</label>
                    <input class="form-control" id="sampai" name="sampai" type="date" value="{{ $validated['sampai'] ?? '' }}">
                </div>
                <div class="form-field">
                    <label class="form-label" for="filter_layanan">Jenis layanan</label>
                    <select class="form-select" id="filter_layanan" name="layanan_id">
                        <option value="">Semua layanan</option>
                        @foreach ($layanans as $layanan)
                            <option value="{{ $layanan->id }}" @selected(($validated['layanan_id'] ?? '') == $layanan->id)>
                                {{ $layanan->nama_layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary app-button">Terapkan Filter</button>
                    <a href="{{ route('laporan.index') }}" class="btn btn-light app-button">Reset</a>
                </div>
            </form>

            <section class="report-summary">
                <article class="summary-card summary-blue">
                    <span class="summary-label">Total Transaksi</span>
                    <strong data-counter="{{ $totalTransaksi }}">{{ $totalTransaksi }}</strong>
                    <span class="summary-hint">Pada periode terpilih</span>
                </article>
                <article class="summary-card summary-violet">
                    <span class="summary-label">Total Pendapatan</span>
                    <strong data-counter="{{ (int) $totalPendapatan }}" data-prefix="Rp " data-currency="true">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </strong>
                    <span class="summary-hint">Dari seluruh transaksi</span>
                </article>
                <article class="summary-card summary-amber">
                    <span class="summary-label">Sedang Diproses</span>
                    <strong data-counter="{{ $totalProses }}">{{ $totalProses }}</strong>
                    <span class="summary-hint">Laundry dalam pengerjaan</span>
                </article>
                <article class="summary-card summary-green">
                    <span class="summary-label">Selesai</span>
                    <strong data-counter="{{ $totalSelesai }}">{{ $totalSelesai }}</strong>
                    <span class="summary-hint">Laundry telah selesai</span>
                </article>
            </section>

            <section class="panel-card report-table-panel">
                <div class="panel-heading">
                    <div>
                        <h3>Detail transaksi</h3>
                        <p data-search-status>{{ $transaksis->count() }} hasil sesuai filter</p>
                    </div>
                    <label class="table-search" for="report-search">
                        <span class="sr-only">Cari dalam rekap transaksi</span>
                        <input id="report-search" type="search" data-table-search data-table-target="#report-table"
                               placeholder="Cari pelanggan atau layanan..." autocomplete="off">
                    </label>
                </div>
                <div class="table-responsive">
                    <table class="table app-table align-middle mb-0" id="report-table" data-searchable-table>
                        <thead>
                            <tr>
                                <th>Tanggal Masuk</th>
                                <th>Nama Pelanggan</th>
                                <th>Nomor HP</th>
                                <th>Jenis Layanan</th>
                                <th>Berat</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksis as $transaksi)
                                <tr data-search-row>
                                    <td>{{ $transaksi->tanggal_masuk->format('d/m/Y') }}</td>
                                    <td class="table-primary-text">{{ $transaksi->nama_pelanggan }}</td>
                                    <td>{{ $transaksi->no_hp }}</td>
                                    <td>{{ $transaksi->layanan->nama_layanan }}</td>
                                    <td>{{ number_format($transaksi->berat, 2, ',', '.') }} kg</td>
                                    <td class="table-primary-text">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="laundry-status {{ $transaksi->status === 'selesai' ? 'status-complete' : 'status-pending' }}">
                                            <span aria-hidden="true"></span>{{ ucfirst($transaksi->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
                                        <div class="empty-state">
                                            <strong>Tidak ada transaksi pada filter ini</strong>
                                            <span>Coba ubah tanggal atau pilih layanan lain.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="panel-card service-summary-panel">
                <div class="panel-heading">
                    <div>
                        <h3>Ringkasan per layanan</h3>
                        <p>Jumlah transaksi dan pendapatan sesuai filter yang dipilih.</p>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table app-table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Nama Layanan</th>
                                <th>Tarif / kg</th>
                                <th>Jumlah Transaksi</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($perLayanan as $layanan)
                                <tr>
                                    <td class="table-primary-text">{{ $layanan->nama_layanan }}</td>
                                    <td>Rp {{ number_format($layanan->harga, 0, ',', '.') }}</td>
                                    <td>{{ $layanan->transaksis_count }}</td>
                                    <td class="table-primary-text">Rp {{ number_format($layanan->pendapatan ?? 0, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
@endsection
