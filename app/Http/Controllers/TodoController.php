<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    // Menampilkan halaman utama (daftar ToDo)
    public function index()
    {
        $todos = Todo::orderBy('is_selesai', 'asc')
                     ->orderBy('created_at', 'desc')
                     ->get();
        return view('todos.index', compact('todos'));
    }

    // Menampilkan halaman form tambah ToDo
    public function create()
    {
        return view('todos.create');
    }

    // Menyimpan ToDo baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $isSelesai = $request->has('is_selesai');

        Todo::create([
            'judul' => $request->judul,
            'keterangan' => $request->keterangan,
            'is_selesai' => $isSelesai,
            'tanggal_selesai' => $isSelesai ? now() : null,
        ]);

        return redirect()->route('todos.index')->with('success', 'Tugas berhasil ditambahkan!');
    }

    // Mengubah status selesai/belum langsung dari halaman Home
    public function toggleSelesai(Todo $todo)
    {
        $statusBaru = !$todo->is_selesai;

        $todo->update([
            'is_selesai' => $statusBaru,
            'tanggal_selesai' => $statusBaru ? now() : null,
        ]);

        return redirect()->route('todos.index')->with('success', 'Status tugas diperbarui!');
    }

    // Menghapus ToDo
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return redirect()->route('todos.index')->with('success', 'Tugas berhasil dihapus!');
    }
}