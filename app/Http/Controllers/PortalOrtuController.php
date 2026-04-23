<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\OrangTua;

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
}
