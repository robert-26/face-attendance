<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

/** @noinspection PhpUndefinedFunctionInspection */
class AbsensiController extends Controller
{
    public function index()
    {
        if (!session('admin_logged_in')) {
            return redirect()->route('login');
        }

        $absensi = DB::table('absensi')
            ->orderByDesc('tanggal')
            ->orderByDesc('waktu')
            ->get();

        return view('absensi.index', compact('absensi'));
    }
}
