@extends('layouts.frontend')

@section('title', 'Sistem Informasi Sekolah - SD Negeri 1 Passo')

@section('content')

    @include('pages.home.sections.hero')
    @include('pages.home.sections.profil')
    @include('pages.home.sections.berita')
    @include('pages.home.sections.galeri')
    @include('pages.home.sections.cta')

@endsection
