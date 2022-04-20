<?php

namespace App\Controllers;

class Pages extends BaseController
{
    public function index()
    {
        // test buat random nama dan alamat
        // $faker = \Faker\Factory::create();
        // dd($faker->address);
        $data = [
            'title' => 'Home | GilangFauzi'
        ];
        // echo view('layout/header', $data);
        return view('pages/home', $data);
        // echo view('layout/footer');
    }

    public function about()
    {
        // bisa aja gini 
        // return view('pages/about');
        // return kalau cuma 1 function atau method cuma bisa 1. jadi pake echo aja
        $data = [
            'title' => 'About Me'
        ];
        // echo view('layout/header', $data);
        return view('pages/about', $data);
        // echo view('layout/footer');
    }

    public function contact()
    {
        $data = [
            'title' => 'Contact Us',

            'alamat' => [
                [
                    'tipe' => 'Rumah',
                    'alamat' => 'Jl. Mutiara IV',
                    'kota' => 'Bogor'
                ],

                [
                    'tipe' => 'Kantor',
                    'alamat' => 'Jl. Berlian V',
                    'kota' => 'Bogor'
                ]
            ]
        ];

        return view('pages/contact', $data);
    }
}