<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tugas — ToDo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0b0f19;
            color: #f1f5f9;
            min-height: 100vh;
            background-image: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.18) 0%, transparent 50%);
        }
        h2, h3, h4 {
            font-family: 'Space Grotesk', sans-serif;
        }
        .split-wrapper {
            background-color: #131a2a;
            border: 1px solid #28354f;
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .side-info {
            background: linear-gradient(160deg, #1e1b4b 0%, #131a2a 100%);
            border-right: 1px solid #28354f;
        }
        .dark-input {
            background-color: #1a2336;
            border: 1px solid #28354f;
            color: #f1f5f9;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
        }
        .dark-input:focus {
            background-color: #1a2336;
            color: #f1f5f9;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }
        .dark-input::placeholder {
            color: #64748b;
        }
        .check-panel {
            background-color: #1a2336;
            border: 1px solid #28354f;
            border-radius: 0.85rem;
        }
        .btn-submit {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 0.75rem;
            padding: 0.75rem 1.5rem;
        }
        .btn-submit:hover {
            opacity: 0.92;
            color: white;
        }
    </style>
</head>
<body class="d-flex align-items-center py-5">
    <div class="container" style="max-width: 860px;">
        <div class="mb-3">
            <a href="{{ route('todos.index') }}" class="text-decoration-none text-secondary fw-medium">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke ToDo
            </a>
        </div>

        <div class="split-wrapper row g-0">
            <!-- Panel Kiri: Informasi Visual -->
            <div class="col-md-5 side-info p-4 p-md-5 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-4" 
                         style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.2); color: #818cf8;">
                        <i class="bi bi-lightning-charge-fill fs-4"></i>
                    </div>
                    <h3 class="fw-bold mb-3">Rencanakan Tugas Baru</h3>
                    <p class="text-secondary small" style="line-height: 1.6;">
                        Setiap tugas yang Anda simpan akan masuk ke dalam papan Kanban dan tersimpan permanen di database MySQL.
                    </p>
                </div>

                <div class="mt-4 pt-4 border-top border-secondary border-opacity-25">
                    <div class="d-flex align-items-center gap-2 text-secondary small mb-2">
                        <i class="bi bi-database-check text-success"></i>
                        <span>Penyimpanan Database MySQL</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 text-secondary small">
                        <i class="bi bi-calendar-event text-info"></i>
                        <span>Pencatatan waktu selesai otomatis</span>
                    </div>
                </div>
            </div>

            <!-- Panel Kanan: Form Input -->
            <div class="col-md-7 p-4 p-md-5">
                <form action="{{ route('todos.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="judul" class="form-label fw-medium small text-uppercase text-secondary">Judul ToDo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control dark-input @error('judul') is-invalid @enderror" 
                               id="judul" name="judul" value="{{ old('judul') }}" 
                               placeholder="Misal: Export SQL dan Push ke GitHub" required autofocus>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="keterangan" class="form-label fw-medium small text-uppercase text-secondary">Keterangan</label>
                        <textarea class="form-control dark-input" id="keterangan" name="keterangan" rows="3" 
                                  placeholder="Detail langkah atau catatan tambahan...">{{ old('keterangan') }}</textarea>
                    </div>

                    <div class="check-panel p-3 mb-4">
                        <div class="form-check form-switch d-flex align-items-center justify-content-between ps-0 mb-0">
                            <label class="form-check-label" for="is_selesai" style="cursor: pointer;">
                                <span class="d-block fw-semibold text-white">Tandai Sudah Selesai</span>
                                <span class="d-block text-secondary small">Otomatis mengisi tanggal penyelesaian hari ini</span>
                            </label>
                            <input class="form-check-input ms-3" type="checkbox" role="switch" 
                                   id="is_selesai" name="is_selesai" value="1" 
                                   style="width: 2.75rem; height: 1.4rem; cursor: pointer;">
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-submit flex-grow-1">
                            Simpan ke Board
                        </button>
                        <a href="{{ route('todos.index') }}" class="btn btn-outline-secondary rounded-3 px-3 d-flex align-items-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>