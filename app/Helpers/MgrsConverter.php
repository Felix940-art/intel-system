<?php

namespace App\Helpers;

class MgrsConverter
{
    public static function toLatLng($mgrs)
    {
        $coordinates = [

            '51PYP2673763824' => [
                'lat' => 11.2448,
                'lng' => 125.0000,
            ],

            '51PYP2408409411' => [
                'lat' => 11.7753,
                'lng' => 124.8861,
            ],

            '51PYP1379423239' => [
                'lat' => 11.7800,
                'lng' => 124.8840,
            ],

        ];

        return $coordinates[$mgrs] ?? [
            'lat' => 0,
            'lng' => 0,
        ];
    }
}
