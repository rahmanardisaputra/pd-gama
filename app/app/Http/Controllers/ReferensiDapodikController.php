<?php

namespace App\Http\Controllers;

use App\Models\ReferensiDapodik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReferensiDapodikController extends Controller
{
    /**
     * Daftar seluruh kategori Dapodik beserta labelnya.
     */
    protected array $kategoriLabels = [
        'agama' => 'Agama',
        'kebutuhan_khusus' => 'Kebutuhan Khusus',
        'tempat_tinggal' => 'Tempat Tinggal',
        'moda_transportasi' => 'Moda Transportasi',
        'pendidikan' => 'Pendidikan',
        'pekerjaan' => 'Pekerjaan',
        'penghasilan' => 'Penghasilan',
        'alasan_menolak_pip' => 'Alasan Menolak PIP',
        'jenis_prestasi' => 'Jenis Prestasi',
        'tingkat_prestasi' => 'Tingkat Prestasi',
        'jenis_beasiswa' => 'Jenis Beasiswa',
        'jenis_pendaftaran' => 'Jenis Pendaftaran',
        'alasan_keluar' => 'Alasan Keluar',
    ];

    public function index(Request $request): View
    {
        $kategoriList = $this->kategoriLabels;
        $activeKategori = $request->get('kategori', 'agama');

        if (! array_key_exists($activeKategori, $kategoriList)) {
            $activeKategori = 'agama';
        }

        $items = ReferensiDapodik::where('kategori', $activeKategori)
            ->orderBy('urutan')
            ->orderBy('kode')
            ->get();

        // Hitung total item per kategori untuk badge di sidebar kategori
        $counts = ReferensiDapodik::selectRaw('kategori, count(*) as total')
            ->groupBy('kategori')
            ->pluck('total', 'kategori');

        return view('referensi.index', [
            'kategoriList' => $kategoriList,
            'activeKategori' => $activeKategori,
            'activeLabel' => $kategoriList[$activeKategori],
            'items' => $items,
            'counts' => $counts,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kategori' => ['required', 'string', 'max:50'],
            'kode' => ['nullable', 'string', 'max:10'],
            'nama' => ['required', 'string', 'max:100'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ]);

        $validated['urutan'] = $validated['urutan'] ?? ((ReferensiDapodik::where('kategori', $validated['kategori'])->max('urutan') ?? 0) + 1);
        $validated['aktif'] = $request->boolean('aktif', true);

        ReferensiDapodik::create($validated);

        return redirect()->route('referensi.index', ['kategori' => $validated['kategori']])
            ->with('success', 'Data referensi baru berhasil ditambahkan.');
    }

    public function update(Request $request, ReferensiDapodik $referensi): RedirectResponse
    {
        $validated = $request->validate([
            'kode' => ['nullable', 'string', 'max:10'],
            'nama' => ['required', 'string', 'max:100'],
            'urutan' => ['required', 'integer', 'min:0'],
            'aktif' => ['nullable', 'boolean'],
        ]);

        $validated['aktif'] = $request->boolean('aktif');

        $referensi->update($validated);

        return redirect()->route('referensi.index', ['kategori' => $referensi->kategori])
            ->with('success', 'Data referensi berhasil diperbarui.');
    }

    public function toggle(ReferensiDapodik $referensi): RedirectResponse
    {
        $referensi->update(['aktif' => ! $referensi->aktif]);

        return redirect()->route('referensi.index', ['kategori' => $referensi->kategori])
            ->with('success', 'Status aktif referensi berhasil diubah.');
    }

    public function destroy(ReferensiDapodik $referensi): RedirectResponse
    {
        $kategori = $referensi->kategori;

        try {
            $referensi->delete();

            return redirect()->route('referensi.index', ['kategori' => $kategori])
                ->with('success', 'Data referensi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('referensi.index', ['kategori' => $kategori])
                ->with('error', 'Gagal menghapus data karena masih digunakan oleh relasi data siswa.');
        }
    }
}
