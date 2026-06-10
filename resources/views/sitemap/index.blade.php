<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    {{-- HALAMAN STATIS UTAMA --}}

    <url>
        <loc>{{ route('home') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    <url>
        <loc>{{ route('profil') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.9</priority>
    </url>

    <url>
        <loc>{{ route('berita.index') }}</loc>
        <lastmod>{{ \Carbon\Carbon::parse($latestBerita)->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>{{ route('galeri') }}</loc>
        <lastmod>{{ now()->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>

    {{-- HALAMAN BERITA DINAMIS --}}
    @foreach($beritaList as $item)
    <url>
        <loc>{{ route('berita.detail', $item->slug) }}</loc>
        <lastmod>{{ \Carbon\Carbon::parse($item->updated_at ?? $item->published_at)->toAtomString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    @endforeach

</urlset>
