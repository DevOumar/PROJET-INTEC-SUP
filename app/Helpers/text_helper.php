<?php

if (!function_exists('mb_ucwords')) {
    function mb_ucwords($string) {
        $words = explode(' ', $string);
        $capitalizedWords = array_map(function($word) {
            return mb_strtoupper(mb_substr($word, 0, 1), 'UTF-8') . mb_strtolower(mb_substr($word, 1), 'UTF-8');
        }, $words);

        return implode(' ', $capitalizedWords);
    }
}
