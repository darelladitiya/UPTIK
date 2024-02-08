<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\HardwareLab13;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\HardwareLab10;
use App\Models\HardwareLab9;
use App\Models\HardwareLab11;
use App\Models\HardwareLab12;
use App\Models\HardwareLab14;
use App\Models\HardwareLab15;
use App\Models\HardwareLab16;
use App\Models\RuanganModel;

class HardwareController extends BaseController
{
    public function detail($id_ruangan)
    {

        // Tentukan model yang sesuai dengan ruangan
        $model = 'App\Models\HardwareLab' . $id_ruangan;

        // Buat instance model yang sesuai
        $hardwareModel = new $model;

        $fasilitas = $hardwareModel->paginate(10, 'lab2');
        $data = [
            'pageTitle' => 'Lab Hardware',
            'lab2' => $fasilitas,
            'pager' => $hardwareModel->pager,
            'modelNumber' => $id_ruangan
        ];
        return view('hardware/lab_2', $data);
    }

    //Crud lab 
    public function delete_data_lab($modelNumber, $id_pc)
    {
        switch ($modelNumber) {
            case 9:
                $model = new HardwareLab9();
                break;
            case 10:
                $model = new HardwareLab10();
                break;
            case 11:
                $model = new HardwareLab11();
                break;
            case 12:
                $model = new HardwareLab12();
                break;
            case 13:
                $model = new HardwareLab13();
                break;
            case 14:
                $model = new HardwareLab14();
                break;
            case 15:
                $model = new HardwareLab15();
                break;
            case 16:
                $model = new HardwareLab16();
                break;
            // Tambahkan case lainnya sesuai dengan model yang Anda miliki
            default:
                // Jika nomor model tidak cocok, kembalikan dengan pesan kesalahan
                return redirect()->back()->with('error', 'Nomor model tidak valid.');
        }

        // Hapus data menggunakan model yang sesuai
        $model->delete(['id_pc' => $id_pc]);

        // Redirect ke halaman pengolahan data setelah penghapusan berhasil
        return redirect()->to('admin/pengolahan_lab');
    }



    public function edit_lab($modelNumber, $id_pc)
    {
        switch ($modelNumber) {
            case 9:
                $model = new HardwareLab9();
                break;
            case 10:
                $model = new HardwareLab10();
                break;
            case 11:
                $model = new HardwareLab11();
                break;
            case 12:
                $model = new HardwareLab12();
                break;
            case 13:
                $model = new HardwareLab13();
                break;
            case 14:
                $model = new HardwareLab14();
                break;
            case 15:
                $model = new HardwareLab15();
                break;
            case 16:
                $model = new HardwareLab16();
                break;
            // Tambahkan case lainnya sesuai dengan model yang Anda miliki
            default:
                // Jika nomor model tidak cocok, kembalikan dengan pesan kesalahan
                return redirect()->back()->with('error', 'Nomor model tidak valid.');
        }

        $data = [
            'pageTitle' => 'Edit data lab',
            'modelNumber' => $modelNumber,
            'hardware' => $model->where('id_pc', $id_pc)->first()

        ];

        return view('hardware/edit_data_lab9', $data);
    }

    public function update_lab($id_pc)
    {
        $modelNumber = $this->request->getPost('modelNumber');

        switch ($modelNumber) {
            case 9:
                $model = new HardwareLab9();
                break;
            case 10:
                $model = new HardwareLab10();
                break;
            case 11:
                $model = new HardwareLab11();
                break;
            case 12:
                $model = new HardwareLab12();
                break;
            case 13:
                $model = new HardwareLab13();
                break;
            case 14:
                $model = new HardwareLab14();
                break;
            case 15:
                $model = new HardwareLab15();
                break;
            case 16:
                $model = new HardwareLab16();
                break;
            // Tambahkan case lainnya sesuai dengan model yang Anda miliki
            default:
                // Jika nomor model tidak cocok, kembalikan dengan pesan kesalahan
                return redirect()->back()->with('error', 'Nomor model tidak valid.');
        }
        $data = $this->request->getPost();
        $model->update($id_pc, $data);
        return redirect()->to('admin/pengolahan_lab');
    }

    public function add_data_lab($modelNumber)
    {
        // $softwareModel = new HardwareLab10();

        // Mengirimkan nomor model ke view untuk digunakan dalam formulir
        return view('hardware/add_data_lab', ['modelNumber' => $modelNumber]);
    }

    public function save_data_lab()
    {
        // Mendapatkan nomor model yang dipilih dari input tersembunyi
        $modelNumber = $this->request->getPost('modelNumber');

        switch ($modelNumber) {
            case 9:
                $model = new HardwareLab9();
                break;
            case 10:
                $model = new HardwareLab10();
                break;
            case 11:
                $model = new HardwareLab11();
                break;
            case 12:
                $model = new HardwareLab12();
                break;
            case 13:
                $model = new HardwareLab13();
                break;
            case 14:
                $model = new HardwareLab14();
                break;
            case 15:
                $model = new HardwareLab15();
                break;
            case 16:
                $model = new HardwareLab16();
                break;
            // Tambahkan case lainnya sesuai dengan model yang Anda miliki
            default:
                // Jika nomor model tidak cocok, kembalikan dengan pesan kesalahan
                return redirect()->back()->with('error', 'Nomor model tidak valid.');
        }

        $model->insert($this->request->getPost());
        // Lakukan validasi dan penyimpanan data berdasarkan model yang dipilih
        // ...
        return redirect()->to('admin/pengolahan_lab');
    }

}