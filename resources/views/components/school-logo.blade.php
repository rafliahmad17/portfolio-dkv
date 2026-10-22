{{--
    Logo sekolah. Satu-satunya tempat yang menentukan berkas logo & ukurannya.

    - Memakai public/images/logo-sekolah.webp (256 px, hasil `php artisan images:optimize --logo`)
      bila ada; jika belum dibuat, otomatis memakai logo-sekolah.png asli.
    - width/height dibaca dari berkas sehingga browser menyisihkan ruangnya
      sebelum gambar selesai dimuat (tidak ada layout shift).

    Pemakaian: <x-school-logo class="..." style="..." alt="..." />
--}}
@props(['alt' => 'Logo SMK Negeri 2 Padang Panjang'])
@php
    $logoFile = is_file(public_path('images/logo-sekolah.webp'))
        ? 'images/logo-sekolah.webp'
        : 'images/logo-sekolah.png';
    $logoSize = @getimagesize(public_path($logoFile));
@endphp
<img src="{{ asset($logoFile) }}"
     alt="{{ $alt }}"
     @if ($logoSize) width="{{ $logoSize[0] }}" height="{{ $logoSize[1] }}" @endif
     decoding="async"
     {{ $attributes }}>
