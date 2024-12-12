<?php

namespace App\Controllers;

use App\Models\fasilitas_softwareModel;
use App\Models\RuanganModel;
use App\Models\barangModel;
use App\Models\galeriModel;
use App\Models\fasilitas_hardwareModel;
use App\Models\PegawaiModel;

class Fasilitas extends BaseController
{
    public function index()
    {
        $hardwareModel = new RuanganModel();
        $fasilitas = $hardwareModel->paginate(10, 'fasilitas');
        $data = [
            'fasilitas' => $fasilitas,
            'pageTitle' => "Fasilitas Hardware",
            'pager' => $hardwareModel->pager,
        ];

        return view('pengolahan_lab/fasilitas', $data);
    }


    public function detailFasilitasHardware($id_ruangan)
    {
        $fasilitasModel = new fasilitas_hardwareModel();
        $ruanganModel = new RuanganModel();

        $keyword = $this->request->getPost('keyword') ?? session('hardware');

        // Simpan keyword dalam session
        session()->set('hardware', $keyword);
        // Ambil data ruangan untuk digunakan di view
        $ruangan = $ruanganModel->find($id_ruangan);

        // Ambil data fasilitas software berdasarkan id ruangan dengan paginasi
        $fasilitas = $fasilitasModel->joinRuangan()->where('f_hardware_b.id_ruangan', $id_ruangan);

        if ($keyword) {

            $fasilitas->groupStart()
                ->like('nama', $keyword)
                ->orLike('jumlah', $keyword)
                ->orLike('gambar', $keyword)
                ->groupEnd();
        }

        $result = $fasilitas->paginate(10, 'fasilitas');

        $pager = $fasilitasModel->pager;

        $data = [
            'fasilitas' => $result,
            'ruangan' => $ruangan,
            'pageTitle' => 'Hardware Lab',
            'pager' => $pager,
            'id_ruangan' => $id_ruangan
        ];

        return view('hardware/f_hardware', $data);
    }

    public function delete_hardware($id)
    {
        $fasilitas = new fasilitas_hardwareModel();
        $fasilitas->delete(['id' => $id]);
        // Redirect ke halaman sebelumnya
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data hardware berhasil dihapus.');
    }


    public function edit_hardware($id)
    {
        $hardwareModel = new fasilitas_hardwareModel;
        $ruanganModel = new RuanganModel();
        $data = [
            'pageTitle' => 'Edit Data Hardware',
            'hardware' => $hardwareModel->where('id', $id)->first(),
            'ruangan' => $ruanganModel->findAll() // Fetch all ruangan data
        ];

        return view('hardware/edit_data_hardware', $data);
    }

    public function update_hardware($id)
    {
        $hardwareModel = new fasilitas_hardwareModel;
        $ruanganModel = new RuanganModel();
        $id_ruangan = $this->request->getPost('id_ruangan');

        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'jumlah' => 'required|numeric',
            'id_ruangan' => 'required'
        ];

        if (!$this->validate($rules)) {
            $data = [
                'pageTitle' => 'Edit Data Hardware',
                'hardware' => $hardwareModel->find($id),
                'ruangan' => $ruanganModel->findAll(),
                'validation' => $this->validator
            ];
            return view('hardware/edit_data_hardware', $data);
        }

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama'),
            'jumlah' => $this->request->getPost('jumlah'),
            'id_ruangan' => $this->request->getPost('id_ruangan')
        ];

        // Periksa apakah ada file gambar yang diunggah
        $gambar = $this->request->getFile('gambar');
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            // Jika ada, unggah gambar baru
            $newFileName = $gambar->getName();
            $gambar->move('img', $newFileName);
            // Simpan nama gambar ke dalam data
            $data['gambar'] = $newFileName;
        }

        // Lakukan update data
        $hardwareModel->update($id, $data);

        return redirect()->to('admin/detail_fasilitas_hardware/' . $id_ruangan)->with('success', 'Data hardware berhasil diperbarui.');
    }


    public function add_data_hardware($id_ruangan)
    {
        $ruanganModel = new RuanganModel();
        $hardwareModel = new fasilitas_hardwareModel;
        $data = [
            'pageTitle' => 'Tambah Data Hardware',
            'ruangan' => $ruanganModel->findAll(),
            'id_ruangan' => $id_ruangan,
            'hardware' => $hardwareModel->findAll()

        ];
        return view('hardware/add_data_hardware', $data);
    }

    public function save_data_hardware()
    {
        $hardwareModel = new fasilitas_hardwareModel;
        $data = $this->request->getPost();
        $id_ruangan = $this->request->getPost('id_ruangan');

        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'jumlah' => 'required|numeric',
            'gambar' => 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]|max_size[gambar,3072]',
            'nama_ruangan' => 'required'
        ];

        if (!$this->validate($rules)) {
            // Validasi gagal, kembali ke halaman form dengan pesan kesalahan
            $ruanganModel = new RuanganModel();
            return view('hardware/add_data_hardware', [
                'pageTitle' => 'Tambah Data Hardware',
                'ruangan' => $ruanganModel->findAll(),
                'validation' => $this->validator,
                'id_ruangan' => $id_ruangan
            ]);
        }

        // Dapatkan id ruangan berdasarkan nama ruangan yang dipilih
        $ruanganModel = new RuanganModel();
        $ruangan = $ruanganModel->where('nama_ruangan', $data['nama_ruangan'])->first();
        $data['id_ruangan'] = $ruangan['id_ruangan'];

        // Hapus nama ruangan dari data sebelum menyimpan ke dalam tabel software
        unset($data['nama_ruangan']);

        // Unggah gambar
        $gambar = $this->request->getFile('gambar');
        $newFileName = $gambar->getName();
        $gambar->move('img', $newFileName);
        $data['gambar'] = $newFileName;

        // Simpan data perangkat lunak baru
        $hardwareModel->insert($data);

        // Redirect kembali ke halaman detail fasilitas dengan menyertakan id ruangan
        return redirect()->to('admin/detail_fasilitas_hardware/' . $id_ruangan)->with('success', 'Data berhasil disimpan.');
    }
    public function software()
    {
        $softwareModel = new RuanganModel();
        $fasilitas = $softwareModel->paginate(10, 'fasilitas');
        $data = [
            'fasilitas' => $fasilitas,
            'pageTitle' => "Fasilitas Software",
            'pager' => $softwareModel->pager,
        ];

        return view('pengolahan_lab/r_software', $data);
    }

    public function detailFasilitas($id_ruangan)
    {
        $fasilitasModel = new fasilitas_softwareModel();
        $ruanganModel = new RuanganModel();

        //Ambil Keyword dari form input search
        $keyword = $this->request->getPost('keyword') ?? session('software');

        // Simpan keyword dalam session
        session()->set('jadwal_keyword', $keyword);

        // Ambil data ruangan untuk digunakan di view
        $ruangan = $ruanganModel->find($id_ruangan);

        // Ambil data fasilitas software berdasarkan id ruangan dengan paginasi
        $fasilitas = $fasilitasModel->where('id_ruangan', $id_ruangan);

        if ($keyword) {

            $fasilitas->groupStart()
                ->like('nama', $keyword)
                ->orLike('jumlah', $keyword)
                ->groupEnd();
        }

        // Melakukan paginate pada hasil query
        $result = $fasilitas->paginate(10, 'fasilitas');

        $pager = $fasilitasModel->pager;
        $data = [
            'fasilitas' => $result,
            'ruangan' => $ruangan,
            'pageTitle' => 'Software Lab',
            'pager' => $pager,
            'id_ruangan' => $id_ruangan
        ];

        return view('pengolahan_lab/f_software', $data);
    }

    public function delete_software($id)
    {
        $fasilitas = new fasilitas_softwareModel();
        $fasilitas->delete(['id' => $id]);
        // Redirect ke halaman sebelumnya
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data software berhasil dihapus.');
    }


    public function edit_software($id)
    {
        $ruanganModel = new RuanganModel();
        $softwareModel = new fasilitas_softwareModel;
        $data = [
            'pageTitle' => 'Edit Data Software',
            'software' => $softwareModel->where('id', $id)->first(),
            'ruangan' => $ruanganModel->findAll(),
            'id_ruangan' => $id,
        ];

        return view('pengolahan_lab/edit_data_software', $data);
    }
    public function add_data_software($id_ruangan)
    {
        $ruanganModel = new RuanganModel();
        $softwareModel = new fasilitas_softwareModel;
        $data = [
            'pageTitle' => 'Tambah Data Software',
            'ruangan' => $ruanganModel->findAll(),
            'id_ruangan' => $id_ruangan,
            'software' => $softwareModel->findAll()

        ];
        return view('pengolahan_lab/add_data_software', $data);
    }


    //Ruangan Controller
    public function ruangan()
    {
        $fasilitas = new RuanganModel();
        $ruangan = $fasilitas->joinPegawai()->paginate(10, 'ruangan');
        $data = [
            'ruangan' => $ruangan,
            'pageTitle' => "Ruangan",
            'pager' => $fasilitas->pager,
        ];

        return view('pengolahan_lab/ruangan', $data);
    }

    public function edit_ruangan($id_ruangan)
    {
        $ruanganModel = new RuanganModel;
        $pegawaiModel = new PegawaiModel;
        $data = [
            'pageTitle' => 'Edit Data Ruangan',
            'ruangan' => $ruanganModel->where('id_ruangan', $id_ruangan)->first(),
            'pegawai' => $pegawaiModel->findAll()
        ];

        return view('pengolahan_lab/edit_data_ruangan', $data);
    }

    public function update_ruangan($id_ruangan)
    {
        $pegawaiModel = new PegawaiModel;
        $ruanganModel = new RuanganModel;
        $rules = $this->validate([
            'nama_ruangan' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => ' nama ruangan tidak boleh kosong',
                    'max_length[40]' => 'nama Terlalu Panjang',
                ]
            ],
            'keterangan' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'keterangan di perlukan',
                    'min_length' => 'terlalu pendek'
                ]
            ],
            'lokasi' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'lokasi tidak boleh kosong!',
                    'min_length' => 'terlalu pendek'
                ]
            ],
            'id_teknisi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'penanggung jawab tidak boleh kosong!'
                ]
            ]
        ]);

        if (!$rules) {
            return view('pengolahan_lab/edit_data_ruangan', [
                'pageTitle' => 'Edit Data Ruangan',
                'ruangan' => $ruanganModel->where('id_ruangan', $id_ruangan)->first(),
                'pegawai' => $pegawaiModel->findAll(),
                'validation' => $this->validator
            ]);
        } else {
            $data = $this->request->getPost();
            $ruanganModel->update($id_ruangan, $data);
            return redirect()->to('admin/ruangan')->with('success', 'Data ruangan berhasil diperbarui.');
        }
    }

    public function add_data_ruangan()
    {
        $ruanganModel = new ruanganModel;
        $pegawaiModel = new PegawaiModel;
        $pegawai = $pegawaiModel->findAll();

        $data = [
            'pageTitle' => 'Tambah Data Ruangan',
            'pegawai' => $pegawai
        ];
        return view('pengolahan_lab/add_data_ruangan', $data);
    }

    public function save_data_ruangan()
    {
        $pegawaiModel = new PegawaiModel;
        $ruanganModel = new RuanganModel;
        $rules = $this->validate([
            'nama_ruangan' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => ' nama ruangan tidak boleh kosong',
                    'max_length[40]' => 'Nama Terlalu Panjang',
                ]
            ],
            'keterangan' => [
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'keterangan di perlukan',
                    'min_length' => 'terlalu pendek'
                ]
            ],
            'lokasi' => [
                'rules' => 'required|min_length[5]',
                'errors' => [
                    'required' => 'lokasi tidak boleh kosong!',
                    'min_length' => 'terlalu pendek'
                ]
            ],
            'id_teknisi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'penanggung jawab tidak boleh kosong!'
                ]
            ]
        ]);

        if (!$rules) {
            return view('pengolahan_lab/add_data_ruangan', [
                'pageTitle' => 'Tambah Data Ruangan',
                'validation' => $this->validator,
                'pegawai' => $pegawaiModel->findAll()
            ]);
        } else {
            $ruanganModel->insert($this->request->getPost());
            return redirect()->to('admin/ruangan')->with('success', 'Data berhasil disimpan.');
        }
    }

    public function delete_ruangan($id_nama)
    {
        $ruanganModel = new RuanganModel();
        $ruanganModel->delete(['id_nama' => $id_nama]);
        // Redirect ke halaman sebelumnya
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data ruangan berhasil dihapus.');
    }




    public function barang()
    {
        $fasilitas = new barangModel();
        $keyword = $this->request->getPost('keyword') ?? session('barang');
        $barang = $fasilitas->joinRuangan();

        if ($keyword) {
            $barang->groupStart()
                ->like('nama_ruangan', $keyword)
                ->orLike('penanggungjawab', $keyword)
                ->orLike('deskripsi', $keyword)
                ->groupEnd();
        }

        $result = $barang->paginate(10, 'barang');
        $pager = $fasilitas->pager;


        $data = [
            'barang' => $result,
            'pageTitle' => "Data Barang",
            'pager' => $pager,
        ];

        return view('pengolahan_lab/barang', $data);
    }

    public function delete_barang($id_aset)
    {
        $barangModel = new barangModel();
        $barangModel->delete(['id_aset' => $id_aset]);
        // Redirect ke halaman sebelumnya
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data barang berhasil dihapus.');
    }

    public function edit_barang($id_aset)
    {
        $barangModel = new barangModel;
        $ruanganModel = new RuanganModel(); // Tambahkan baris ini
        $data = [
            'pageTitle' => 'Edit Data Barang',
            'barang' => $barangModel->where('id_aset', $id_aset)->first(),
            'galeri' => $ruanganModel->findAll() // Tambahkan baris ini
        ];

        return view('pengolahan_lab/edit_data_barang', $data);
    }

    public function update_barang($id_aset)
    {
        $barangModel = new barangModel;
        $ruanganModel = new RuanganModel();

        $rules = $this->validate([
            'deskripsi' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'nama barang diperlukan',
                    'max_length' => 'terlalu panjang!'
                ]
            ],
            'serialnumber' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'serial number diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'supplier' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'supplier diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'brand' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'brand diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'model' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'model diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'penanggungjawab' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'penanggung jawab diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
        ]);

        if (!$rules) {
            $barangModel = new barangModel;
            $data = [
                'pageTitle' => 'Edit Data Barang',
                'barang' => $barangModel->where('id_aset', $id_aset)->first(),
                'galeri' => $ruanganModel->findAll(),
                'validation' => $this->validator
            ];

            return view('pengolahan_lab/edit_data_barang', $data);
        } else {
            $data = $this->request->getPost();
            $barangModel->update($id_aset, $data);
            return redirect()->to('admin/barang')->with('success', 'Data barang berhasil diperbarui.');
        }
    }

    public function add_data_barang()
    {
        $galeri = new galeriModel;
        $barangModel = new RuanganModel();
        $galeri = $barangModel->findAll();
        $data = [
            'pageTitle' => 'Tambah Data Barang',
            'galeri' => $galeri
        ];
        return view('pengolahan_lab/add_data_barang', $data);
    }

    public function save_data_barang()
    {
        $barangModel = new barangModel;

        $rules = $this->validate([
            'deskripsi' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'nama barang diperlukan',
                    'max_length' => 'terlalu panjang!'
                ]
            ],
            'serialnumber' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'serial number diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'supplier' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'supplier diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'brand' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'brand diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'model' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'model diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'penanggungjawab' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'penanggung jawab diperlukan, jika tidak ada masukkan simbol " - "',
                ]
            ],
            'id_ruangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ruangan harus dipilih'
                ]
            ],
        ]);

        if (!$rules) {
            $galeri = new galeriModel;
            $barangModel = new RuanganModel();
            $galeri = $barangModel->findAll();
            $data = [
                'galeri' => $galeri,
                'pageTitle' => 'Tambah Data Barang',
                'validation' => $this->validator
            ];
            return view('pengolahan_lab/add_data_barang', $data);
        } else {
            $barangModel->insert($this->request->getPost());
            return redirect()->to('admin/barang')->with('success', 'Data berhasil disimpan.');
        }
    }


    public function galeri()
    {
        $fasilitas = new galeriModel();
        $galeri = $fasilitas->joinRuangan()->orderBy('galeri.id_ruangan', 'asc')->paginate(10, 'galeri');
        $data = [
            'galeri' => $galeri,
            'pageTitle' => "Galeri",
            'pager' => $fasilitas->pager,

        ];

        return view('pengolahan_lab/galeri', $data);
    }

    public function delete_galeri($id_galeri)
    {
        $galeriModel = new galeriModel();
        $galeriModel->delete(['id_galeri' => $id_galeri]);
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data galeri berhasil dihapus.');
    }

    public function edit_galeri($id_galeri)
    {
        $galeriModel = new galeriModel();
        $ruanganModel = new RuanganModel();
        $data = [
            'pageTitle' => 'Edit Data Galeri',
            'galeri' => $galeriModel->where('id_galeri', $id_galeri)->first(),
            'ruangan' => $ruanganModel->findAll()
        ];

        return view('pengolahan_lab/edit_data_galeri', $data);
    }

    public function update_galeri($id_galeri)
    {
        $galeriModel = new galeriModel;
        $ruanganModel = new ruanganModel();
        $id_ruangan = $this->request->getPost('id_ruangan');
        // Menerima data yang dikirim melalui form
        $data = [];

        // Cek apakah ada gambar yang diunggah
        if ($this->request->getFile('foto')->isValid()) {
            $rules = $this->validate([
                'foto' => [
                    'uploaded[foto]',
                    'mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]',
                    'max_size[foto,3072]'
                ]
            ]);

            if (!$rules) {
                return view('pengolahan_lab/edit_data_galeri', [
                    'pageTitle' => 'Edit Data Galeri',
                    'galeri' => $galeriModel->find($id_galeri),
                    'ruangan' => $ruanganModel->findAll(),
                    'validation' => $this->validator
                ]);
            }

            $foto = $this->request->getFile('foto');
            $namaFoto = $foto->getName();
            $foto->move('img', $namaFoto);

            // Simpan nama foto ke dalam data
            $data['foto'] = $namaFoto;
        }

        // Ambil nama ruangan baru dari database berdasarkan id_ruangan yang baru
        if ($id_ruangan) {
            $ruanganBaru = $ruanganModel->find($id_ruangan);
            $data['id_ruangan'] = $id_ruangan;
            $data['nama_ruangan'] = $ruanganBaru['nama_ruangan'];
        }

        // Perbarui entri di database dengan data yang baru
        $galeriModel->update($id_galeri, $data);
        return redirect()->to('admin/galeri')->with('success', 'Data galeri berhasil diperbarui.');
    }

    public function add_data_galeri()
    {
        $galeriModel = new galeriModel;
        $ruanganModel = new RuanganModel();

        // Ambil data ruangan dari database
        $ruangan = $ruanganModel->findAll();

        $data = [
            'pageTitle' => "Tambah Data Galeri",
            'ruangan' => $ruangan, // Melewatkan data ruangan ke view
            'galeri' => $galeriModel->findAll()

        ];
        return view('pengolahan_lab/add_data_galeri', $data);
    }

    public function save_data_galeri()
    {
        $galeriModel = new galeriModel;
        $ruanganModel = new ruanganModel();
        // Ambil data ruangan dari database
        $ruangan = $ruanganModel->findAll();

        $rules = $this->validate([
            'foto' => [
                'rules' => 'uploaded[foto]|mime_in[foto,image/jpg,image/jpeg,image/png,image/gif]|max_size[foto,3072]',
                'errors' => [
                    'uploaded' => 'foto belum di upload',
                    'mime_in' => 'maaf file anda bukan image',
                    'max_size' => 'maaf file anda bukan image',
                ]
            ],
            'id_ruangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'ruangan harus dipilih'
                ]
            ],
        ]);
        if (!$rules) {
            return view('pengolahan_lab/add_data_galeri', [
                'pageTitle' => 'Tambah Data Galeri',
                'ruangan' => $ruangan,
                'validation' => $this->validator,
            ]);
        } else {
            $foto = $this->request->getFile('foto');
            $namaFoto = $foto->getName();
            $foto->move('img', $namaFoto);
            $galeriModel->insert([
                'foto' => $namaFoto,  // Gunakan nama file yang valid
                'id_ruangan' => $this->request->getPost('id_ruangan'),
            ]);
            return redirect()->to('admin/galeri')->with('success', 'Data berhasil disimpan.');
        }
    }

    public function update_software($id)
    {
        $softwareModel = new fasilitas_softwareModel;
        $ruangan = new RuanganModel();
        $id_ruangan = $this->request->getPost('id_ruangan');


        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'jumlah' => 'required|numeric',
            'id_ruangan' => 'required'
        ];

        if (!$this->validate($rules)) {
            $data = [
                'pageTitle' => 'Edit Software',
                'software' => $softwareModel->find($id),
                'ruangan' => $ruangan->findAll(),
                'id_ruangan' => $id,
                'validation' => $this->validator
            ];
            return view('pengolahan_lab/edit_data_software', $data);
        }

        // Ambil data dari form
        $data = [
            'nama' => $this->request->getPost('nama'),
            'jumlah' => $this->request->getPost('jumlah'),
            'id_ruangan' => $this->request->getPost('id_ruangan')
        ];

        // Periksa apakah ada file gambar yang diunggah
        $gambar = $this->request->getFile('gambar');
        if ($gambar->isValid() && !$gambar->hasMoved()) {
            // Jika ada, unggah gambar baru
            $newFileName = $gambar->getName();
            $gambar->move('img', $newFileName);
            // Simpan nama gambar ke dalam data
            $data['gambar'] = $newFileName;
        }

        // Lakukan update data
        $softwareModel->update($id, $data);

        return redirect()->to('admin/detail_fasilitas/' . $id_ruangan)->with('success', 'Data software berhasil diperbarui.');
    }

    public function save_data_software()
    {
        $softwareModel = new fasilitas_softwareModel;
        $data = $this->request->getPost();
        $id_ruangan = $this->request->getPost('id_ruangan');

        // Validasi input
        $rules = [
            'nama' => 'required|min_length[3]',
            'jumlah' => 'required|numeric',
            'gambar' => 'uploaded[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif]|max_size[gambar,3072]',
            'nama_ruangan' => 'required'
        ];

        if (!$this->validate($rules)) {
            // Validasi gagal, kembali ke halaman form dengan pesan kesalahan
            $ruanganModel = new RuanganModel();
            return view('pengolahan_lab/add_data_software', [
                'pageTitle' => 'Tambah Data Software',
                'ruangan' => $ruanganModel->findAll(),
                'validation' => $this->validator,
                'id_ruangan' => $id_ruangan
            ]);
        }

        // Dapatkan id ruangan berdasarkan nama ruangan yang dipilih
        $ruanganModel = new RuanganModel();
        $ruangan = $ruanganModel->where('nama_ruangan', $data['nama_ruangan'])->first();
        $data['id_ruangan'] = $ruangan['id_ruangan'];

        // Hapus nama ruangan dari data sebelum menyimpan ke dalam tabel software
        unset($data['nama_ruangan']);

        // Unggah gambar
        $gambar = $this->request->getFile('gambar');
        $newFileName = $gambar->getName();
        $gambar->move('img', $newFileName);
        $data['gambar'] = $newFileName;

        // Simpan data perangkat lunak baru
        $softwareModel->insert($data);

        // Redirect kembali ke halaman detail fasilitas dengan menyertakan id ruangan
        return redirect()->to('admin/detail_fasilitas/' . $id_ruangan)->with('success', 'Data berhasil disimpan.');
    }
}
