<?php

namespace App\Controllers;

// cara konek pake model
use App\Models\OrangModel;

class Orang extends BaseController
{
    protected $orangModel;
    public function __construct()
    {
        $this->orangModel = new orangModel();
    }

    public function index()
    {
        $currentPage = $this->request->getVar('page_orang') ? $this->request->getVar('page_orang') : 1;

        $keyword = $this->request->getVar('keyword');
        if ($keyword) {
            $orang = $this->orangModel->search($keyword);
        } else {
            $orang = $this->orangModel;
        }

        // if ($keyword) {
        //     $_SESSION["keyword"] = $keyword;
        //     $orang = $this->orangModel->search($keyword);
        // } else {
        //     if (isset($_SESSION['keyword'])) {
        //         $keyword = $_SESSION['keyword'];
        //         $orang = $this->orangModel->search($keyword);
        //     } else {
        //         $orang = $this->orangModel;
        //     }
        // }

        $data = [
            'title' => 'Daftar Orang',
            // 'orang' => $this->orangModel->findAll()
            // 'orang' => $this->orangModel->paginate(7, 'orang'),
            'orang' => $orang->paginate(7, 'orang'),
            'pager' => $this->orangModel->pager,
            'currentPage' => $currentPage
        ];

        return view('orang/index', $data);
    }
    // public function clearFilter()
    // {
    //     unset($_SESSION['keyword']);
    //     return redirect()->to('/orang');
    // }
}