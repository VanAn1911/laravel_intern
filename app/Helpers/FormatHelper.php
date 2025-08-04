<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Str;

class FormatHelper
{
    public static function datetime($datetime)
    {
        return $datetime ? Carbon::parse($datetime)->format('Y-m-d H:i') : null;
    }

    public static function date($date)
    {
        return $date ? Carbon::parse($date)->format('d/m/Y') : null;
    }

    public static function format($dateTime, $format = 'Y-m-d H:i')
    {
        return $dateTime ? Carbon::parse($dateTime)->format($format) : null;
    }

    public static function slugify($string)
    {
        return Str::slug($string, '-');
    }
}
