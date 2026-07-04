<?php

if(!function_exists('validate_logo_image')){
    function validate_logo_image($file)
    {
        _validate_basic_image($file);

        $info   =   getimagesize($file['tmp_name']);
        $width  =   $info[0];
        $height =   $info[1];

        if ($width !== $height || $width < 200 || $height < 200) {
            return throwResponseInternalServerError("Panjang dan lebar harus 200 pixel dan gambar harus berbentuk persegi.");
        }

        return true;
    }
}

if(!function_exists('validate_image')){
    function validate_image($file, $maxSize = 500000)
    {
        _validate_basic_image($file, $maxSize);
        return true;
    }
}

if(!function_exists('_validate_basic_image')){
    function _validate_basic_image($file, $maxSize = 500000)
    {
        $allowed = ['image/jpeg', 'image/jpg', 'image/png'];

        if (!in_array($file['type'], $allowed) || $file['size'] > $maxSize) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Tipe berkas tidak diizinkan ({$file['type']}) atau ukuran berkas terlalu besar ({$file['size']})");
        }

        if ($file['error'] > 0) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Berkas rusak");
        }
    }
}

if(!function_exists('validate_excel')){
    function validate_excel($file)
    {
        $allowed = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ];

        if (!in_array($file['type'], $allowed) || $file['size'] > 800000) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Tipe berkas tidak diizinkan ({$file['type']}) atau ukuran berkas terlalu besar ({$file['size']})");
        }

        if ($file['error'] > 0) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Berkas rusak");
        }

        return true;
    }
}

if(!function_exists('validate_pdf')){
    function validate_pdf($file, $maxSize = 1000000)
    {
        $allowed = ['application/pdf'];

        if (!in_array($file['type'], $allowed) || $file['size'] > $maxSize) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Tipe berkas tidak diizinkan ({$file['type']}) atau ukuran berkas terlalu besar ({$file['size']})");
        }

        if ($file['error'] > 0) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Berkas rusak");
        }

        return true;
    }
}

if(!function_exists('validate_mix_file')){
    function validate_mix_file($file)
    {
        $allowed = [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/pdf',
            'image/jpeg',
            'image/jpg',
            'image/png'
        ];

        if (!in_array($file['type'], $allowed) || $file['size'] > 800000) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Tipe berkas tidak diizinkan ({$file['type']}) atau ukuran berkas terlalu besar ({$file['size']})");
        }

        if ($file['error'] > 0) {
            return throwResponseInternalServerError("Gagal mengunggah berkas. Berkas rusak");
        }

        return true;
    }
}