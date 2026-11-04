<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuruController extends Controller
{
    public function index(): View
    {
        $guruList = Guru::withCount(['kelasWali', 'pengampuMapel'])
            ->orderBy('nama_guru')
            ->get();

        return view('akademik.guru.index', compact('guruList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:30'],
            'nama_guru' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['aktif'] = $request->boolean('aktif', true);

        Guru::create($validated);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function update(Request $request, Guru $guru): RedirectResponse
    {
        $validated = $request->validate([
            'nip' => ['nullable', 'string', 'max:30'],
            'nama_guru' => ['required', 'string', 'max:100'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $guru->update($validated);

        return redirect()->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru): RedirectResponse
    {
        try {
            $guru->delete();

            return redirect()->route('guru.index')
                ->with('success', 'Data guru berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('guru.index')
                ->with('error', 'Gagal menghapus guru karena terdaftar sebagai wali kelas atau pengampu mapel.');
        }
    }
}
