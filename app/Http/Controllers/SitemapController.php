<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Response;

/**
 * SitemapController
 * 
 * Menghasilkan file sitemap.xml secara dinamis.
 * Sitemap adalah peta website yang dikirim ke Google agar semua
 * halaman website bisa ditemukan dan diindeks oleh mesin pencari.
 * 
 * Format mengikuti standar: https://www.sitemaps.org/protocol.html
 */
class SitemapController extends Controller
{
    /**
     * Menghasilkan sitemap.xml berisi semua URL publik website.
     * 
     * Halaman yang dimasukkan:
     * - Beranda
     * - Profil Sekolah
     * - Daftar Berita
     * - Setiap artikel berita (dinamis dari database)
     * - Galeri
     * 
     * @return \Illuminate\Http\Response
     */
    public function index(): Response
    {
        // Ambil semua berita yang sudah dipublikasikan, urutkan terbaru dulu
        $beritaList = Berita::where('status', 'publish')
            ->orderBy('published_at', 'desc')
            ->select(['slug', 'updated_at', 'published_at'])
            ->get();

        // Waktu terbaru artikel berita untuk lastmod halaman daftar berita
        $latestBerita = $beritaList->first()?->published_at ?? now();

        // Render view XML, lalu tambahkan XML declaration di depan
        // (tidak diletakkan di blade agar tidak konflik dengan PHP parser)
        $xmlDeclaration = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $content = $xmlDeclaration . view('sitemap.index', compact('beritaList', 'latestBerita'))->render();

        return response($content, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }
}
