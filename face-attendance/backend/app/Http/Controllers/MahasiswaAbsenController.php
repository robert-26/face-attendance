<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaAbsenController extends Controller
{
    private function getDeadline(): string
    {
        return DB::table('settings')->where('key', 'deadline')->value('value') ?? '23:59';
    }

    private function isPastDeadline(?string $deadline = null): bool
    {
        $deadline = $deadline ?? $this->getDeadline();

        return now()->format('H:i') > $deadline;
    }

    private function deadlineMessage(?string $deadline = null): string
    {
        $deadline = $deadline ?? $this->getDeadline();

        return 'Absensi hari ini sudah ditutup. Batas waktu absensi adalah pukul ' . $deadline . ' WIB.';
    }

    /** Halaman absen mahasiswa */
    public function absenPage()
    {
        if (!session('mhs_logged_in')) {
            return redirect()->route('login');
        }

        $nim   = session('mhs_nim');
        $nama  = session('mhs_nama');
        $kelas = session('mhs_kelas');

        // Cek apakah sudah absen hari ini
        $sudahAbsen = DB::table('absensi')
            ->where('nim', $nim)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        // Deadline absen (jam 23:59 default, bisa diubah admin)
        $deadline = $this->getDeadline();
        $isPastDeadline = $this->isPastDeadline($deadline);

        return view('mahasiswa.absen', compact('nim', 'nama', 'kelas', 'sudahAbsen', 'deadline', 'isPastDeadline'));
    }

    /** Absen dengan wajah */
    public function absenWajah(Request $request)
    {
        if (!session('mhs_logged_in')) {
            return redirect()->route('login');
        }

        $nim   = session('mhs_nim');
        $nama  = session('mhs_nama');
        $kelas = session('mhs_kelas');

        // Cek sudah absen belum
        $sudah = DB::table('absensi')
            ->where('nim', $nim)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if ($sudah) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Status: ' . ucfirst($sudah->status));
        }

        $deadline = $this->getDeadline();
        if ($this->isPastDeadline($deadline)) {
            return back()->with('error', $this->deadlineMessage($deadline));
        }

        $request->validate([
            'image' => 'required|string',
        ]);

        $image_parts = explode(";base64,", $request->image);
        if (count($image_parts) != 2) {
            return back()->with('error', 'Format gambar tidak valid.');
        }
        $image_base64 = base64_decode($image_parts[1]);
        
        // Simpan ke file temp
        $fileName = 'temp_absen_' . uniqid() . '.jpg';
        $storageDir = storage_path('app');
        if (!file_exists($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        $tempPath = $storageDir . '/' . $fileName;
        file_put_contents($tempPath, $image_base64);

        // Jalankan Python face recognition
        $python  = env('PYTHON_PATH', 'D:\\python310\\python.exe');
        $script  = base_path('../face_recognition/scripts/recognize_image.py');
        $command = escapeshellcmd($python) . ' ' . escapeshellarg($script) . ' --image ' . escapeshellarg($tempPath);
        $output  = shell_exec($command . ' 2>&1');

        // Hapus file sementara
        @unlink($tempPath);

        // Cek apakah wajah dikenali sebagai NIM ini
        if (strpos($output, $nim) === false || strpos($output, 'UNKNOWN') !== false) {
            return back()->with('error', 'Wajah tidak dikenali atau tidak cocok. Coba lagi.');
        }

        // Simpan ke DB sebagai hadir
        DB::table('absensi')->insert([
            'nim'       => $nim,
            'nama'      => $nama,
            'kelas'     => $kelas,
            'tanggal'   => now()->toDateString(),
            'waktu'     => now()->toTimeString(),
            'status'    => 'hadir',
            'created_at'=> now(),
        ]);

        return back()->with('success', "✅ Absensi berhasil! Anda tercatat HADIR pada " . now()->format('H:i'));
    }

    /** Absen dengan izin */
    public function absenIzin(Request $request)
    {
        if (!session('mhs_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'keterangan' => 'required|string|max:500',
        ]);

        $nim   = session('mhs_nim');
        $nama  = session('mhs_nama');
        $kelas = session('mhs_kelas');

        $sudah = DB::table('absensi')
            ->where('nim', $nim)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if ($sudah) {
            return back()->with('error', 'Anda sudah melakukan absensi hari ini. Status: ' . ucfirst($sudah->status));
        }

        $deadline = $this->getDeadline();
        if ($this->isPastDeadline($deadline)) {
            return back()->with('error', $this->deadlineMessage($deadline));
        }

        DB::table('absensi')->insert([
            'nim'        => $nim,
            'nama'       => $nama,
            'kelas'      => $kelas,
            'tanggal'    => now()->toDateString(),
            'waktu'      => now()->toTimeString(),
            'status'     => 'izin',
            'keterangan' => $request->keterangan,
            'created_at' => now(),
        ]);

        return back()->with('success', "📝 Izin berhasil dicatat pada " . now()->format('H:i'));
    }

    /** (Command/Scheduler) Tandai alpha semua yang belum absen lewat deadline */
    public static function tandaiAlpha(string $deadline = '23:59')
    {
        $today = now()->toDateString();

        // Ambil semua NIM yang belum absen hari ini
        $semuaMhs = DB::table('mahasiswa')->get();
        $sudahAbsen = DB::table('absensi')
            ->whereDate('tanggal', $today)
            ->pluck('nim')
            ->toArray();

        foreach ($semuaMhs as $m) {
            if (!in_array($m->nim, $sudahAbsen)) {
                DB::table('absensi')->insert([
                    'nim'        => $m->nim,
                    'nama'       => $m->nama,
                    'kelas'      => $m->kelas,
                    'tanggal'    => $today,
                    'waktu'      => $deadline,
                    'status'     => 'alpha',
                    'keterangan' => 'Tidak melakukan absensi sampai batas waktu.',
                    'created_at' => now(),
                ]);
            }
        }
    }

    /** Riwayat absensi mahasiswa */
    public function riwayatPage()
    {
        if (!session('mhs_logged_in')) {
            return redirect()->route('login');
        }

        $nim = session('mhs_nim');
        $absensi = DB::table('absensi')
            ->where('nim', $nim)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu', 'desc')
            ->get();

        return view('mahasiswa.riwayat', compact('absensi'));
    }
}
