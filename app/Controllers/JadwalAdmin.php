<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\JamModel;
use App\Models\PengajuanModel;
use App\Models\RuanganModel;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\JadwalModel;
use App\Models\th_ajarModel;

class JadwalAdmin extends BaseController
{
    // <-----------------------ADMIN JADWAL------------>
    public function index()
    {
        $jadwalModel = new JadwalModel();

        // Ambil keyword dari request atau dari session jika ada
        $keyword = $this->request->getPost('keyword') ?? session('jadwal_keyword');

        // Simpan keyword dalam session
        session()->set('jadwal_keyword', $keyword);

        // Query dasar dengan join
        $query = $jadwalModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'Reguler');

        // Terapkan filter pencarian berdasarkan keyword
        if ($keyword) {
            // Jika keyword adalah nama ruangan, gunakan like untuk mencari kesesuaian sebagian dari nama ruangan
            $query->groupStart()
                ->like('hari', $keyword)
                ->orLike('nama_dosen', $keyword)
                ->orLike('mk', $keyword)
                ->orLike('kelas', $keyword)
                ->orGroupStart()
                ->like('nama_ruangan', $keyword) // Memastikan nama ruangan mengandung substring yang dicari
                ->orLike('nama_ruangan', str_replace(' ', '%', $keyword)) // Menangani kasus di mana kata kunci terdiri dari beberapa kata
                ->groupEnd()
                ->orGroupStart()
                ->like('nama_prodi', $keyword) // Memastikan nama ruangan mengandung substring yang dicari
                ->orLike('nama_prodi', str_replace(' ', '%', $keyword)) // Menangani kasus di mana kata kunci terdiri dari beberapa kata
                ->groupEnd()
                ->groupEnd();
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();
        // Melakukan paginate pada hasil query
        $result = $query->paginate(10, 'jadwal');

        // Ambil pager setelah menjalankan metode paginate
        $pager = $jadwalModel->pager;

        // Siapkan data untuk views
        $data = [
            'pageTitle' => 'Jadwal-Reguler',
            'jadwal' => $result,
            'pager' => $pager, // Kirim pager ke view
            'keyword' => $keyword,
            'hari' => $hari,
            'jam' => $jam->findAll() // Kirim keyword kembali ke view
        ];

        return view('jadwal/jadwal-reguler', $data);
    }



    public function edit_reguler($id_jadwal)
    {
        // Simpan URL referer ke dalam session
        $session = session();
        $session->setFlashdata('referrer', $_SERVER['HTTP_REFERER']);

        $jadwalmodel = new JadwalModel();
        $ruanganModel = new RuanganModel();
        $jadwal = $jadwalmodel->joinRuangan()->joinTA()->joinProdi()->joinJam1();
        $data = [
            'pageTitle' => 'Edit Jadwal',
            'jadwal' => $jadwal->where('jadwal.id_jadwal', $id_jadwal)->first(),
            'ruangan' => $ruanganModel->findAll(),
            'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'] // Define the $hari variable here
        ];

        return view('jadwal/edit-reguler', $data);
    }

    public function update_reguler($id_jadwal)
    {
        $jadwalmodel = new JadwalModel();
        $rules = $this->validate([
            'mk' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'mata kuliah diperlukan',
                    'max_length' => 'terlalu panjang!'
                ]
            ],
            'nama_dosen' => [
                'rules' => 'required|min_length[1]',
                'errors' => [
                    'required' => 'nama dosen diperlukan',
                    'min_length' => 'terlalu pendek!'
                ]
            ],
            'kelas' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'kelas diperlukan',
                ]
            ],
            'jam' => [
                'rules' => 'required',
                'errors' => 'jam diperlukan'
            ],
            'nama_prodi' => [
                'rules' => 'required',
                'errors' => 'prodi diperlukan'
            ],
            'nama_ruangan' => [
                'rules' => 'required',
                'errors' => 'nama ruangan harus dipilih'
            ],
            'jenis' => [
                'rules' => 'required',
                'errors' => 'jenis diperlukan'
            ],
            'hari' => [
                'rules' => 'required',
                'errors' => 'hari harus dipilih'
            ],
        ]);

        $session = session();
        $referrer = $session->getFlashdata('referrer');

        if (!$rules) {
            $jadwalmodel = new JadwalModel();
            $ruanganModel = new RuanganModel();
            $jadwal = $jadwalmodel->joinRuangan()->joinTA()->joinProdi()->joinJam1()->where('jadwal.id_jadwal', $id_jadwal)->first();
            $data = [
                'pageTitle' => 'Edit Jadwal',
                'jadwal' => $jadwal,
                'ruangan' => $ruanganModel->findAll(),
                'hari' => ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
                'validation' => $this->validator
            ];

            return view('jadwal/edit-reguler', $data);
        } // Jika URL referer ada dan merupakan string, arahkan pengguna kembali ke URL referer
        if ($referrer && is_string($referrer)) {
            $data = $this->request->getPost();
            $jadwalmodel->update($id_jadwal, $data);
            return redirect()->to($referrer)->with('success', 'Data berhasil diperbarui.');
        } else {
            // Jika tidak ada URL referer, arahkan pengguna ke halaman jadwal
            return redirect()->to('admin/jadwal')->with('success', 'Data berhasil diperbarui.');
        }
    }



    // ---------------ADMIN JADWAL NON REGULER ------------------------
    public function jadwalNonReguler()
    {
        $jadwalModel = new JadwalModel();

        // Ambil keyword dari request atau dari session jika ada
        $keyword = $this->request->getPost('keyword') ?? session('jadwal_keyword');

        // Simpan keyword dalam session
        session()->set('jadwal_keyword', $keyword);

        // Query dasar dengan join
        $query = $jadwalModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'Nonreguler');

        // Terapkan filter pencarian berdasarkan keyword
        if ($keyword) {
            // Pecah keyword menjadi array
            $keywordArr = explode(' ', $keyword);

            // Ekstrak hari dan nama dosen dari array keyword
            $hari = $keywordArr[0] ?? '';
            $nama_dosen = $keywordArr[1] ?? '';

            // Jika ada hari dan nama dosen, terapkan filter
            if ($hari && $nama_dosen) {
                $query->where('hari', $hari)->where('nama_dosen', $nama_dosen);
            } else {
                // Jika tidak ada hari dan nama dosen, gunakan filter berdasarkan keyword
                $query->groupStart()
                    ->like('hari', $keyword)
                    ->orLike('nama_dosen', $keyword)
                    ->orLike('mk', $keyword)
                    ->orLike('nama_prodi', $keyword)
                    ->orLike('nama_ruangan', '%' . $keyword . '%')
                    ->groupEnd();
            }
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();

        // Melakukan paginate pada hasil query
        $result = $query->paginate(10, 'jadwal');

        // Ambil pager setelah menjalankan metode paginate
        $pager = $jadwalModel->pager;

        // Siapkan data untuk view
        $data = [
            'pageTitle' => 'Jadwal-Nonreguler',
            'jadwal' => $result,
            'pager' => $pager, // Kirim pager ke view
            'keyword' => $keyword,
            'hari' => $hari,
            'jam' => $jam->findAll() // Kirim keyword kembali ke view
        ];

        return view('jadwal/jadwal-nonreguler', $data);
    }
    public function jadwalUAS()
    {
        $jadwalModel = new JadwalModel();

        // Ambil keyword dari request atau dari session jika ada
        $keyword = $this->request->getPost('keyword') ?? session('jadwal_keyword');

        // Simpan keyword dalam session
        session()->set('jadwal_keyword', $keyword);

        // Query dasar dengan join
        $query = $jadwalModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'UAS');

        // Terapkan filter pencarian berdasarkan keyword
        if ($keyword) {
            // Pecah keyword menjadi array
            $keywordArr = explode(' ', $keyword);

            // Ekstrak hari dan nama dosen dari array keyword
            $hari = $keywordArr[0] ?? '';
            $nama_dosen = $keywordArr[1] ?? '';

            // Jika ada hari dan nama dosen, terapkan filter
            if ($hari && $nama_dosen) {
                $query->where('hari', $hari)->where('nama_dosen', $nama_dosen);
            } else {
                // Jika tidak ada hari dan nama dosen, gunakan filter berdasarkan keyword
                $query->groupStart()
                    ->like('hari', $keyword)
                    ->orLike('nama_dosen', $keyword)
                    ->orLike('mk', $keyword)
                    ->orLike('nama_prodi', $keyword)
                    ->orLike('nama_ruangan', '%' . $keyword . '%')
                    ->groupEnd();
            }
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();

        // Melakukan paginate pada hasil query
        $result = $query->paginate(10, 'jadwal');

        // Ambil pager setelah menjalankan metode paginate
        $pager = $jadwalModel->pager;

        // Siapkan data untuk view
        $data = [
            'pageTitle' => 'Jadwal-UAS',
            'jadwal' => $result,
            'pager' => $pager, // Kirim pager ke view
            'keyword' => $keyword,
            'hari' => $hari,
            'jam' => $jam->findAll(), // Kirim keyword kembali ke view
        ];

        return view('jadwal/jadwal-uas', $data);
    }
    public function jadwalUTS()
    {
        $jadwalModel = new JadwalModel();

        // Ambil keyword dari request atau dari session jika ada
        $keyword = $this->request->getPost('keyword') ?? session('jadwal_keyword');

        // Simpan keyword dalam session
        session()->set('jadwal_keyword', $keyword);

        // Query dasar dengan join
        $query = $jadwalModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'UTS');

        // Terapkan filter pencarian berdasarkan keyword
        if ($keyword) {
            // Pecah keyword menjadi array
            $keywordArr = explode(' ', $keyword);

            // Ekstrak hari dan nama dosen dari array keyword
            $hari = $keywordArr[0] ?? '';
            $nama_dosen = $keywordArr[1] ?? '';

            // Jika ada hari dan nama dosen, terapkan filter
            if ($hari && $nama_dosen) {
                $query->where('hari', $hari)->where('nama_dosen', $nama_dosen);
            } else {
                // Jika tidak ada hari dan nama dosen, gunakan filter berdasarkan keyword
                $query->groupStart()
                    ->like('hari', $keyword)
                    ->orLike('nama_dosen', $keyword)
                    ->orLike('mk', $keyword)
                    ->orLike('nama_prodi', $keyword)
                    ->orLike('nama_ruangan', '%' . $keyword . '%')
                    ->groupEnd();
            }
        }

        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();
        // Melakukan paginate pada hasil query
        $result = $query->paginate(10, 'jadwal');

        // Ambil pager setelah menjalankan metode paginate
        $pager = $jadwalModel->pager;

        // Siapkan data untuk view
        $data = [
            'pageTitle' => 'Jadwal-UTS',
            'jadwal' => $result,
            'pager' => $pager, // Kirim pager ke view
            'keyword' => $keyword,
            'hari' => $hari,
            'jam' => $jam->findAll() // Kirim keyword kembali ke view
        ];

        return view('jadwal/jadwal-uts', $data);
    }


    public function deleteJadwal($id_jadwal)
    {
        $ruanganModel = new JadwalModel();
        $ruanganModel->delete(['id_jadwal' => $id_jadwal]);
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data jadwal berhasil dihapus.');
    }


    public function pengajuanJadwal()
    {
        $pengajuanModel = new PengajuanModel();
        $pengajuan = $pengajuanModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'REGULER')->paginate(10, 'pengajuan');
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();
        $data = [
            'pageTitle' => 'Pengajuan-Admin',
            'pengajuan' => $pengajuan,
            'pager' => $pengajuanModel->pager,
            'hari' => $hari,
            'jam' => $jam->findAll() // Kirim keyword kembali ke view
        ];

        return view('pengajuan_admin/pengajuan-admin', $data);

    }
    public function pengajuanJadwalNonReguler()
    {
        $pengajuanModel = new PengajuanModel();
        $pengajuan = $pengajuanModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'NONREGULER')->paginate(10, 'pengajuan');
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();
        $data = [
            'pageTitle' => 'Pengajuan-Admin',
            'pengajuan' => $pengajuan,
            'pager' => $pengajuanModel->pager,
            'hari' => $hari,
            'jam' => $jam->findAll() // Kirim keyword kembali ke view
        ];

        return view('pengajuan_admin/pengajuan-nonreguler', $data);

    }
    public function pengajuanJadwalUAS()
    {
        $pengajuanModel = new PengajuanModel();
        $pengajuan = $pengajuanModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'UAS')->paginate(10, 'pengajuan');
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();
        $data = [
            'pageTitle' => 'Pengajuan-Admin',
            'pengajuan' => $pengajuan,
            'pager' => $pengajuanModel->pager,
            'hari' => $hari,
            'jam' => $jam->findAll() // Kirim keyword kembali ke view
        ];

        return view('pengajuan_admin/pengajuan-uas', $data);

    }
    public function pengajuanJadwalUTS()
    {
        $pengajuanModel = new PengajuanModel();
        $pengajuan = $pengajuanModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', 'UTS')->paginate(10, 'pengajuan');
        $hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jam = new JamModel();
        $data = [
            'pageTitle' => 'Pengajuan-Admin',
            'pengajuan' => $pengajuan,
            'pager' => $pengajuanModel->pager,
            'hari' => $hari,
            'jam' => $jam->findAll() // Kirim keyword kembali ke view
        ];

        return view('pengajuan_admin/pengajuan-uts', $data);

    }

    public function approvePengajuan($id)
    {
        $pengajuanModel = new PengajuanModel();
        $pengajuan = $pengajuanModel->getPengajuanById($id);
        $jam = $pengajuan['id_jam'];
        $id_thn = $pengajuan['id_thn'];
        $mk = $pengajuan['mk'];
        $kelas = $pengajuan['kelas'];
        $id_ruangan = $pengajuan['id_ruangan'];
        $nama_dosen = $pengajuan['nama_dosen'];
        $jenis = $pengajuan['jenis'];
        $id_thn = $pengajuan['id_thn'];
        $hari = $pengajuan['hari'];
        $id_prodi = $pengajuan['id_prodi'];

        // Perbaikan operator panah di bawah ini
        $pengajuanModel->insertApprovedSchedule($mk, $kelas, $id_ruangan, $jam, $nama_dosen, $jenis, $id_thn, $hari, $id_prodi);
        $pengajuanModel->deleteSchedule($jam);
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data jadwal telah disetujui.');
    }

    public function deletePengajuan($id)
    {
        $pengajuanModel = new PengajuanModel();
        $pengajuan = $pengajuanModel->getPengajuanById($id);
        $jam = $pengajuan['id_jam'];
        $pengajuanModel->deleteSchedule($jam);
        return redirect()->to($_SERVER['HTTP_REFERER'])->with('success', 'Data jadwal berhasil dihapus.');
    }
}
