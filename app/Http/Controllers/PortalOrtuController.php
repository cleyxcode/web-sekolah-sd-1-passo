<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrangTua;
use App\Models\Tugas;

class PortalOrtuController extends Controller
{
    public function loginForm()
    {
        if (Auth::guard('ortu')->check()) {
            return redirect()->route('portal.ortu.dashboard');
        }
        return view('pages.portal-ortu.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('ortu')->attempt($credentials, $request->boolean('remember-me'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('portal.ortu.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('ortu')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function dashboard()
    {
        $ortu = Auth::guard('ortu')->user();
        if (!$ortu) {
            return redirect()->route('login.ortu');
        }

        $anak_anak = $ortu->siswas()->with([
            'kelas.jadwalPelajarans.mataPelajaran',
            'kelas.jadwalPelajarans.guru',
            'kelas.waliKelas',
            'nilais.mataPelajaran',
            'presensis' => function($q) { $q->orderBy('tanggal', 'desc')->limit(30); },
            'catatanPerkembangans.guru' => function($q) { $q->latest(); }
        ])->get();

        // Hitung statistik kehadiran per anak
        foreach ($anak_anak as $anak) {
            $anak->stat_hadir  = $anak->presensis->where('status','hadir')->count();
            $anak->stat_sakit  = $anak->presensis->where('status','sakit')->count();
            $anak->stat_izin   = $anak->presensis->where('status','izin')->count();
            $anak->stat_alpha  = $anak->presensis->where('status','alpha')->count();
            $anak->stat_total  = $anak->presensis->count();
            $anak->pct_hadir   = $anak->stat_total > 0
                ? round(($anak->stat_hadir / $anak->stat_total) * 100)
                : 0;
            // Presensi yang ada foto absen
            $anak->presensis_foto = $anak->presensis->whereNotNull('foto_absen')->values();

            // Tugas aktif untuk kelas anak ini (urut deadline terdekat)
            if ($anak->kelas_id) {
                $anak->tugas_kelas = Tugas::where('kelas_id', $anak->kelas_id)
                    ->where('status', 'aktif')
                    ->with(['guru', 'komentars.guru'])
                    ->orderBy('deadline', 'asc')
                    ->get();
            } else {
                $anak->tugas_kelas = collect();
            }
        }

        return view('pages.portal-ortu.index', compact('ortu', 'anak_anak'));
    }

    public function profil()
    {
        $ortu = Auth::guard('ortu')->user();
        if (!$ortu) return redirect()->route('login.ortu');
        return view('pages.portal-ortu.profil', compact('ortu'));
    }

    public function updateProfil(Request $request)
    {
        $ortu = Auth::guard('ortu')->user();
        if (!$ortu) return redirect()->route('login.ortu');

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:20',
            'pekerjaan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $ortu->nama = $validated['nama'];
        $ortu->no_telepon = $validated['no_telepon'];
        $ortu->pekerjaan = $validated['pekerjaan'];
        $ortu->alamat = $validated['alamat'];

        if (!empty($validated['password'])) {
            $ortu->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $ortu->save();

        return back()->with('success', 'Profil dan pengaturan berhasil diperbarui!');
    }

    public function forgotPasswordForm()
    {
        return view('pages.portal-ortu.auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:orang_tuas,email',
        ], [
            'email.exists' => 'Email tidak ditemukan di sistem kami.'
        ]);

        $ortu = OrangTua::where('email', $request->email)->first();
        
        $otp = rand(100000, 999999);
        $ortu->otp_code = $otp;
        $ortu->otp_expires_at = now()->addMinutes(10);
        $ortu->save();

        \Illuminate\Support\Facades\Mail::to($ortu->email)->send(new \App\Mail\OrtuOtpMail($otp));

        session()->put('reset_email', $ortu->email);
        
        return redirect()->route('ortu.verify_otp')->with('success', 'Kode OTP telah dikirim ke email Anda.');
    }

    public function verifyOtpForm()
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('ortu.forgot_password');
        }
        return view('pages.portal-ortu.auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        $email = session()->get('reset_email');
        if (!$email) return redirect()->route('ortu.forgot_password');

        $ortu = OrangTua::where('email', $email)
            ->where('otp_code', $request->otp)
            ->where('otp_expires_at', '>=', now())
            ->first();

        if (!$ortu) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kadaluarsa.']);
        }

        session()->put('otp_verified', true);
        return redirect()->route('ortu.reset_password')->with('success', 'OTP valid. Silakan buat password baru.');
    }

    public function resetPasswordForm()
    {
        if (!session()->get('otp_verified')) {
            return redirect()->route('ortu.forgot_password');
        }
        return view('pages.portal-ortu.auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!session()->get('otp_verified')) {
            return redirect()->route('ortu.forgot_password');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session()->get('reset_email');
        $ortu = OrangTua::where('email', $email)->first();
        
        if ($ortu) {
            $ortu->password = \Illuminate\Support\Facades\Hash::make($request->password);
            $ortu->otp_code = null;
            $ortu->otp_expires_at = null;
            $ortu->save();
        }

        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login.ortu')->with('success', 'Password berhasil direset. Silakan login.');
    }
}
