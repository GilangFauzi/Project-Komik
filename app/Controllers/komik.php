<?php

namespace App\Controllers;

// cara konek pake model
use App\Models\KomikModel;

class Komik extends BaseController
{
    protected $komikModel;
    public function __construct()
    {
        $this->komikModel = new KomikModel();
    }

    public function index()
    {
        // $komik = $this->komikModel->findAll();
        $data = [
            'title' => 'Daftar Komik',
            'komik' => $this->komikModel->getKomik()
        ];

        // cara konek db tanpa model

        // $db = \Config\Database::connect();
        // $komik = $db->query('SELECT * FROM komik');
        // foreach ($komik->getResultArray() as $row) {
        //     d($row);
        // }

        // cara konek pake model
        // $komikModel = new \App\Models\KomikModel();

        // $komikModel = new KomikModel();



        return view('komik/index', $data);
    }

    public function detail($slug)
    {

        $data = [
            'title' => 'Detail Komik',
            'komik' => $this->komikModel->getKomik($slug)
        ];

        // jika komik tidak ada di tabel

        if (empty($data['komik'])) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Judul Komik ' . $slug . ' Tidak di Temukan.');
        }
        return view('komik/detail', $data);
    }

    public function create()
    {
        // session(); di pindahin ke baseController
        $data = [
            'title' => 'Form Tambah Data Komik',
            'validation' => \Config\Services::validation()
        ];
        return view('komik/create', $data);
    }

    public function save()
    {
        // validasi input
        if (!$this->validate([
            'judul' => [
                'rules' => 'required|is_unique[komik.judul]',
                'errors' => [
                    'required' => '{field} komik tidak boleh kosong.',
                    'is_unique' => '{field} komik sudah ada'
                ]
            ],
            'sampul' => [
                // kalau mau izinin user bisa simpan data tnpa upload gambar, apus 'uploaded[sampul]'
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'Pilih gambar sampul terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]
        ])) {
            // $validation = \Config\Services::validation();
            // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
            return redirect()->to('/komik/create')->withInput();
        }

        // ambil gambar
        $fileSampul = $this->request->getFile('sampul');
        // apakah tidak ada gambar yang di upload
        if ($fileSampul->getError() == 4) {
            $namaSampul = 'default.jpg';
        } else {

            // generate nama sampul random
            $namaSampul = $fileSampul->getRandomName();

            // pindahkan file
            // kalau mau nama sampul random tambahin 1 parameter, jangan cuma 'img'
            $fileSampul->move('img', $namaSampul);

            // ambil nama file
            // $namaSampul = $fileSampul->getName();
            // kalau mau nama sampulnya random gini
        }

        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            // 'sampul' => $this->request->getVar('sampul')
            // kalau udah ada upload jadi sytax sampul di atas ganti kek gini
            'sampul' => $namaSampul
        ]);

        session()->setFlashdata('pesan', 'Data Berhasil ditambahkan');

        return redirect()->to('/komik');
    }

    public function delete($id)
    {

        // cari gambar berdasarkan id
        $komik = $this->komikModel->find($id);
        // cek jika file gambarnya default.jpg
        if ($komik['sampul'] != 'default.jpg') {
            // hapus gambar
            unlink('img/' . $komik['sampul']);
        }


        $this->komikModel->delete($id);
        session()->setFlashdata('pesan', 'Data Berhasil di Hapus');
        return redirect()->to('/komik');
    }

    public function edit($slug)
    {
        $data = [
            'title' => 'Form Ubah Data Komik',
            'validation' => \Config\Services::validation(),
            'komik' => $this->komikModel->getKomik($slug)
        ];
        return view('komik/edit', $data);
    }

    public function update($id)
    {
        // cek judul
        $komikLama = $this->komikModel->getKomik($this->request->getVar('slug'));
        if ($komikLama['judul'] == $this->request->getVar('judul')) {
            $rule_judul = 'required';
        } else {
            $rule_judul = 'required|is_unique[komik.judul]';
        }
        if (!$this->validate([
            'judul' => [
                'rules' => $rule_judul,
                'errors' => [
                    'required' => '{field} komik harus di isi.',
                    'is_unique' => '{field} komik sudah ada'
                ]
            ],
            'sampul' => [
                // kalau mau izinin user bisa simpan data tnpa upload gambar, apus 'uploaded[sampul]'
                'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    // 'uploaded' => 'Pilih gambar sampul terlebih dahulu',
                    'max_size' => 'Ukuran gambar terlalu besar',
                    'is_image' => 'Yang anda pilih bukan gambar',
                    'mime_in' => 'Yang anda pilih bukan gambar'
                ]
            ]
        ])) {

            return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
        }
        $fileSampul = $this->request->getFile('sampul');

        // cek biar gambar sampul lama ga ke hapus
        // if ($this->request->getVar('sampulLama') != 'default.jpg') {
        //     unlink('img/' . $this->request->getVar('sampulLama'));
        // }

        // cek gambar, apakah tetap gambar lama
        if ($fileSampul->getError() == 4) {
            $namaSampul = $this->request->getVar('sampulLama');
        } else {
            // generate nama file random
            $namaSampul = $fileSampul->getRandomName();
            // pindahkan gambar
            $fileSampul->move('img', $namaSampul);
            // hapus file lama dan periksa apakah yang dihapus gambar default atau bukan
            if ($this->request->getVar('sampulLama') != 'default.jpg') {
                unlink('img/' . $this->request->getVar('sampulLama'));
                // unlink('img/' . $this->request->getVar('sampulLama'));
            }
        }



        $slug = url_title($this->request->getVar('judul'), '-', true);
        $this->komikModel->save([
            'id' => $id,
            'judul' => $this->request->getVar('judul'),
            'slug' => $slug,
            'penulis' => $this->request->getVar('penulis'),
            'penerbit' => $this->request->getVar('penerbit'),
            // 'sampul' => $this->request->getVar('sampul')
            'sampul' => $namaSampul
        ]);
        session()->setFlashdata('pesan', 'Data Berhasil di Ubah.');
        return redirect()->to('/komik');
    }
}