<?php

namespace App\Http\Controllers;

use App\Models\KompetensiKeahlian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KompetensiKeahlianController extends Controller
{
    public function index(): View
    {
        $jurusanList = KompetensiKeahlian::withCount(['siswa', 'kelas'])->get();

        return view('akademik.jurusan.index', compact('jurusanList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_jurusan' => ['required', 'string', 'max:20', 'unique:kompetensi_keahlian,kode_jurusan'],
            'nama_jurusan' => ['required', 'string', 'max:100'],
        ]);

        KompetensiKeahlian::create($validated);

        return redirect()->route('jurusan.index')
            ->with('success', 'Kompetensi keahlian / jurusan berhasil ditambahkan.');
    }

    public function update(Request $request, KompetensiKeahlian $jurusan): RedirectResponse
    {
        $validated = $request->validate([
            'kode_jurusan' => ['required', 'string', 'max:20', 'unique:kompetensi_keahlian,kode_jurusan,'.$jurusan->id],
            'nama_jurusan' => ['required', 'string', 'max:100'],
        ]);

        $jurusan->update($validated);

        return redirect()->route('jurusan.index')
            ->with('success', 'Kompetensi keahlian berhasil diperbarui.');
    }

    public function destroy(KompetensiKeahlian $jurusan): RedirectResponse
    {
        try {
            $jurusan->delete();

            return redirect()->route('jurusan.index')
                ->with('success', 'Kompetensi keahlian berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('jurusan.index')
                ->with('error', 'Gagal menghapus jurusan karena masih digunakan oleh data kelas atau siswa.');
        }
    }
}
