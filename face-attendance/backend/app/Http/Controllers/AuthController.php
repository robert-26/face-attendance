<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika sudah login sebagai admin
        if (session('admin_logged_in')) {
            return redirect()->route('dashboard');
        }
        // Jika sudah login sebagai mahasiswa
        if (session('mhs_logged_in')) {
            return redirect()->route('mhs.absen');
        }

        return view('auth.login');
    }

    public function showRegister()
    {
        // Jika sudah login
        if (session('admin_logged_in')) {
            return redirect()->route('dashboard');
        }
        if (session('mhs_logged_in')) {
            return redirect()->route('mhs.absen');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'nim'   => 'required|unique:mahasiswa,nim',
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|string|max:100',
        ]);

        DB::table('mahasiswa')->insert([
            'nim'        => $request->nim,
            'nama'       => $request->nama,
            'kelas'      => $request->kelas,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat. Gunakan format email: nim@kampus.com dan password: nim untuk login.');
    }

    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password'   => 'required',
        ]);

        $identifier = $request->identifier;
        $password   = $request->password;

        // --- Admin login ---
        // Admin uses identifier = 'admin@kampus.com' and password = 'admin123'
        if ($identifier === 'admin@kampus.com' && $password === 'admin123') {
            $request->session()->put('admin_logged_in', true);
            return redirect()->route('dashboard');
        }

        // --- Mahasiswa login ---
        // Mahasiswa uses identifier = 'Username (Nama)' and password = 'NIM'
        // We will also check the reverse case (identifier = NIM, password = Nama) just to be user friendly
        $mahasiswa = DB::table('mahasiswa')
            ->where(function($query) use ($identifier, $password) {
                $query->where('nama', $identifier)
                      ->where('nim', $password);
            })
            ->orWhere(function($query) use ($identifier, $password) {
                $query->where('nim', $identifier)
                      ->where('nama', $password);
            })
            ->first();

        if ($mahasiswa) {
            $request->session()->put('mhs_logged_in', true);
            $request->session()->put('mhs_nim',   $mahasiswa->nim);
            $request->session()->put('mhs_nama',  $mahasiswa->nama);
            $request->session()->put('mhs_kelas', $mahasiswa->kelas);
            $request->session()->put('mhs_id',    $mahasiswa->id);
            return redirect()->route('mhs.absen');
        }

        return back()->with('error', 'Kredensial tidak valid. Silakan periksa kembali Username/Email dan Password/NIM Anda.');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }

    public function dashboard()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $mahasiswaCount = DB::table('mahasiswa')->count();
        $absensiToday   = DB::table('absensi')
            ->whereDate('tanggal', now()->toDateString())
            ->count();

        return view('dashboard', compact('mahasiswaCount', 'absensiToday'));
    }
}
