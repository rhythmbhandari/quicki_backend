<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Modules\Models\Booking;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Booking::create([
            'trip_id' => '#erjweroiw',
            "passenger_number" => 2,
            'user_id' => 2,
            'status' => 'pending',
            "vehicle_type_id" => 1,
            // "voucher" => "#9816810976C",
            "distance" => 12,
            "price" => 160,
            "duration" => 20,
            "stoppage" => [
                [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ],
            "location" => [
                "origin" => [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                "destination" => [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ]
        ]);

        Booking::create([
            'trip_id' => '#erjweroiw',
            "passenger_number" => 2,
            'user_id' => 2,
            'status' => 'pending',
            "vehicle_type_id" => 1,
            // "voucher" => "#9816810976C",
            "distance" => 12,
            "price" => 160,
            "duration" => 20,
            "stoppage" => [
                [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ],
            "location" => [
                "origin" => [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                "destination" => [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ]
        ]);

        Booking::create([
            'trip_id' => '#erjweroiw',
            "passenger_number" => 2,
            'user_id' => 2,
            'status' => 'pending',
            "vehicle_type_id" => 2,
            // "voucher" => "#9816810976C",
            "distance" => 12,
            "price" => 160,
            "duration" => 20,
            "stoppage" => [
                [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ],
            "location" => [
                "origin" => [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                "destination" => [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ]
        ]);

        Booking::create([
            'trip_id' => '#erjweroiw',
            "passenger_number" => 2,
            'user_id' => 2,
            'status' => 'pending',
            "vehicle_type_id" => 2,
            // "voucher" => "#9816810976C",
            "distance" => 12,
            "price" => 160,
            "duration" => 20,
            "stoppage" => [
                [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ],
            "location" => [
                "origin" => [
                    "name" => "Jaulakhel, Lalitpur",
                    "latitude" => 27.672827,
                    "longitude" => 85.313665
                ],
                "destination" => [
                    "name" => "Kupondol, Kathmandu",
                    "latitude" => 27.689358,
                    "longitude" => 85.316847
                ]
            ]
        ]);
    }
}
