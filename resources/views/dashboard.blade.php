<x-app-layout>
    <div class="dashboard-home mx-auto w-full max-w-[1600px] px-4 py-5 sm:px-6 lg:px-7">
        <section class="dashboard-hero relative isolate overflow-hidden rounded-2xl border p-5 sm:p-7" aria-labelledby="dashboard-welcome">
            <div class="hero-copy relative z-10">
                <span class="hero-kicker">LAUNDRYKAMI · RUANG KERJA STAF</span>
                <p class="hero-greeting">Selamat datang,</p>
                <h1 id="dashboard-welcome">{{ Auth::user()->name }}!</h1>
                <p class="hero-caption">Kelola laundry dengan lebih mudah, cepat, dan efisien.</p>
                <a href="{{ route('transaksi.create') }}" class="hero-action">
                    <span>＋</span> Catat transaksi baru <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="hero-art" aria-hidden="true">
                <span class="hero-bubble bubble-one"></span>
                <span class="hero-bubble bubble-two"></span>
                <span class="hero-bubble bubble-three"></span>
                <span class="hero-spark hero-spark-one">✦</span>
                <span class="hero-spark hero-spark-two">✦</span>
                <div class="hero-mascot">
                    <span class="mascot-antenna"></span>
                    <span class="mascot-head">
                        <span class="mascot-face">
                            <span class="mascot-eye"></span>
                            <span class="mascot-eye"></span>
                            <span class="mascot-smile"></span>
                        </span>
                    </span>
                    <span class="mascot-arm mascot-arm-left"></span>
                    <span class="mascot-arm mascot-arm-right"><span></span></span>
                    <span class="mascot-body">
                        <span class="mascot-badge">L</span>
                        <span class="mascot-body-line"></span>
                    </span>
                </div>
                <div class="hero-washer">
                    <div class="washer-top"><span></span><i></i><i></i><i></i></div>
                    <div class="washer-door"><span class="washer-water"></span><span class="washer-bubble"></span></div>
                </div>
                <span class="hero-wave"></span>
            </div>
        </section>

        <section class="dashboard-metrics grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Ringkasan laundry">
            <article class="metric-card metric-blue rounded-2xl border p-4 sm:p-5">
                <span class="metric-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M3 4h2l2.2 11.2a2 2 0 0 0 2 1.6h8.5a2 2 0 0 0 2-1.6L21 8H6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="10" cy="20" r="1.5" fill="currentColor"/>
                        <circle cx="18" cy="20" r="1.5" fill="currentColor"/>
                    </svg>
                </span>
                <div class="metric-copy">
                    <span>Total Transaksi</span>
                    <strong data-counter="{{ $totalTransaksi }}">{{ $totalTransaksi }}</strong>
                    <small><span aria-hidden="true">↗</span> Semua pesanan tercatat</small>
                </div>
                <svg class="metric-sparkline" viewBox="0 0 128 44" fill="none" aria-hidden="true">
                    <path d="M2 39C22 37 25 28 44 31s23 8 38-7 19-16 29-8 12-9 17-11" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M2 39C22 37 25 28 44 31s23 8 38-7 19-16 29-8 12-9 17-11v42H2V39Z" fill="currentColor" opacity=".09"/>
                </svg>
            </article>

            <article class="metric-card metric-green rounded-2xl border p-4 sm:p-5">
                <span class="metric-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M12 3v18M16 7c0-1.7-1.6-2.8-4-2.8S8 5.4 8 7.2c0 4 8 1.7 8 6.4 0 1.8-1.6 3.2-4 3.2s-4-1.2-4-3" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    </svg>
                </span>
                <div class="metric-copy">
                    <span>Total Pendapatan</span>
                    <strong class="metric-currency" data-counter="{{ (int) $totalPendapatan }}" data-prefix="Rp " data-currency="true">
                        Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                    </strong>
                    <small><span aria-hidden="true">↗</span> Akumulasi seluruh transaksi</small>
                </div>
                <svg class="metric-sparkline" viewBox="0 0 128 44" fill="none" aria-hidden="true">
                    <path d="M2 38C15 36 20 24 33 29s21-12 37-8 19 15 31 5 17-12 25-23" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M2 38C15 36 20 24 33 29s21-12 37-8 19 15 31 5 17-12 25-23v41H2V38Z" fill="currentColor" opacity=".09"/>
                </svg>
            </article>

            <article class="metric-card metric-orange rounded-2xl border p-4 sm:p-5">
                <span class="metric-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8"/>
                        <path d="M12 6.5v5.8l3.6 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
                <div class="metric-copy">
                    <span>Laundry Dalam Proses</span>
                    <strong data-counter="{{ $transaksiProses }}">{{ $transaksiProses }}</strong>
                    <small><span aria-hidden="true">◷</span> Siap untuk diselesaikan</small>
                </div>
                <svg class="metric-sparkline" viewBox="0 0 128 44" fill="none" aria-hidden="true">
                    <path d="M2 35c14 0 18-12 31-9s17 14 31 2 19-21 32-14 17 10 32-8" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M2 35c14 0 18-12 31-9s17 14 31 2 19-21 32-14 17 10 32-8v38H2V35Z" fill="currentColor" opacity=".09"/>
                </svg>
            </article>

            <article class="metric-card metric-purple rounded-2xl border p-4 sm:p-5">
                <span class="metric-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="m5 12.5 4.3 4.2L19 7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9.2" stroke="currentColor" stroke-width="1.8"/>
                    </svg>
                </span>
                <div class="metric-copy">
                    <span>Laundry Selesai</span>
                    <strong data-counter="{{ $transaksiSelesai }}">{{ $transaksiSelesai }}</strong>
                    <small><span aria-hidden="true">✓</span> Pesanan telah tuntas</small>
                </div>
                <svg class="metric-sparkline" viewBox="0 0 128 44" fill="none" aria-hidden="true">
                    <path d="M2 36c13-2 17-13 30-7s18 9 31-5 19-15 30-8 19 7 35-12" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                    <path d="M2 36c13-2 17-13 30-7s18 9 31-5 19-15 30-8 19 7 35-12v40H2V36Z" fill="currentColor" opacity=".09"/>
                </svg>
            </article>
        </section>

        <section class="dashboard-insights grid grid-cols-1 gap-4 xl:grid-cols-5" aria-label="Grafik dan status laundry">
            <article class="insight-card revenue-card rounded-2xl border p-4 sm:p-5 xl:col-span-3">
                <div class="insight-heading">
                    <div>
                        <span class="insight-eyebrow">PERFORMA USAHA</span>
                        <h2>Grafik Pendapatan</h2>
                        <p>Ringkasan pemasukan selama tujuh hari terakhir.</p>
                    </div>
                    <span class="chart-period"><span></span> 7 hari terakhir</span>
                </div>

                <div class="revenue-chart">
                    <div class="chart-y-labels" aria-hidden="true">
                        <span>Rp {{ number_format(max(0, (int) $grafikPendapatan->max('pendapatan')), 0, ',', '.') }}</span>
                        <span>Rp {{ number_format((int) ($grafikPendapatan->max('pendapatan') / 2), 0, ',', '.') }}</span>
                        <span>Rp 0</span>
                    </div>
                    <svg viewBox="0 0 740 220" role="img" aria-label="Grafik pendapatan tujuh hari terakhir">
                        <defs>
                            <linearGradient id="revenue-fill" x1="0" x2="0" y1="0" y2="1">
                                <stop offset="0%" stop-color="#1595ff" stop-opacity=".34"/>
                                <stop offset="100%" stop-color="#1595ff" stop-opacity="0"/>
                            </linearGradient>
                            <linearGradient id="revenue-line" x1="0" x2="1" y1="0" y2="0">
                                <stop offset="0%" stop-color="#2277ff"/>
                                <stop offset="100%" stop-color="#13c9d8"/>
                            </linearGradient>
                        </defs>
                        <path class="chart-grid-line" d="M48 38H717M48 110H717M48 184H717"/>
                        <path class="chart-grid-line chart-grid-vertical" d="M48 30V194M159.5 30V194M271 30V194M382.5 30V194M494 30V194M605.5 30V194M717 30V194"/>
                        <path d="{{ $areaGrafik }}" fill="url(#revenue-fill)"/>
                        <path class="chart-revenue-line" d="{{ $jalurGrafik }}" fill="none" stroke="url(#revenue-line)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                        @foreach ($grafikPendapatan as $titik)
                            <g class="chart-point">
                                <circle cx="{{ $titik['x'] }}" cy="{{ $titik['y'] }}" r="8" fill="#fff" stroke="#238eff" stroke-width="3"/>
                                <title>{{ $titik['tanggal'] }}: Rp {{ number_format($titik['pendapatan'], 0, ',', '.') }}</title>
                            </g>
                        @endforeach
                    </svg>
                </div>
                <div class="chart-x-labels" aria-hidden="true">
                    @foreach ($grafikPendapatan as $titik)
                        <span>{{ $titik['label'] }}<small>{{ $titik['tanggal'] }}</small></span>
                    @endforeach
                </div>
            </article>

            @php
                $persentaseSelesai = $totalTransaksi > 0
                    ? (int) round(($transaksiSelesai / $totalTransaksi) * 100)
                    : 0;
            @endphp
            <article class="insight-card status-card rounded-2xl border p-4 sm:p-5 xl:col-span-2">
                <div class="insight-heading">
                    <div>
                        <span class="insight-eyebrow">RINGKASAN PEKERJAAN</span>
                        <h2>Status Laundry</h2>
                        <p>Perbandingan progres transaksi saat ini.</p>
                    </div>
                    <span class="status-pulse" aria-label="Data terbaru"></span>
                </div>

                <div class="status-content">
                    <div class="status-donut" style="--done-share: {{ $persentaseSelesai }}%;" role="img" aria-label="{{ $persentaseSelesai }} persen transaksi selesai">
                        <div>
                            <strong>{{ $persentaseSelesai }}%</strong>
                            <span>Selesai</span>
                        </div>
                    </div>
                    <div class="status-legend">
                        <div>
                            <span class="legend-dot legend-complete"></span>
                            <span>Selesai</span>
                            <strong>{{ $transaksiSelesai }}</strong>
                        </div>
                        <div>
                            <span class="legend-dot legend-process"></span>
                            <span>Proses</span>
                            <strong>{{ $transaksiProses }}</strong>
                        </div>
                        <a href="{{ route('transaksi.index') }}" class="status-link">Lihat transaksi <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
                <div class="status-note">
                    <span class="status-note-icon" aria-hidden="true">✦</span>
                    <span>Terus pantau proses laundry agar pesanan selesai tepat waktu.</span>
                </div>
            </article>
        </section>

        <section class="dashboard-bottom grid grid-cols-1 gap-4 xl:grid-cols-4">
            <article class="insight-card recent-panel rounded-2xl border xl:col-span-3">
                <div class="insight-heading recent-heading">
                    <div>
                        <span class="insight-eyebrow">AKTIVITAS TERBARU</span>
                        <h2>Transaksi Terbaru</h2>
                        <p>Pesanan laundry yang terakhir dicatat.</p>
                    </div>
                    <div class="recent-actions">
                        <label class="table-search dashboard-table-search" for="dashboard-search">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <circle cx="10.8" cy="10.8" r="6.8" stroke="currentColor" stroke-width="1.7"/>
                                <path d="m16 16 4 4" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/>
                            </svg>
                            <input id="dashboard-search" type="search" data-table-search data-table-target="#dashboard-table"
                                   placeholder="Cari transaksi..." autocomplete="off">
                        </label>
                        <a href="{{ route('transaksi.index') }}" class="dashboard-view-all">Lihat semua <span aria-hidden="true">&rarr;</span></a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="dashboard-table" class="table app-table dashboard-table align-middle mb-0" data-searchable-table>
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>ID Transaksi</th>
                                <th>Nama Pelanggan</th>
                                <th>No. HP</th>
                                <th>Layanan</th>
                                <th>Berat</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transaksiTerbaru as $transaksi)
                                <tr data-search-row>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="transaction-code">TRX{{ $transaksi->tanggal_masuk->format('ymd') }}{{ str_pad((string) $transaksi->id, 4, '0', STR_PAD_LEFT) }}</td>
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
                                    <td>{{ $transaksi->tanggal_masuk->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">
                                        <div class="empty-state">
                                            <strong>Belum ada transaksi</strong>
                                            <span>Pesanan terbaru akan muncul di sini setelah dicatat.</span>
                                            <a href="{{ route('transaksi.create') }}" class="btn btn-primary app-button">Catat Transaksi</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>

            <aside class="dashboard-promo rounded-2xl border p-5">
                <span class="promo-glow" aria-hidden="true"></span>
                <span class="promo-badge"><span aria-hidden="true">✦</span> LAYANAN ANDALAN</span>
                <div class="promo-icon" aria-hidden="true">
                    <svg viewBox="0 0 96 96" fill="none">
                        <path d="M31 19 20 26l-9 18 15 8 7-11v37h31V41l7 11 15-8-9-18-11-7c-4 8-10 12-18 12S35 27 31 19Z" fill="url(#shirt-fill)" stroke="rgba(255,255,255,.82)" stroke-width="2.5" stroke-linejoin="round"/>
                        <path d="M38 31c2 5 6 8 11 8s9-3 11-8" stroke="rgba(255,255,255,.78)" stroke-width="2"/>
                        <path d="M43 52h12M43 60h12" stroke="rgba(255,255,255,.68)" stroke-width="2" stroke-linecap="round"/>
                        <defs>
                            <linearGradient id="shirt-fill" x1="16" y1="18" x2="81" y2="78" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#c5f8ff"/>
                                <stop offset="1" stop-color="#388cff"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <h2>Laundry kilat,<br>hasil maksimal!</h2>
                <p>Pilih layanan yang sesuai kebutuhan dan berikan pengalaman terbaik untuk pelanggan.</p>
                <a href="{{ route('layanan.index') }}" class="promo-action">Lihat layanan <span aria-hidden="true">&rarr;</span></a>
                <div class="promo-footer"><span></span> Bersih, wangi, siap dipakai.</div>
            </aside>
        </section>
    </div>
</x-app-layout>
