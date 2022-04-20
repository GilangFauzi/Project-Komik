<?php

namespace App\Database\Seeds;

use CodeIgniter\I18n\Time;

use CodeIgniter\Database\Seeder;

class OrangSeeder extends Seeder
{
    public function run()
    {
        // $data = [
        //     [
        //         'nama'          => 'Mario',
        //         'alamat'        => 'Jl. Mutiara 4',
        //         'created_at'    => Time::now(),
        //         'update_at'    => Time::now()
        //     ],
        //     [
        //         'nama'          => 'Jamet',
        //         'alamat'        => 'Jl. Mutiara 4',
        //         'created_at'    => Time::now(),
        //         'update_at'    => Time::now()
        //     ],
        //     [
        //         'nama'          => 'Kuri',
        //         'alamat'        => 'Jl. Mutiara 4',
        //         'created_at'    => Time::now(),
        //         'update_at'    => Time::now()
        //     ]
        // ];
        // biar nama dan alamt di dalam db nya random
        // kalau yang di dalam create('id_ID') itu biar orang indonesia semua. liat di localization faker
        $faker = \Faker\Factory::create('id_ID');
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'nama'          => $faker->name,
                'alamat'        => $faker->address,
                'created_at'    => Time::createFromTimestamp($faker->unixTime()),
                'update_at'    => Time::now()
            ];
            $this->db->table('Orang')->insert($data);
        }
        // Simple Queries
        // $this->db->query("INSERT INTO orang (nama, alamat, created_at, update_at) VALUES(:nama:, :alamat:, :created_at:, :update_at:)", $data);

        // Using Query Builder
        // $this->db->table('Orang')->insertBatch($data);
    }
}