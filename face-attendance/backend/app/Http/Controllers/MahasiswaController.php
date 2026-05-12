<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MahasiswaController extends Controller
{
    public function absenPage()
{
    return view('absen.index');
}

public function absenRun(Request $request)
{
    $python = "D:\\python310\\python.exe";
    $script = base_path('../face_recognition/scripts/recognize.py');
    $command = $python . " " . escapeshellarg($script);
    $output = shell_exec($command);

    // interpretasi output, bisa pakai regex atau cari keyword "Wajah tidak dikenali"
    if (strpos($output, "Wajah tidak dikenali") !== false) {
        return back()->with('error', 'Wajah tidak dikenali.');
    }

    // kalau berhasil parse nama dan nim dari output
    preg_match('/([A-Z0-9-]+) - (.+?) \((.+?)\)/', $output, $matches);

    if (count($matches) == 4) {
        $nim = $matches[1];
        $nama = $matches[2];
        $kelas = $matches[3];
        return back()->with('success', "Absensi berhasil untuk $nama ($nim)");
    }

    return back()->with('error', 'Gagal memproses hasil absensi.');
}
    public function index(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $mahasiswas = DB::table('mahasiswa')->orderBy('nama')->get();
        return view('mahasiswa.index', compact('mahasiswas'));
    }

    public function store(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'nim' => 'required|unique:mahasiswa,nim',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
        ]);

        DB::table('mahasiswa')->insert([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Mahasiswa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'nim' => 'required|string|max:50',
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
        ]);

        DB::table('mahasiswa')->where('id', $id)->update([
            'nim' => $request->nim,
            'nama' => $request->nama,
            'kelas' => $request->kelas,
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $mahasiswa = DB::table('mahasiswa')->where('id', $id)->first();
        if ($mahasiswa) {
            // Hapus folder dataset
            $datasetDir = base_path('../face_recognition/dataset/' . $mahasiswa->nim);
            if (\Illuminate\Support\Facades\File::exists($datasetDir)) {
                \Illuminate\Support\Facades\File::deleteDirectory($datasetDir);
            }
            
            // Hapus dari database
            DB::table('mahasiswa')->where('id', $id)->delete();
        }
        
        return back()->with('success', 'Mahasiswa beserta datasetnya berhasil dihapus.');
    }

    public function capturePage($nim)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        return view('mahasiswa.capture', compact('nim'));
    }

    public function captureRun(Request $request)
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $request->validate([
            'nim' => 'required|string',
            'images' => 'required|string', // JSON array
        ]);

        $nim = $request->nim;
        $images = json_decode($request->images, true);

        if (!is_array($images) || count($images) == 0) {
            return back()->with('error', 'Gagal mendapatkan gambar dari kamera.');
        }

        $datasetDir = base_path('../face_recognition/dataset/' . $nim);
        if (!file_exists($datasetDir)) {
            mkdir($datasetDir, 0777, true);
        }

        $count = 1;
        foreach ($images as $img) {
            $image_parts = explode(";base64,", $img);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                // Format: NIM_01.jpg
                $fileName = sprintf("%s_%02d.jpg", $nim, $count);
                file_put_contents($datasetDir . '/' . $fileName, $image_base64);
                $count++;
            }
        }

        return redirect()->route('mahasiswa.index')->with('success', 'Pengambilan dataset (' . ($count - 1) . ' gambar) untuk ' . $nim . ' selesai. Jangan lupa latih model (Train).');
    }

    public function trainModel()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        set_time_limit(0);
        $python = env('PYTHON_PATH', 'D:\\python310\\python.exe');
        $script = base_path('../face_recognition/scripts/train.py');

        $command = escapeshellcmd($python) . " " . escapeshellarg($script);
        exec($command . ' 2>&1', $output, $returnCode);

        $message = $returnCode === 0 ? 'Training model selesai.' : 'Training gagal. Periksa output.';
        /** @noinspection PhpUndefinedMethodInspection */
        return redirect()->route('mahasiswa.index')->with('output', implode("\n", $output))->with('success', $message);
    }
}
