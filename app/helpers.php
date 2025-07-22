<?php
use Illuminate\Support\Str;
use Carbon\Carbon;

//định dạng ngày tháng, giờ
if (!function_exists('format_datetime')) {
    function format_datetime($datetime)
    {
        return $datetime ? Carbon::parse($datetime)->format('Y-m-d\TH:i') : null;
    }
}

//định dạng ngày tháng
if (!function_exists('format_date')) {
    function format_date($date)
    {
        return $date ? Carbon::parse($date)->format('d/m/Y') : null;
    }
}

//định dạng slug
if (!function_exists('slugify')) {
    function slugify($string)
    {
        return Str::slug($string, '-');
    }
}