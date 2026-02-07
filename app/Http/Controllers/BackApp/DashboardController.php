<?php

namespace App\Http\Controllers\BackApp;

use App\Models\User;
use App\Helpers\Helper;
use App\Models\UserLog;
use App\Models\Category;
use App\Models\Document;
use App\Models\FiscalYear;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yaza\LaravelGoogleDriveStorage\Gdrive;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalStorageUsed = Document::sum('file_size');
        // Mengambil data untuk statistik
        $totalCategories = Category::count();

        // Ambil tahun aktif (sesuaikan logika dengan aplikasi Anda)
        $activeFiscalYear = FiscalYear::where('is_active', 1)->first()->year ?? 'N/A';

        $totalDocuments = Document::count();
        $totalUsers = User::count();

        // 10 Dokumen Terbaru
        $latestDocuments = Document::with(['category', 'uploader'])
            ->latest()
            ->take(10)
            ->get();

        // Ambil 10 log terakhir yang aksinya 'LOGIN'
        $latestLogins = UserLog::with('user')
            ->where('action', 'LOGIN')
            ->latest() // urutkan dari yang terbaru
            ->take(10)
            ->get();

        return view('dashboard', compact(
            'totalCategories',
            'activeFiscalYear',
            'totalDocuments',
            'totalUsers',
            'latestDocuments',
            'latestLogins',
            'totalStorageUsed'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
