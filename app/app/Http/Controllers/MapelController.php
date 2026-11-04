<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapelController extends Controller
{
    public function index(): View
    {
        $mapelList = Mapel::orderBy('urutan_leger')
            ->orderBy('kode_mapel')
            ->withCount('pengampuMapel')
            ->get();

        return view('akademik.mapel.index', compact('mapelList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20', 'unique:mapel,kode_mapel'],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'kelompok' => ['required', 'string', 'max:10'],
            'urutan_leger' => ['nullable', 'integer', 'min:1'],
        ]);

        $validated['urutan_leger'] = $validated['urutan_leger'] ?? ((Mapel::max('urutan_leger') ?? 0) + 1);

        Mapel::create($validated);

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(Request $request, Mapel $mapel): RedirectResponse
    {
        $validated = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:20', 'unique:mapel,kode_mapel,'.$mapel->id],
            'nama_mapel' => ['required', 'string', 'max:100'],
            'kelompok' => ['required', 'string', 'max:10'],
            'urutan_leger' => ['required', 'integer', 'min:1'],
        ]);

        $mapel->update($validated);

        return redirect()->route('mapel.index')
            ->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Mapel $mapel): RedirectResponse
    {
        try {
            $mapel->delete();

            return redirect()->route('mapel.index')
                ->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('mapel.index')
                ->with('error', 'Gagal menghapus mata pelajaran karena masih digunakan di kelas pengampu.');
        }
    }
}
