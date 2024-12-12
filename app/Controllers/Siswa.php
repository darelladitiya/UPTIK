<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\SiswaModel;

class Siswa extends BaseController
{
    public function index()
    {
        $siswaModel = new SiswaModel();
        $siswa = $siswaModel->paginate(10, 'siswa');
        $data = [
            'pageTitle' => 'Siswa PKL',
            'siswa' => $siswa,
            'pager' => $siswaModel->pager
        ];

        return view('Pengolahan_data/siswa', $data);
    }

    public function add_data_siswa()
    {
        $siswa = new SiswaModel;
        return view('Pengolahan_data/add_data_siswa', ['pageTitle' => 'Tambah Data Siswa']);
    }

    public function save_data_siswa()
    {
        $SiswaModel = new SiswaModel;
        $rules = $this->validate([
            'nama_lengkap' => [
                'rules' => 'required|max_length[60]',
                'errors' => [
                    'required' => ' nama siswa diperlukan',
                    'max_length[50]' => 'nama terlalu panjang',
                ]
            ],
            'jenis_kelamin' => [
                'rules' => 'required|max_length[1]',
                'errors' => [
                    'required' => 'keterangan diperlukan',
                    'max_length' => 'cukup satu huruf'
                ]
            ],
            'juruusan_pkl' => [
                'rules' => 'required|min_length[2]',
                'errors' => [
                    'required' => 'jurusan diperlukan',
                    'min_length' => 'terlalu pendek'
                ]
            ],
            'asal_sekolah' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'asal sekolah diperlukan',
                    'min_length' => 'terlalu pendek'
                ]
            ]
        ]);

        if (!$rules) {
            return view('pengolahan_data/add_data_siswa', [
                'pageTitle' => 'Tambah Data Siswa',
                'siswa' => $SiswaModel,
                'validation' => $this->validator
            ]);
        } else {
            $SiswaModel->insert($this->request->getPost());
            return redirect()->to('admin/siswa')->with('success', 'Data berhasil disimpan !');
        }
    }

    public function delete_siswa($id)
    {
        $fasilitas = new SiswaModel();
        $fasilitas->delete(['id' => $id]);
        // Redirect ke halaman sebelumnya
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data pegawai berhasil dihapus.');
    }

    public function edit_siswa($id)
    {
        $siswaModel = new SiswaModel;
        $data = [
            'pageTitle' => 'Edit Data Siswa',
            'siswa' => $siswaModel->where('id', $id)->first()
        ];

        return view('pengolahan_data/edit_data_siswa', $data);
    }

    public function update_siswa($id)
    {
        $ruanganModel = new SiswaModel;
        $rules = $this->validate([
            'nama_lengkap' => [
                'rules' => 'required|max_length[60]',
                'errors' => [
                    'required' => ' nama siswa diperlukan',
                    'max_length[40]' => 'nama terlalu panjang',
                ]
            ],
            'jenis_kelamin' => [
                'rules' => 'required|max_length[2]',
                'errors' => [
                    'required' => 'jenis kelamin diperlukan',
                    'max_length' => 'cukup satu huruf, pastikan tidak ada spasi di belakang huruf'
                ]
            ],
            'juruusan_pkl' => [
                'rules' => 'required|min_length[2]',
                'errors' => [
                    'required' => 'jurusan diperlukan',
                    'min_length' => 'terlalu pendek'
                ]
            ],
            'asal_sekolah' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'asal sekolah diperlukan',
                    'min_length' => 'terlalu pendek'
                ]
            ]
        ]);

        if (!$rules) {
            return view('pengolahan_data/edit_data_siswa', [
                'pageTitle' => 'Edit Data Siswa',
                'siswa' => $ruanganModel->where('id', $id)->first(),
                'validation' => $this->validator
            ]);
        } else {
            $data = $this->request->getPost();
            $ruanganModel->update($id, $data);
            return redirect()->to('admin/siswa')->with('success', 'Data siswa berhasil diperbarui.');
        }
    }
}
