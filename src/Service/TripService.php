<?php

namespace App\Service;

final class TripService
{
    public function getFeatures(): array
    {
        $features = [
            [
                'title' => 'Transport Rapid 🚀',
                'description' => '0 incidente rutiere in peste 5 ani de activitate.',
            ],
            [
                'title' => 'Siguranță ✅',
                'description' => 'Flexibilitatea programarii online. Ce mai astepti?',
            ],
            [
                'title' => 'Prețuri Bune 💸',
                'description' => 'Oferim cele mai competitive prețuri pe piață.',
            ],
        ];

        return $features;
    }

    public function getTrips(): array
    {
        $trips = [
            [
                'departure' => 'Roman',
                'arrival' => 'Bacau',
                'duration' => '1h 30m',
                'price' => '50 lei',
            ],
            [
                'departure' => 'Bacau',
                'arrival' => 'Focsani',
                'duration' => '2h',
                'price' => '70 lei',
            ],
            [
                'departure' => 'Focsani',
                'arrival' => 'Buzau',
                'duration' => '1h 45m',
                'price' => '60 lei',
            ],
            [
                'departure' => 'Buzau',
                'arrival' => 'Bucuresti',
                'duration' => '2h 30m',
                'price' => '80 lei',
            ],
        ];

        return $trips;
    }
}