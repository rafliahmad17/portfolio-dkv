<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi (Bahasa Indonesia)
    |--------------------------------------------------------------------------
    |
    | Kunci yang tidak ada di berkas ini otomatis memakai bahasa cadangan
    | (APP_FALLBACK_LOCALE=en) bawaan framework, sehingga tidak pernah kosong.
    |
    */

    'accepted'             => ':Attribute harus diterima.',
    'accepted_if'          => ':Attribute harus diterima ketika :other bernilai :value.',
    'active_url'           => ':Attribute bukan URL yang valid.',
    'after'                => ':Attribute harus berupa tanggal setelah :date.',
    'after_or_equal'       => ':Attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha'                => ':Attribute hanya boleh berisi huruf.',
    'alpha_dash'           => ':Attribute hanya boleh berisi huruf, angka, strip, dan garis bawah.',
    'alpha_num'            => ':Attribute hanya boleh berisi huruf dan angka.',
    'array'                => ':Attribute harus berupa array.',
    'ascii'                => ':Attribute hanya boleh berisi karakter alfanumerik dan simbol single-byte.',
    'before'               => ':Attribute harus berupa tanggal sebelum :date.',
    'before_or_equal'      => ':Attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between'              => [
        'array'   => ':Attribute harus memiliki antara :min sampai :max item.',
        'file'    => ':Attribute harus berukuran antara :min sampai :max kilobita.',
        'numeric' => ':Attribute harus bernilai antara :min sampai :max.',
        'string'  => ':Attribute harus terdiri dari :min sampai :max karakter.',
    ],
    'boolean'              => ':Attribute harus bernilai benar atau salah.',
    'can'                  => ':Attribute berisi nilai yang tidak diizinkan.',
    'confirmed'            => 'Konfirmasi :attribute tidak cocok.',
    'current_password'     => 'Password saat ini salah.',
    'date'                 => ':Attribute bukan tanggal yang valid.',
    'date_equals'          => ':Attribute harus berupa tanggal yang sama dengan :date.',
    'date_format'          => ':Attribute tidak cocok dengan format :format.',
    'decimal'              => ':Attribute harus memiliki :decimal angka desimal.',
    'declined'             => ':Attribute harus ditolak.',
    'declined_if'          => ':Attribute harus ditolak ketika :other bernilai :value.',
    'different'            => ':Attribute dan :other harus berbeda.',
    'digits'               => ':Attribute harus terdiri dari :digits digit.',
    'digits_between'       => ':Attribute harus terdiri dari :min sampai :max digit.',
    'dimensions'           => 'Dimensi gambar :attribute tidak valid.',
    'distinct'             => ':Attribute memiliki nilai yang duplikat.',
    'doesnt_end_with'      => ':Attribute tidak boleh diakhiri dengan salah satu dari: :values.',
    'doesnt_start_with'    => ':Attribute tidak boleh diawali dengan salah satu dari: :values.',
    'email'                => 'Format :attribute tidak valid.',
    'ends_with'            => ':Attribute harus diakhiri dengan salah satu dari: :values.',
    'enum'                 => ':Attribute yang dipilih tidak valid.',
    'exists'               => ':Attribute yang dipilih tidak valid.',
    'extensions'           => ':Attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file'                 => ':Attribute harus berupa berkas.',
    'filled'               => ':Attribute wajib diisi.',
    'gt'                   => [
        'array'   => ':Attribute harus memiliki lebih dari :value item.',
        'file'    => ':Attribute harus berukuran lebih dari :value kilobita.',
        'numeric' => ':Attribute harus bernilai lebih dari :value.',
        'string'  => ':Attribute harus terdiri dari lebih dari :value karakter.',
    ],
    'gte'                  => [
        'array'   => ':Attribute harus memiliki :value item atau lebih.',
        'file'    => ':Attribute harus berukuran minimal :value kilobita.',
        'numeric' => ':Attribute harus bernilai minimal :value.',
        'string'  => ':Attribute harus terdiri dari minimal :value karakter.',
    ],
    'hex_color'            => ':Attribute harus berupa warna heksadesimal yang valid.',
    'image'                => ':Attribute harus berupa gambar.',
    'in'                   => ':Attribute yang dipilih tidak valid.',
    'in_array'             => ':Attribute tidak ada di dalam :other.',
    'integer'              => ':Attribute harus berupa bilangan bulat.',
    'ip'                   => ':Attribute harus berupa alamat IP yang valid.',
    'ipv4'                 => ':Attribute harus berupa alamat IPv4 yang valid.',
    'ipv6'                 => ':Attribute harus berupa alamat IPv6 yang valid.',
    'json'                 => ':Attribute harus berupa string JSON yang valid.',
    'list'                 => ':Attribute harus berupa daftar.',
    'lowercase'            => ':Attribute harus berupa huruf kecil.',
    'lt'                   => [
        'array'   => ':Attribute harus memiliki kurang dari :value item.',
        'file'    => ':Attribute harus berukuran kurang dari :value kilobita.',
        'numeric' => ':Attribute harus bernilai kurang dari :value.',
        'string'  => ':Attribute harus terdiri dari kurang dari :value karakter.',
    ],
    'lte'                  => [
        'array'   => ':Attribute tidak boleh memiliki lebih dari :value item.',
        'file'    => ':Attribute harus berukuran maksimal :value kilobita.',
        'numeric' => ':Attribute harus bernilai maksimal :value.',
        'string'  => ':Attribute harus terdiri dari maksimal :value karakter.',
    ],
    'mac_address'          => ':Attribute harus berupa alamat MAC yang valid.',
    'max'                  => [
        'array'   => ':Attribute tidak boleh memiliki lebih dari :max item.',
        'file'    => 'Ukuran :attribute tidak boleh lebih dari :max kilobita.',
        'numeric' => ':Attribute tidak boleh lebih dari :max.',
        'string'  => ':Attribute tidak boleh lebih dari :max karakter.',
    ],
    'max_digits'           => ':Attribute tidak boleh lebih dari :max digit.',
    'mimes'                => ':Attribute harus berupa berkas bertipe: :values.',
    'mimetypes'            => ':Attribute harus berupa berkas bertipe: :values.',
    'min'                  => [
        'array'   => ':Attribute harus memiliki minimal :min item.',
        'file'    => 'Ukuran :attribute minimal :min kilobita.',
        'numeric' => ':Attribute minimal bernilai :min.',
        'string'  => ':Attribute minimal terdiri dari :min karakter.',
    ],
    'min_digits'           => ':Attribute harus terdiri dari minimal :min digit.',
    'missing'              => ':Attribute harus tidak ada.',
    'missing_if'           => ':Attribute harus tidak ada ketika :other bernilai :value.',
    'missing_unless'       => ':Attribute harus tidak ada kecuali :other bernilai :value.',
    'missing_with'         => ':Attribute harus tidak ada ketika :values ada.',
    'missing_with_all'     => ':Attribute harus tidak ada ketika :values ada.',
    'multiple_of'          => ':Attribute harus merupakan kelipatan dari :value.',
    'not_in'               => ':Attribute yang dipilih tidak valid.',
    'not_regex'            => 'Format :attribute tidak valid.',
    'numeric'              => ':Attribute harus berupa angka.',
    'password'             => [
        'letters'       => ':Attribute harus mengandung setidaknya satu huruf.',
        'mixed'         => ':Attribute harus mengandung setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers'       => ':Attribute harus mengandung setidaknya satu angka.',
        'symbols'       => ':Attribute harus mengandung setidaknya satu simbol.',
        'uncompromised' => ':Attribute yang diberikan muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present'              => ':Attribute harus ada.',
    'present_if'           => ':Attribute harus ada ketika :other bernilai :value.',
    'present_unless'       => ':Attribute harus ada kecuali :other bernilai :value.',
    'present_with'         => ':Attribute harus ada ketika :values ada.',
    'present_with_all'     => ':Attribute harus ada ketika :values ada.',
    'prohibited'           => ':Attribute tidak boleh diisi.',
    'prohibited_if'        => ':Attribute tidak boleh diisi ketika :other bernilai :value.',
    'prohibited_unless'    => ':Attribute tidak boleh diisi kecuali :other bernilai :values.',
    'prohibits'            => ':Attribute melarang :other untuk diisi.',
    'regex'                => 'Format :attribute tidak valid.',
    'required'             => ':Attribute wajib diisi.',
    'required_array_keys'  => ':Attribute harus memuat entri untuk: :values.',
    'required_if'          => ':Attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => ':Attribute wajib diisi ketika :other diterima.',
    'required_if_declined' => ':Attribute wajib diisi ketika :other ditolak.',
    'required_unless'      => ':Attribute wajib diisi kecuali :other bernilai :values.',
    'required_with'        => ':Attribute wajib diisi ketika :values ada.',
    'required_with_all'    => ':Attribute wajib diisi ketika :values ada.',
    'required_without'     => ':Attribute wajib diisi ketika :values tidak ada.',
    'required_without_all' => ':Attribute wajib diisi ketika tidak ada satu pun dari :values.',
    'same'                 => ':Attribute dan :other harus sama.',
    'size'                 => [
        'array'   => ':Attribute harus mengandung :size item.',
        'file'    => 'Ukuran :attribute harus :size kilobita.',
        'numeric' => ':Attribute harus bernilai :size.',
        'string'  => ':Attribute harus terdiri dari :size karakter.',
    ],
    'starts_with'          => ':Attribute harus diawali dengan salah satu dari: :values.',
    'string'               => ':Attribute harus berupa teks.',
    'timezone'             => ':Attribute harus berupa zona waktu yang valid.',
    'unique'               => ':Attribute sudah digunakan.',
    'uploaded'             => ':Attribute gagal diunggah. Periksa ukuran berkas dan coba lagi.',
    'uppercase'            => ':Attribute harus berupa huruf besar.',
    'url'                  => 'Format :attribute tidak valid.',
    'ulid'                 => ':Attribute harus berupa ULID yang valid.',
    'uuid'                 => ':Attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Pesan Kustom per Atribut
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nama Atribut yang Ramah Pengguna
    |--------------------------------------------------------------------------
    |
    | Mengganti nama field teknis (mis. "category_id") menjadi nama yang
    | dipahami pengguna di pesan error.
    |
    */

    'attributes' => [
        'name'                  => 'nama',
        'email'                 => 'alamat email',
        'password'              => 'password',
        'password_confirmation' => 'konfirmasi password',
        'current_password'      => 'password saat ini',
        'token'                 => 'token',
        'nis_nip'               => 'NIS/NIP',
        'contact'               => 'nomor WhatsApp',
        'instagram'             => 'akun Instagram',
        'title'                 => 'judul',
        'description'           => 'deskripsi',
        'category_id'           => 'kategori',
        'image'                 => 'gambar',
        'file_pdf'              => 'berkas PDF',
        'photo'                 => 'foto',
        'achieved_at'           => 'tanggal prestasi',
    ],

];
