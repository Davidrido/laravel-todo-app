<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo — Workspace ToDo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        :root {
            --bg-main: #0b0f19;
            --bg-panel: #131a2a;
            --bg-card: #1a2336;
            --border-color: #28354f;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --accent-amber: #f59e0b;
            --accent-emerald: #10b981;
            --accent-indigo: #6366f1;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-primary);
            min-height: 100vh;
            background-image: radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.12) 0%, transparent 40%),
                              radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.08) 0%, transparent 40%);
        }
        h1, h2, h3, h4, h5, .brand-font {
            font-family: 'Space Grotesk', sans-serif;
        }
        .navbar-custom {
            background: rgba(19, 26, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
        }
        .search-input {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            color: var(--text-primary);
            border-radius: 0.75rem;
        }
        .search-input:focus {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--accent-indigo);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        .search-input::placeholder {
            color: var(--text-secondary);
        }
        .kanban-column {
            background-color: var(--bg-panel);
            border: 1px solid var(--border-color);
            border-radius: 1.25rem;
            padding: 1.25rem;
            min-height: 65vh;
        }
        .task-item {
            background-color: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
            padding: 1.15rem;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        .task-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background-color: var(--accent-amber);
        }
        .task-item.is-done::before {
            background-color: var(--accent-emerald);
        }
        .task-item:hover {
            transform: translateY(-3px);
            border-color: #3b4d71;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4);
        }
        .btn-create {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            color: white;
            font-weight: 600;
            border-radius: 0.75rem;
            padding: 0.6rem 1.25rem;
            transition: opacity 0.2s;
        }
        .btn-create:hover {
            opacity: 0.9;
            color: white;
        }
        .check-btn {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 2px solid #475569;
            background: transparent;
            color: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .check-btn:hover {
            border-color: var(--accent-emerald);
            color: var(--accent-emerald);
        }
        .check-btn.checked {
            background-color: var(--accent-emerald);
            border-color: var(--accent-emerald);
            color: #0b0f19;
        }
    </style>
</head>
<body>
    @php
        $pendingTodos = $todos->where('is_selesai', false);
        $completedTodos = $todos->where('is_selesai', true);
    @endphp

    <!-- Top Navigation Bar -->
    <nav class="navbar-custom sticky-top py-3 mb-4">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-indigo text-white rounded-3 d-flex align-items-center justify-content-center" 
                     style="width: 38px; height: 38px; background: #6366f1;">
                    <i class="bi bi-kanban-fill fs-5"></i>
                </div>
                <div>
                    <span class="brand-font fw-bold fs-4 tracking-tight">ToDo</span>
                    <span class="badge bg-dark border border-secondary text-secondary ms-2">Laravel 12</span>
                </div>
            </div>

            <div class="d-flex align-items-center gap-3 flex-grow-1 flex-md-grow-0">
                <div class="position-relative flex-grow-1" style="min-width: 230px;">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="text" id="searchInput" class="form-control search-input ps-5" placeholder="Cari tugas...">
                </div>
                <a href="{{ route('todos.create') }}" class="btn btn-create text-nowrap">
                    <i class="bi bi-plus-lg me-1"></i> Tugas Baru
                </a>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        @if(session('success'))
            <div class="alert alert-dark border-success text-success d-flex justify-content-between align-items-center rounded-4 mb-4" role="alert">
                <div><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Kanban Board Grid (2 Kolom) -->
        <div class="row g-4">
            <!-- KOLOM KIRI: BELUM SELESAI -->
            <div class="col-lg-6">
                <div class="kanban-column">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background: var(--accent-amber);"></span>
                            <h5 class="mb-0 fw-bold">Sedang Dikerjakan</h5>
                        </div>
                        <span class="badge rounded-pill px-3 py-1" style="background: rgba(245, 158, 11, 0.15); color: var(--accent-amber);">
                            {{ $pendingTodos->count() }} Tugas
                        </span>
                    </div>

                    @if($pendingTodos->isEmpty())
                        <div class="text-center py-5 my-4 text-secondary">
                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                            <p class="mb-2">Tidak ada antrean tugas saat ini.</p>
                            <a href="{{ route('todos.create') }}" class="btn btn-sm btn-outline-light rounded-pill px-3">Tambah Tugas</a>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($pendingTodos as $todo)
                                <div class="task-item searchable-item">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <form action="{{ route('todos.toggle', $todo->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="check-btn mt-1" title="Tandai Selesai">
                                                    <i class="bi bi-check-lg fw-bold"></i>
                                                </button>
                                            </form>
                                            <div>
                                                <h6 class="fw-bold mb-1 fs-5 task-title">{{ $todo->judul }}</h6>
                                                @if($todo->keterangan)
                                                    <p class="text-secondary small mb-2 task-desc">{{ $todo->keterangan }}</p>
                                                @endif
                                                <div class="text-secondary" style="font-size: 0.75rem;">
                                                    <i class="bi bi-clock me-1"></i>Ditambahkan {{ $todo->created_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </div>

                                        <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-secondary hover-danger p-1" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- KOLOM KANAN: SELESAI -->
            <div class="col-lg-6">
                <div class="kanban-column">
                    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-secondary border-opacity-25">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle d-inline-block" style="width: 10px; height: 10px; background: var(--accent-emerald);"></span>
                            <h5 class="mb-0 fw-bold">Selesai</h5>
                        </div>
                        <span class="badge rounded-pill px-3 py-1" style="background: rgba(16, 185, 129, 0.15); color: var(--accent-emerald);">
                            {{ $completedTodos->count() }} Selesai
                        </span>
                    </div>

                    @if($completedTodos->isEmpty())
                        <div class="text-center py-5 my-4 text-secondary">
                            <i class="bi bi-check2-all fs-1 d-block mb-2 opacity-50"></i>
                            <p class="mb-0">Belum ada tugas yang diselesaikan.</p>
                        </div>
                    @else
                        <div class="d-flex flex-column gap-3">
                            @foreach($completedTodos as $todo)
                                <div class="task-item is-done searchable-item opacity-75">
                                    <div class="d-flex justify-content-between align-items-start gap-3">
                                        <div class="d-flex align-items-start gap-3">
                                            <form action="{{ route('todos.toggle', $todo->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="check-btn checked mt-1" title="Batalkan Selesai">
                                                    <i class="bi bi-check-lg fw-bold"></i>
                                                </button>
                                            </form>
                                            <div>
                                                <h6 class="fw-semibold mb-1 fs-5 text-decoration-line-through text-secondary task-title">{{ $todo->judul }}</h6>
                                                @if($todo->keterangan)
                                                    <p class="text-secondary small mb-2 task-desc">{{ $todo->keterangan }}</p>
                                                @endif
                                                @if($todo->tanggal_selesai)
                                                    <span class="badge rounded-pill fw-normal" style="background: rgba(16, 185, 129, 0.12); color: var(--accent-emerald); font-size: 0.75rem;">
                                                        <i class="bi bi-calendar2-check me-1"></i>
                                                        Selesai: {{ $todo->tanggal_selesai->translatedFormat('d M Y, H:i') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <form action="{{ route('todos.destroy', $todo->id) }}" method="POST" onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm text-secondary p-1" title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Fitur pencarian instan pada kedua kolom Kanban
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll('.searchable-item').forEach(item => {
                const title = item.querySelector('.task-title').textContent.toLowerCase();
                const descEl = item.querySelector('.task-desc');
                const desc = descEl ? descEl.textContent.toLowerCase() : '';
                
                if (title.includes(keyword) || desc.includes(keyword)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>