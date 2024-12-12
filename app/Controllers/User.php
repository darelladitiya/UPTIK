<?php

namespace App\Controllers;

use App\Models\userModel;
use App\Models\prodiModel;
use App\Models\kritikModel;
use App\Libraries\Hash;

class User extends BaseController
{
    public function index()
    {
        $usrModel = new userModel();
        $user = $usrModel->joinProdi()->paginate(10, 'user');
        $data = [
            'user' => $user,
            'pageTitle' => "User",
            'pager' => $usrModel->pager,
            'status' => $usrModel->getStatus()
        ];



        return view('users/user', $data);
    }

    public function add_data_user()
    {
        $userModel = new userModel;
        $prodiModel = new prodiModel();

        $data = [
            'pageTitle' => 'Tambah Data User',
            'user' => $userModel->find('id_prodi'),
            'prodi' => $prodiModel->findAll()

        ];

        return view('users/add_data_user', $data);
    }

    public function save_data_user()
    {
        $userModel = new userModel();
        $prodiModel = new prodiModel();


        $rules = $this->validate([
            'nama_user' => [
                'rules' => 'required|max_length[40]',
                'errors' => [
                    'required' => 'nama/kode lengkap diperlukan',
                    'max_length' => 'terlalu panjang!',
                ]
            ],
            'username' => [
                'rules' => 'required|max_length[40]',
                'errors' => [
                    'required' => 'username diperlukan',
                    'max_length' => 'terlalu panjang!',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'password diperlukan',
                    'min_length' => 'password minimal 8 karakter',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'email diperlukan',
                    'valid_email' => 'masukkan email yang valid!',
                ]
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'status harus dipilih'
                ]
            ],
            'no_telp' => [
                'rules' => 'required|max_length[18]',
                'errors' => [
                    'required' => 'no_telp diperlukan, jika tidak ada masukkan simbol " - " ',
                    'max_length' => 'terlalu panjang!'
                ]
            ],
            'id_prodi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'prodi harus dipilih'
                ]
            ],
        ]);

        if (!$rules) {
            $userModel = new userModel();
            $prodiModel = new prodiModel();

            // Ambil data dari form
            $data = [
                'pageTitle' => 'Tambah Data User',
                'validation' => $this->validator,
                'user' => $userModel->find('id_prodi'),
                'prodi' => $prodiModel->findAll(),
                'nama_user' => $this->request->getPost('nama_user'),
                'username' => $this->request->getPost('username'),
                'password' => Hash::make($this->request->getVar('password')),
                'email' => $this->request->getPost('email'),
                'status' => $this->request->getPost('status'),
                'no_telp' => $this->request->getPost('no_telp'),
                'id_prodi' => $this->request->getPost('id_prodi'),
            ];

            return view('users/add_data_user', $data);
        } else {

            $userModel = new userModel();
            $prodiModel = new prodiModel();
            $data = [
                'user' => $userModel->find('id_prodi'),
                'prodi' => $prodiModel->findAll(),
                'nama_user' => $this->request->getPost('nama_user'),
                'username' => $this->request->getPost('username'),
                'password' => Hash::make($this->request->getVar('password')),
                'email' => $this->request->getPost('email'),
                'status' => $this->request->getPost('status'),
                'no_telp' => $this->request->getPost('no_telp'),
                'id_prodi' => $this->request->getPost('id_prodi'),
            ];

            $userModel->save($data);
            return redirect()->to('admin/users')->with('success', 'Data berhasil disimpan.');
        }
    }

    public function edit_user($id_user)
    {
        $userModel = new UserModel();
        $prodiModel = new ProdiModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('/admin/user')->with('error', 'Data user tidak ditemukan.');
        }

        $data = [
            'pageTitle' => 'Edit Data User',
            'user' => $user,
            'prodi' => $prodiModel->findAll(),
            'status' => $userModel->getStatus()
        ];

        return view('users/edit_data_user', $data);
    }

    public function update_user($id_user)
    {
        $userModel = new UserModel();
        $prodiModel = new ProdiModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'Data user tidak ditemukan.');
        }

        // Ambil data dari form
        $postData = $this->request->getPost();
        $validation = \Config\Services::validation();

        // Validasi data jika diperlukan
        $rules = $this->validate([
            'nama_user' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'nama/kode lengkap diperlukan',
                    'max_length' => 'terlalu panjang!',
                ]
            ],
            'username' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'username diperlukan',
                    'max_length' => 'terlalu panjang!',
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'password diperlukan',
                    'min_length' => 'password minimal 8 karakter',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'email diperlukan',
                    'valid_email' => 'masukkan email yang valid!',
                ]
            ],
            'no_telp' => [
                'rules' => 'required|max_length[18]',
                'errors' => [
                    'required' => 'no_telp diperlukan, jika tidak ada masukkan simbol " - " ',
                    'max_length' => 'terlalu panjang!'
                ]
            ],

        ]);

        if (!$rules) {
            $data = [
                'pageTitle' => 'Edit Data User',
                'user' => $user,
                'prodi' => $prodiModel->findAll(),
                'status' => $userModel->getStatus(),
                'validation' => $validation
            ];

            return view('users/edit_data_user', $data);
        } else {
            // Pastikan id_prodi yang dikirim valid
            if (!$prodiModel->find($postData['id_prodi'])) {
                return redirect()->back()->withInput()->with('error', 'Prodi tidak valid.');
            }

            // Update data user
            $data = [
                'nama_user' => $postData['nama_user'],
                'username' => $postData['username'],
                'password' => Hash::make($postData['password']),
                'email' => $postData['email'],
                'status' => $postData['status'],
                'no_telp' => $postData['no_telp'],
                'id_prodi' => $postData['id_prodi'],
            ];

            $userModel->update($id_user, $data);

            return redirect()->to('admin/users')->with('success', 'Data user berhasil diperbarui.');
        }
    }



    public function delete_user($id)
    {
        $userModel = new UserModel();

        $user = $userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'Data prodi tidak ditemukan.');
        }

        // Hapus data user
        $userModel->delete($id);

        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data user berhasil dihapus.');
    }

    public function kritik()
    {

        $user = new kritikModel();
        $kritik = $user->paginate(10, 'kritik');
        $data = [
            'kritik' => $kritik,
            'pageTitle' => "Kritik",
            'pager' => $user->pager,
        ];

        return view('users/kritik', $data);
    }



    public function add_data_kritik()
    {
        $kritikModel = new kritikModel;
        $data = [
            'pageTitle' => 'Tambah Kritik',
            'kritik' => $kritikModel,
        ];
        return view('view-users/kontak', $data);
    }

    public function save_data_kritik()
    {
        $kritikModel = new kritikModel;

        $rules = $this->validate([
            'nama' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'nama diperlukan',
                    'max_length' => 'terlalu panjang!',
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email',
                'errors' => [
                    'required' => 'email diperlukan',
                    'valid_email' => 'masukkan email yang valid!',
                ]
            ],
            'komentar' => [
                'rules' => 'required|max_length[300]',
                'errors' => [
                    'required' => 'komentar diperlukan',
                    'max_length' => 'terlalu panjang!'
                ]
            ],
        ]);

        if (!$rules) {

            $data = [
                'pageTitle' => 'Tambah Kritik',
                'kritik' => $kritikModel,
                'validation' => $this->validator
            ];
            return view('view-users/kontak', $data);
        } else {
            $data = [
                'kritik' => $kritikModel,
                'nama' => $this->request->getPost('nama'),
                'email' => $this->request->getPost('email'),
                'komentar' => $this->request->getPost('komentar'),
            ];
        }
        $kritikModel->save($data);
        return redirect()->to('user/kontak-user')->with('success', 'Pesan sudah dikirim.');
    }
    public function delete_kritik($id_kontak)
    {
        $kritikModel = new kritikModel();
        $kritikModel->delete(['id_kontak' => $id_kontak]);
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data kritik berhasil dihapus.');
    }
}
