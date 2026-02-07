<?php

namespace App\Helpers;

use Carbon\Carbon;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function gdrive()
    {
        return Storage::disk('google');
    }

    public static function date($data, $format)
    {
        return Carbon::parse($data)->format($format);
    }

    public static function number($data)
    {
        return number_format($data, 0, '', '.');
    }

    public static function dateIndo($tanggal)
    {
        $bulan = [
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];
        //format tanggal 2022-10-20
        $pecahkan = explode('-', $tanggal);

        return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
    }

    public static function delMask($separator, $data)
    {
        return implode('', explode($separator, $data));
    }

    public static function bytesToHuman($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public static function monthRomawi($month)
    {
        $monthArr = [1 => "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII"];
        return $monthArr[$month];
    }

    public static function getNoUrut($no_surat)
    {
        $nomor = explode('/', $no_surat);
        $urut = explode('.', $nomor[0]);
        return (int)$urut[1];
    }

    public static function getFilename($file)
    {
        $name = explode('/', $file);
        end($name);         // move the internal pointer to the end of the pecah
        $key = key($name);
        return $name[$key];
    }

}
