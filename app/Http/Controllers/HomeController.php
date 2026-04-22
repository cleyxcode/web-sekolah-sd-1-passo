<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\ProfilSekolah;
use App\Models\Galeri;

class HomeController extends Controller
{
    public function index()
    {
        $berita = Berita::where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
            
        $visi = ProfilSekolah::where('jenis', 'visi')->first();
        $misi = ProfilSekolah::where('jenis', 'misi')->first();
        
        $galeri = Galeri::latest()->take(4)->get();
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();
        
        return view('pages.home.index', compact('berita', 'visi', 'misi', 'galeri', 'pendaftaran'));
    }

    public function profil()
    {
        $visi = ProfilSekolah::where('jenis', 'visi')->first();
        $misi = ProfilSekolah::where('jenis', 'misi')->first();
        $sejarah = ProfilSekolah::where('jenis', 'sejarah')->first();
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        return view('pages.profil.index', compact('visi', 'misi', 'sejarah', 'pendaftaran'));
    }

    public function berita()
    {
        $berita = Berita::where('status', 'publish')->orderBy('published_at', 'desc')->paginate(9);
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        return view('pages.berita.index', compact('berita', 'pendaftaran'));
    }

    public function beritaDetail(Berita $berita)
    {
        if ($berita->status !== 'publish') {
            abort(404);
        }
        $beritaLainnya = Berita::where('status', 'publish')->where('id', '!=', $berita->id)->orderBy('published_at', 'desc')->take(3)->get();
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        return view('pages.berita.detail', compact('berita', 'beritaLainnya', 'pendaftaran'));
    }

    public function galeri()
    {
        $galeri = Galeri::latest()->paginate(12);
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        return view('pages.galeri.index', compact('galeri', 'pendaftaran'));
    }

    public function pendaftaran()
    {
        $pendaftaran = \App\Models\Pendaftaran::where('is_active', true)->latest()->first();

        if (!$pendaftaran) {
            return redirect()->route('home')->with('error', 'Pendaftaran belum dibuka.');
        }

        return view('pages.pendaftaran.index', compact('pendaftaran'));
    }
}
