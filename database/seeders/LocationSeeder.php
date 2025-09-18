<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            [
                'location' => 'Head Office Jakarta',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'description' => 'Main headquarters building',
                'address' => 'Jl. Sudirman No. 1, Jakarta Pusat',
                'status' => true,
            ],
            [
                'location' => 'Branch Office Surabaya',
                'latitude' => -7.2504,
                'longitude' => 112.7688,
                'description' => 'Eastern Java regional office',
                'address' => 'Jl. Tunjungan No. 101, Surabaya',
                'status' => true,
            ],
            [
                'location' => 'Branch Office Medan',
                'latitude' => 3.5952,
                'longitude' => 98.6722,
                'description' => 'North Sumatra regional office',
                'address' => 'Jl. Asia No. 1, Medan',
                'status' => true,
            ],
            [
                'location' => 'Branch Office Makassar',
                'latitude' => -5.1477,
                'longitude' => 119.4327,
                'description' => 'Eastern Indonesia regional office',
                'address' => 'Jl. Veteran No. 1, Makassar',
                'status' => true,
            ],
            [
                'location' => 'Data Center Bandung',
                'latitude' => -6.9175,
                'longitude' => 107.6191,
                'description' => 'Primary data center facility',
                'address' => 'Jl. Dago No. 50, Bandung',
                'status' => true,
            ],
            [
                'location' => 'Warehouse Tangerang',
                'latitude' => -6.1781,
                'longitude' => 106.6297,
                'description' => 'Main storage facility',
                'address' => 'Jl. BSD Raya No. 1, Tangerang',
                'status' => true,
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
