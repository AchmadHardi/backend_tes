<?php

namespace App\Http\Controllers;

// use App\Models\Karyawan;
use App\Models\Login;
use App\Models\Unit;
// use App\Models\Jabatan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        // Menghitung jumlah karyawan
        // $jumlahKaryawan = Karyawan::count();

        // Menghitung jumlah login berdasarkan rentang waktu
        $jumlahLogin = Login::whereBetween('login_time', [$startDate, $endDate])->count();

        // Menghitung jumlah unit
        $jumlahUnit = Unit::count();

        // Menghitung jumlah jabatan
        // $jumlahJabatan = Jabatan::count();

        // Mendapatkan top 10 pengguna yang login lebih dari 25 kali dalam rentang waktu yang ditentukan
        $topUsers = Login::select('user_id', \DB::raw('count(*) as login_count'))
            ->whereBetween('login_time', [$startDate, $endDate])
            ->groupBy('user_id')
            ->having('login_count', '>', 25)
            ->orderBy('login_count', 'desc')
            ->take(10)
            ->get();

        return view('dashboard.dashboard', [
            // 'jumlahKaryawan' => $jumlahKaryawan,
            'jumlahLogin' => $jumlahLogin,
            'jumlahUnit' => $jumlahUnit,
            // 'jumlahJabatan' => $jumlahJabatan,
            'topUsers' => $topUsers,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
