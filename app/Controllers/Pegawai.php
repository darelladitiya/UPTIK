<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PegawaiModel;

class Pegawai extends BaseController
{
    public function index()
    {

        $pegawaiModel = new PegawaiModel();
        $pegawai = $pegawaiModel->paginate(10, 'pegawai');
        $data = [
            'pageTitle' => 'Pegawai UPA-TIK',
            'pegawai' => $pegawai,
            'pager' => $pegawaiModel->pager
        ];

        return view('Pengolahan_data/pegawai', $data);
    }

    public function add_data_pegawai()
    {
        $pegawaiModel = new PegawaiModel();

        return view('Pengolahan_data/add_data_pegawai', ['pageTitle' => 'Tambah Data Pegawai']);
    }

    public function save_pegawai()
    {
        $pegawaiModel = new PegawaiModel();
        $rules = $this->validate([
            'nama' => [
                'rules' => 'required|max_length[60]',
                'errors' => [
                    'required' => ' nama pegawai diperlukan',
                    'max_length' => 'nama terlalu panjang',
                ]
            ],
            'nip' => [
                'rules' => 'required|min_length[1]',
                'errors' => [
                    'required' => 'keterangan diperlukan, gunakan simbol "-" jika tidak ada',
                    'min_length' => 'nip terlalu sedikit'
                ]
            ],
        ]);

        if (!$rules) {
            return view('pengolahan_data/add_data_pegawai', [
                'pageTitle' => 'Tambah Data Pegawai',
                'pegawai' => $pegawaiModel,
                'validation' => $this->validator
            ]);
        } else {
            $pegawaiModel->insert($this->request->getPost());
            return redirect()->to('admin/pegawai')->with('success', 'Data berhasil ditambahkan.');
        }
    }

    public function delete_pegawai($id)
    {
        $pegawaiModel = new PegawaiModel();

        $pegawaiModel->delete(['id' => $id]);

        // Redirect ke halaman sebelumnya
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function edit_pegawai($id)
    {
        $pegawaiModel = new PegawaiModel();

        $data = [
            'pageTitle' => 'Edit Data Pegawai',
            'pegawai' => $pegawaiModel->where('id', $id)->first()
        ];

        return view('pengolahan_data/edit_data_pegawai', $data);
    }

    public function update_pegawai($id)
    {
        $pegawaiModel = new PegawaiModel();
        $rules = $this->validate([
            'nama' => [
                'rules' => 'required|max_length[60]',
                'errors' => [
                    'required' => ' nama pegawai diperlukan',
                    'max_length' => 'nama terlalu panjang',
                ]
            ],
            'nip' => [
                'rules' => 'required|min_length[1]',
                'errors' => [
                    'required' => 'keterangan diperlukan, gunakan simbol "-" jika tidak ada',
                    'min_length' => 'nip terlalu sedikit'
                ]
            ],
        ]);
        if (!$rules) {
            return view('pengolahan_data/edit_data_pegawai', [
                'pageTitle' => 'Edit Data Pegawai',
                'pegawai' => $pegawaiModel->where('id', $id)->first(),
                'validation' => $this->validator
            ]);
        } else {
            $data = $this->request->getPost();
            $pegawaiModel->update($id, $data);
            return redirect()->to('admin/pegawai')->with('success', 'Data pegawai berhasil diperbarui.');
        }
    }
}
