<?php

namespace App\Helpers;

class CommonHelper
{
    public static function convertAdTypeToText(string $string) {
        return match ($string) {
            'rent' => 'Kiadó',
            'sell' => 'Eladó'
        };
    }
}
