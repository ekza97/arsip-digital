<?php

namespace App\Listeners;

use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogSuccessfulLogin
{
    protected $request;

    // Inject Request untuk mengambil IP dan User Agent
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Login $event)
    {
        // $event->user berisi data user yang baru saja login
        UserLog::create([
            'user_id'    => $event->user->id,
            'action'     => 'LOGIN',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'details'    => 'Pengguna '.$event->user->name.' berhasil masuk ke sistem.',
        ]);
        
        // Opsional: Update kolom last_login_at di tabel users jika punya
        // $event->user->update(['last_login_at' => now()]);
    }
}
