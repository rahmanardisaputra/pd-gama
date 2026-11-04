<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function index(): View
    {
        $tahunAjaranList = TahunAjaran::withCount('kelas')
            ->orderByDesc('nama_tahun')
            ->orderBy('semester')
            ->get();

        return view('akademik.tahun_ajaran.index', compact('tahunAjaranList'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tahun' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:Ganjil,Genap'],
            'nama_kepala_sekolah' => ['nullable', 'string', 'max:100'],
            'nip_kepala_sekolah' => ['nullable', 'string', 'max:30'],
            'tempat_cetak_rapor' => ['nullable', 'string', 'max:50'],
            'tanggal_cetak_rapor' => ['nullable', 'date'],
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        if ($validated['aktif']) {
            TahunAjaran::query()->update(['aktif' => false]);
        }

        TahunAjaran::create($validated);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function update(Request $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        $validated = $request->validate([
            'nama_tahun' => ['required', 'string', 'max:20'],
            'semester' => ['required', 'in:Ganjil,Genap'],
            'nama_kepala_sekolah' => ['nullable', 'string', 'max:100'],
            'nip_kepala_sekolah' => ['nullable', 'string', 'max:30'],
            'tempat_cetak_rapor' => ['nullable', 'string', 'max:50'],
            'tanggal_cetak_rapor' => ['nullable', 'date'],
        ]);

        $isAktif = $request->boolean('aktif');
        if ($isAktif) {
            TahunAjaran::where('id', '!=', $tahunAjaran->id)->update(['aktif' => false]);
            $validated['aktif'] = true;
        }

        $tahunAjaran->update($validated);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function setAktif(TahunAjaran $tahunAjaran): RedirectResponse
    {
        TahunAjaran::query()->update(['aktif' => false]);
        $tahunAjaran->update(['aktif' => true]);

        return redirect()->route('tahun-ajaran.index')
            ->with('success', "Tahun Ajaran {$tahunAjaran->nama_tahun} ({$tahunAjaran->semester}) sekarang aktif.");
    }

    public function destroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        if ($tahunAjaran->aktif) {
            return redirect()->route('tahun-ajaran.index')
                ->with('error', 'Tidak dapat menghapus tahun ajaran yang sedang aktif.');
        }

        try {
            $tahunAjaran->delete();

            return redirect()->route('tahun-ajaran.index')
                ->with('success', 'Tahun ajaran berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('tahun-ajaran.index')
                ->with('error', 'Gagal menghapus tahun ajaran karena memiliki data kelas terkait.');
        }
    }
}
