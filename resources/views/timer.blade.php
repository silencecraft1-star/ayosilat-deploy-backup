<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timer Arena - AyoSilat</title>
    <link rel="stylesheet" href="{{ asset('assets/plugins/bootstrap-5.3.7/css/bootstrap.min.css') }}">
    <style>
        body {
            background-color: #f4f6f9;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #212529;
            min-height: 100vh;
        }

        .timer-card {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1.25rem rgba(0, 0, 0, 0.06);
            width: 100%;
            max-width: 820px;
        }

        .timer-display {
            font-family: 'Courier New', Courier, monospace, sans-serif;
            font-weight: 700;
            letter-spacing: 2px;
            font-size: clamp(3.2rem, 9vw, 5.5rem);
            line-height: 1.1;
            padding: 1.25rem 1rem;
            border-radius: 0.75rem;
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            color: #0d6efd;
        }

        .timer-display.state-start {
            color: #198754;
            background-color: #f0fdf4;
            border-color: #bbf7d0;
        }

        .timer-display.state-pause {
            color: #fd7e14;
            background-color: #fffbeb;
            border-color: #fef08a;
        }

        .timer-display.state-stop {
            color: #dc3545;
            background-color: #fef2f2;
            border-color: #fecaca;
        }

        .btn-control {
            min-height: 60px;
            font-weight: 600;
            border-radius: 0.6rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-control:active {
            transform: scale(0.97);
        }

        .status-badge {
            font-size: 0.85rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            padding: 0.4rem 0.85rem;
            border-radius: 50rem;
        }

        /* Mode Fullscreen Helper */
        :fullscreen body, :-webkit-full-screen body {
            padding: 1rem !important;
        }
    </style>
</head>

<body class="d-flex flex-column justify-content-center align-items-center p-3">

    <!-- Tombol Fullscreen Kiri Atas & Kanan Atas -->
    <button type="button" onclick="toggleFullScreen()" class="btn btn-sm btn-outline-secondary bg-white shadow-sm position-fixed" style="top: 12px; left: 12px; z-index: 1050;" title="Full Screen">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
        </svg>
        Full Screen
    </button>
    <button type="button" onclick="toggleFullScreen()" class="btn btn-sm btn-outline-secondary bg-white shadow-sm position-fixed" style="top: 12px; right: 12px; z-index: 1050;" title="Full Screen">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-1">
            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/>
        </svg>
        Full Screen
    </button>

    @include('addon.connection-indicator')

    @php
        use App\Setting;
        use App\PersertaModel;
        use App\KontigenModel;
        use App\jadwal_group;
        use App\arena;
        use App\kelas;
        use App\category;

        $arenaModel = arena::where('id', $arena)->first();
        $arenaName = $arenaModel ? $arenaModel->name : 'Arena ' . $arena;
        $arenaStatus = $arenaModel ? $arenaModel->status : 'Tanding';

        // Check whether this arena operates in Seni mode or Tanding mode
        $isSeni = in_array($arenaStatus, ['Tunggal', 'Solo_Kreatif', 'Ganda_Kreatif', 'Group']);

        $btnStatus = Setting::where('arena', $arena)->whereNotNull('judul')->first() ?? Setting::where('arena', $arena)->first();
        $currentBabak = $btnStatus->babak ?? '1';
        $timerStatus = $btnStatus->status ?? '';

        $jadwalData = ($btnStatus && $btnStatus->jadwal) ? jadwal_group::where('id', $btnStatus->jadwal)->first() : null;
        $partaiNumber = $btnStatus->partai ?? ($jadwalData->partai ?? '-');

        // Participants lookup
        $pesertaBiru = ($btnStatus && $btnStatus->biru) ? PersertaModel::where('id', $btnStatus->biru)->first() : null;
        $pesertaMerah = ($btnStatus && $btnStatus->merah) ? PersertaModel::where('id', $btnStatus->merah)->first() : null;

        $kontigenBiru = ($pesertaBiru && $pesertaBiru->id_kontigen) ? KontigenModel::where('id', $pesertaBiru->id_kontigen)->value('kontigen') : '-';
        $kontigenMerah = ($pesertaMerah && $pesertaMerah->id_kontigen) ? KontigenModel::where('id', $pesertaMerah->id_kontigen)->value('kontigen') : '-';

        // Match Info Labels (Dewan/Juri Standard)
        $kelasId = $pesertaBiru->kelas ?? ($jadwalData->kelas ?? null);
        $categoryId = $pesertaBiru->category ?? ($jadwalData->kategori ?? null);
        $kelasObj = $kelasId ? kelas::where('id', $kelasId)->first() : null;
        $categoryObj = $categoryId ? category::where('id', $categoryId)->first() : null;

        $infoKeterangan = $jadwalData->keterangan ?? ($isSeni ? 'Pemasalan' : 'Penyisihan');
        $infoKelas = $kelasObj ? $kelasObj->name : '-';
        $infoKategori = $categoryObj ? $categoryObj->name : '-';
        $infoGender = $pesertaBiru->gender ?? ($pesertaMerah->gender ?? '-');

        $initialTimeFormatted = $isSeni ? '00:00:00' : '00:00';
    @endphp

    <div class="timer-card p-4 p-md-5 my-auto">
        <!-- Header Info Pertandingan -->
        <div class="text-center mb-3">
            <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                <span class="fs-4 fw-bold text-dark">{{ $arenaName }}</span>
                <span class="badge bg-primary rounded-pill px-3 py-1 fs-6">{{ $arenaStatus }}</span>
            </div>

            <!-- Pill Info Baris Sejajar (Standar Dewan & Juri) -->
            <div class="d-flex flex-row flex-wrap align-items-center justify-content-center gap-2 text-center text-uppercase mb-3">
                <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold" id="info-partai">Partai {{ $partaiNumber }}</span>
                <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold" id="info-keterangan">{{ ucfirst($infoKeterangan) }}</span>
                <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold" id="info-kelas">{{ $infoKelas }}</span>
                <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold" id="info-kategori">{{ $infoKategori }}</span>
                <span class="badge bg-secondary rounded-pill px-3 py-1 fs-6 fw-bold" id="info-gender">{{ ucfirst($infoGender) }}</span>
            </div>

            <!-- Detail Atlet / Kontingen -->
            @if (!$isSeni && ($pesertaBiru || $pesertaMerah))
                <div class="row align-items-center justify-content-between g-2 mb-3 px-2">
                    <div class="col-5 text-start">
                        <div class="small text-muted fw-bold text-uppercase" id="kontigen-biru">{{ $kontigenBiru }}</div>
                        <div class="fw-bold text-primary text-uppercase text-truncate" id="nama-biru">{{ $pesertaBiru->name ?? '-' }}</div>
                    </div>
                    <div class="col-2 text-center">
                        <span class="badge bg-light text-secondary border fw-bold px-2 py-1">VS</span>
                    </div>
                    <div class="col-5 text-end">
                        <div class="small text-muted fw-bold text-uppercase" id="kontigen-merah">{{ $kontigenMerah }}</div>
                        <div class="fw-bold text-danger text-uppercase text-truncate" id="nama-merah">{{ $pesertaMerah->name ?? '-' }}</div>
                    </div>
                </div>
            @elseif ($isSeni && $pesertaBiru)
                <div class="text-center mb-3">
                    <div class="small text-muted fw-bold text-uppercase" id="kontigen-seni">{{ $kontigenBiru }}</div>
                    <div class="fw-bold text-primary text-uppercase fs-5" id="nama-seni">{{ $pesertaBiru->name }}</div>
                </div>
            @endif
        </div>

        <!-- Timer Display Section -->
        <div class="text-center mb-4">
            <div id="timer1" class="timer-display mb-2 state-{{ $timerStatus ?: 'stop' }}">
                {{ $initialTimeFormatted }}
            </div>
            <div class="d-flex align-items-center justify-content-center gap-2">
                <span id="timer-status-badge" class="status-badge badge bg-secondary text-uppercase">
                    {{ $timerStatus ?: 'STOPPED' }}
                </span>
                <span id="sync-indicator" class="badge bg-success-subtle text-success border border-success-subtle d-inline-flex align-items-center gap-1">
                    <span class="spinner-grow spinner-grow-sm text-success" role="status" style="width: 0.5rem; height: 0.5rem;"></span>
                    Socket Terhubung
                </span>
            </div>
        </div>

        <!-- Tombol Aksi Kontrol Timer Utama -->
        <div class="row g-2 mb-4 justify-content-center">
            <div class="col-6 col-md-3">
                <button type="button" id="start" class="btn btn-success btn-control fs-5 w-100 shadow-sm"
                    {{ in_array($timerStatus, ['start', 'pause']) ? 'disabled' : '' }}>
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                    </svg>
                    Start
                </button>
            </div>
            <div class="col-6 col-md-3" id="pauseResumeCol">
                @if ($timerStatus === 'pause')
                    <button type="button" id="resume" class="btn btn-warning text-dark btn-control fs-5 w-100 shadow-sm">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                        </svg>
                        Resume
                    </button>
                @else
                    <button type="button" id="pause" class="btn btn-primary btn-control fs-5 w-100 shadow-sm"
                        {{ $timerStatus === 'start' ? '' : 'disabled' }}>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"/>
                        </svg>
                        Pause
                    </button>
                @endif
            </div>
            <div class="col-6 col-md-3">
                <button type="button" id="stop" class="btn btn-danger btn-control fs-5 w-100 shadow-sm">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                    </svg>
                    Stop
                </button>
            </div>
            <div class="col-6 col-md-3">
                <button type="button" data-bs-toggle="modal" data-bs-target="#resetModal" class="btn btn-outline-danger btn-control fs-5 w-100 shadow-sm">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2v1z"/>
                        <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466z"/>
                    </svg>
                    Reset
                </button>
            </div>
        </div>

        <!-- Pemilih Babak (Khusus Tanding) -->
        @if (!$isSeni)
            <div class="mb-3">
                <label for="input-continent" class="form-label fw-bold text-secondary small text-uppercase">Pilihan Babak Pertandingan</label>
                <div class="input-group">
                    <span class="input-group-text bg-light text-secondary fw-bold">Babak</span>
                    <select class="form-select form-select-lg border-secondary-subtle" id="input-continent">
                        <option value="1" {{ $currentBabak == '1' ? 'selected' : '' }}>Babak 1</option>
                        <option value="2" {{ $currentBabak == '2' ? 'selected' : '' }}>Babak 2</option>
                        <option value="3" {{ $currentBabak == '3' ? 'selected' : '' }}>Babak 3</option>
                    </select>
                </div>
            </div>
        @endif

        <!-- Manual Custom Time Preset (Opsional) -->
        <div class="accordion accordion-flush mb-2" id="accordionTimerPreset">
            <div class="accordion-item border rounded-3 overflow-hidden">
                <h2 class="accordion-header" id="headingPreset">
                    <button class="accordion-button collapsed py-2 px-3 bg-light text-secondary small fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePreset" aria-expanded="false" aria-controls="collapsePreset">
                        Preset Waktu Manual (Opsional)
                    </button>
                </h2>
                <div id="collapsePreset" class="accordion-collapse collapse" aria-labelledby="headingPreset" data-bs-parent="#accordionTimerPreset">
                    <div class="accordion-body p-3 bg-white">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Menit</span>
                                    <input type="number" id="menitset" class="form-control" placeholder="0" min="0" max="60">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Detik</span>
                                    <input type="number" id="detikset" class="form-control" placeholder="0" min="0" max="59">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Konfirmasi Reset -->
    <div class="modal fade" id="resetModal" tabindex="-1" aria-labelledby="resetModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fw-bold" id="resetModalLabel">Konfirmasi Reset Pertandingan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="fs-5 text-dark mb-1">Yakin ingin mereset timer dan seluruh perolehan skor pertandingan?</p>
                    <small class="text-muted">Aksi ini akan mengembalikan waktu ke 0 dan menghapus riwayat penilaian partai ini.</small>
                </div>
                <div class="modal-footer justify-content-center border-top-0 pt-0">
                    <button type="button" class="btn btn-danger px-4 py-2 fw-bold" id="btnConfirmReset" data-bs-dismiss="modal">Ya, Reset Sekarang</button>
                    <button type="button" class="btn btn-secondary px-4 py-2" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Arena Data -->
    <div class="d-none" id="arena" data-arena="{{ $arena }}" data-is-seni="{{ $isSeni ? '1' : '0' }}"></div>

    <!-- Scripts -->
    <script src="{{ asset('assets/plugins/jquery/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootstrap-5.3.7/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

    <script>
        jQuery(document).ready(function ($) {
            const arenaId = $('#arena').data('arena');
            const isSeni = $('#arena').data('is-seni') == '1';

            let timerInterval = null;
            let currentTime = 0; // Tanding: detik | Seni: centiseconds (1/100 detik)
            let isPaused = false;
            let currentStatus = "{{ $timerStatus }}";

            // Formatters
            function formatTimeTanding(totalSeconds) {
                const minutes = Math.floor(totalSeconds / 60);
                const seconds = totalSeconds % 60;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            }

            function formatTimeSeni(centiseconds) {
                const minutes = Math.floor(centiseconds / 6000);
                const seconds = Math.floor((centiseconds % 6000) / 100);
                const ms = centiseconds % 100;
                return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}:${String(ms).padStart(2, '0')}`;
            }

            function updateDisplay() {
                const text = isSeni ? formatTimeSeni(currentTime) : formatTimeTanding(currentTime);
                $('#timer1').text(text);
            }

            function setTimerVisualState(status) {
                currentStatus = status;
                $('#timer1').removeClass('state-start state-pause state-stop');
                const badge = $('#timer-status-badge');

                badge.removeClass('bg-success bg-warning text-dark bg-danger bg-secondary');

                if (status === 'start') {
                    $('#timer1').addClass('state-start');
                    badge.addClass('bg-success').text('BERJALAN');
                    $('#start').prop('disabled', true);
                    renderPauseButton();
                } else if (status === 'pause') {
                    $('#timer1').addClass('state-pause');
                    badge.addClass('bg-warning text-dark').text('DIJEDA (PAUSE)');
                    $('#start').prop('disabled', true);
                    renderResumeButton();
                } else {
                    $('#timer1').addClass('state-stop');
                    badge.addClass('bg-secondary').text('BERHENTI');
                    $('#start').prop('disabled', false);
                    renderPauseButton(true);
                }
            }

            function renderPauseButton(disabled = false) {
                $('#pauseResumeCol').html(`
                    <button type="button" id="pause" class="btn btn-primary btn-control fs-5 w-100 shadow-sm" ${disabled ? 'disabled' : ''}>
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z"/>
                        </svg>
                        Pause
                    </button>
                `);
            }

            function renderResumeButton() {
                $('#pauseResumeCol').html(`
                    <button type="button" id="resume" class="btn btn-warning text-dark btn-control fs-5 w-100 shadow-sm">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
                        </svg>
                        Resume
                    </button>
                `);
            }

            // Client-side Timer Logic (Sama persis dengan score display)
            function startTimer() {
                if (!isPaused) {
                    currentTime = 0;
                }
                isPaused = false;
                clearInterval(timerInterval);

                const intervalMs = isSeni ? 10 : 1000;
                timerInterval = setInterval(() => {
                    currentTime++;
                    updateDisplay();
                }, intervalMs);

                setTimerVisualState('start');
            }

            function pauseTimer() {
                clearInterval(timerInterval);
                isPaused = true;
                setTimerVisualState('pause');
            }

            function resumeTimer() {
                if (isPaused) {
                    isPaused = false;
                    clearInterval(timerInterval);

                    const intervalMs = isSeni ? 10 : 1000;
                    timerInterval = setInterval(() => {
                        currentTime++;
                        updateDisplay();
                    }, intervalMs);

                    setTimerVisualState('start');
                }
            }

            function resetTimer() {
                clearInterval(timerInterval);
                currentTime = 0;
                isPaused = false;
                updateDisplay();
                setTimerVisualState('stop');
            }

            // AJAX Handlers (Tanpa Reload Halaman)
            function sendStart() {
                const menit = $('#menitset').val() || 0;
                const detik = $('#detikset').val() || 0;

                $.ajax({
                    url: `/timeradmin/?tipe=start&arena=${arenaId}&menit=${menit}&detik=${detik}`,
                    method: 'GET',
                    success: function (res) {
                        startTimer();
                    },
                    error: function (xhr) {
                        console.error('Gagal memulai timer:', xhr);
                    }
                });
            }

            function sendPause() {
                $.ajax({
                    url: `/timeradmin/?tipe=pause&arena=${arenaId}`,
                    method: 'GET',
                    success: function (res) {
                        pauseTimer();
                    },
                    error: function (xhr) {
                        console.error('Gagal menjeda timer:', xhr);
                    }
                });
            }

            function sendResume() {
                $.ajax({
                    url: `/timeradmin/?tipe=resume&arena=${arenaId}`,
                    method: 'GET',
                    success: function (res) {
                        resumeTimer();
                    },
                    error: function (xhr) {
                        console.error('Gagal melanjutkan timer:', xhr);
                    }
                });
            }

            function sendStop() {
                $.ajax({
                    url: `/timeradmin/?tipe=stop&arena=${arenaId}`,
                    method: 'GET',
                    success: function (res) {
                        resetTimer();
                    },
                    error: function (xhr) {
                        console.error('Gagal menghentikan timer:', xhr);
                    }
                });
            }

            function sendReset() {
                $.ajax({
                    url: `/timeradmin/?tipe=reset&arena=${arenaId}`,
                    method: 'GET',
                    success: function (res) {
                        resetTimer();
                        if (!isSeni) {
                            $('#input-continent').val('1');
                        }
                    },
                    error: function (xhr) {
                        console.error('Gagal mereset timer:', xhr);
                    }
                });
            }

            function sendBabak(val) {
                $.ajax({
                    url: `/timeradmin/?tipe=babak&value=${val}&arena=${arenaId}`,
                    method: 'GET',
                    success: function (res) {
                        console.log('Babak diperbarui:', val);
                    },
                    error: function (xhr) {
                        console.error('Gagal mengubah babak:', xhr);
                    }
                });
            }

            // Event Listeners (Delegated for dynamic elements)
            $(document).on('click', '#start', function (e) {
                e.preventDefault();
                sendStart();
            });

            $(document).on('click', '#pause', function (e) {
                e.preventDefault();
                sendPause();
            });

            $(document).on('click', '#resume', function (e) {
                e.preventDefault();
                sendResume();
            });

            $(document).on('click', '#stop', function (e) {
                e.preventDefault();
                sendStop();
            });

            $('#btnConfirmReset').click(function (e) {
                e.preventDefault();
                sendReset();
            });

            $('#input-continent').change(function (e) {
                e.preventDefault();
                const selectedVal = $(this).val();
                sendBabak(selectedVal);
            });

            // WebSocket Listeners
            function initWebSocket() {
                if (window.Echo) {
                    window.Echo.connector.pusher.connection.bind('connected', function () {
                        $('#sync-indicator').html('<span class="spinner-grow spinner-grow-sm text-success" role="status" style="width: 0.5rem; height: 0.5rem;"></span> Socket Terhubung');
                    });

                    window.Echo.connector.pusher.connection.bind('disconnected', function () {
                        $('#sync-indicator').html('<span class="badge bg-danger">Socket Terputus</span>');
                    });

                    // Channel Timer
                    window.Echo.channel('timer')
                        .listen('TimerUpdate', (datas) => {
                            const data = datas.action || datas;
                            if (data && data.arena == arenaId) {
                                if (data.action === 'start') {
                                    startTimer();
                                } else if (data.action === 'pause') {
                                    pauseTimer();
                                } else if (data.action === 'resume') {
                                    resumeTimer();
                                } else if (data.action === 'stop' || data.action === 'reset') {
                                    resetTimer();
                                }
                            }
                        });

                    // Channel Score Event (untuk mendeteksi update babak dari dewan/score)
                    window.Echo.channel('score-channel')
                        .listen('ScoreEvent', (datas) => {
                            const data = datas.message || datas;
                            if (data && data.arena == arenaId) {
                                if (data.babak && !isSeni) {
                                    $('#input-continent').val(String(data.babak));
                                }
                                if (data.partai) {
                                    $('#info-partai').text(`Partai ${data.partai}`);
                                }
                                if (data.namaBiru) $('#nama-biru').text(data.namaBiru);
                                if (data.namaMerah) $('#nama-merah').text(data.namaMerah);
                                if (data.kontigenBiru) $('#kontigen-biru').text(data.kontigenBiru);
                                if (data.kontigenMerah) $('#kontigen-merah').text(data.kontigenMerah);
                            }
                        });
                }
            }

            initWebSocket();

            // Set initial state from server render
            if (currentStatus === 'start') {
                startTimer();
            } else if (currentStatus === 'pause') {
                setTimerVisualState('pause');
            } else {
                setTimerVisualState('stop');
            }
        });

        // Fullscreen Toggle Helper
        function toggleFullScreen() {
            const doc = window.document;
            const docEl = doc.documentElement;

            const requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
            const cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

            if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
                if (requestFullScreen) {
                    requestFullScreen.call(docEl);
                }
            } else {
                if (cancelFullScreen) {
                    cancelFullScreen.call(doc);
                }
            }
        }
    </script>
</body>

</html>