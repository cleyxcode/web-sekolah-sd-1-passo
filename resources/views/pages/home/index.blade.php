@extends('layouts.frontend')

{{-- ============================================================ --}}
{{-- SEO HALAMAN BERANDA                                          --}}
{{-- ============================================================ --}}
@section('title', 'SD Negeri 1 Passo - Website Resmi | Unggul, Berkarakter & Berprestasi | Ambon, Maluku')
@section('meta_description', 'Website resmi SD Negeri 1 Passo, Kota Ambon, Maluku. Sekolah dasar unggulan dengan visi mewujudkan pendidikan berkualitas. Temukan berita, profil sekolah, galeri, dan portal orang tua siswa SD 1 Passo.')
@section('meta_keywords', 'SD Negeri 1 Passo, SDN 1 Passo, SD 1 Passo, sekolah dasar Passo, SD Passo Ambon, sekolah Passo, sekolah dasar negeri Ambon, sekolah dasar Maluku, website sekolah Passo')
@section('canonical', url('/'))
@section('og_title', 'SD Negeri 1 Passo - Website Resmi | Unggul, Berkarakter & Berprestasi')
@section('og_description', 'Website resmi SD Negeri 1 Passo, Kota Ambon, Maluku. Informasi akademik, berita terbaru, galeri kegiatan, dan portal orang tua siswa.')

@section('schema_json')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@graph": [
        {
            "@type": "WebSite",
            "@id": "{{ url('/') }}/#website",
            "name": "SD Negeri 1 Passo",
            "url": "{{ url('/') }}",
            "description": "Website resmi SD Negeri 1 Passo, Kota Ambon, Maluku",
            "inLanguage": "id-ID",
            "potentialAction": {
                "@type": "SearchAction",
                "target": "{{ url('/berita') }}?q={search_term_string}",
                "query-input": "required name=search_term_string"
            }
        },
        {
            "@type": "ElementarySchool",
            "@id": "{{ url('/') }}/#school",
            "name": "SD Negeri 1 Passo",
            "alternateName": ["SDN 1 Passo", "SD 1 Passo", "Sekolah Dasar Negeri 1 Passo", "SD Passo"],
            "url": "{{ url('/') }}",
            "description": "Sekolah Dasar Negeri 1 Passo adalah sekolah dasar unggulan berkomitmen mewujudkan pendidikan berkualitas, berkarakter, dan berprestasi di Kota Ambon, Maluku.",
            "address": {
                "@type": "PostalAddress",
                "streetAddress": "Jl. Pendidikan No. 1, Passo",
                "addressLocality": "Passo",
                "addressRegion": "Kota Ambon",
                "addressCountry": "ID"
            },
            "telephone": "{{ $settings->telepon ?? '' }}",
            "email": "{{ $settings->email ?? 'info@sdn1passo.sch.id' }}",
            "hasMap": "https://www.google.com/maps/search/SD+Negeri+1+Passo+Ambon",
            "areaServed": {
                "@type": "Place",
                "name": "Passo, Kota Ambon, Maluku, Indonesia"
            }
        }
    ]
}
</script>
@endsection

@section('content')

    @include('pages.home.sections.hero')
    @include('pages.home.sections.profil')
    @include('pages.home.sections.profil-guru')
    @include('pages.home.sections.berita')
    @include('pages.home.sections.galeri')
    @include('pages.home.sections.cta')

@endsection
