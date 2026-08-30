<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Validation\ValidationException;

/**
 * Validasi isi file yang sesungguhnya (bukan cuma ekstensi/MIME dari header
 * request) menggunakan finfo, agar file yang di-rename agar lolos aturan
 * validasi Laravel (mis. file .exe diganti nama jadi .jpg) tetap ditolak.
 *
 * Dipakai bersama oleh PortfolioController, AchievementController, dan
 * ProfileController — sebelumnya method identik ini diduplikasi tiga kali.
 * Perilaku TIDAK berubah sama sekali dari versi sebelumnya, murni ekstraksi.
 */
trait ValidatesFileContent
{
    /**
     * Validate actual file content using finfo (server-side MIME check).
     */
    private function validateFileContent($file, array $allowedMimes, string $field): void
    {
        if (!$file || !$file->isValid()) {
            return;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file->getRealPath());

        if (!in_array($mime, $allowedMimes, true)) {
            throw ValidationException::withMessages([
                $field => "File tidak valid. Tipe file yang diizinkan: " . implode(', ', $allowedMimes),
            ]);
        }
    }
}
