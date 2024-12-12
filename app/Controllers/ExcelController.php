<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\JadwalModel;
use App\Models\JadwalDetailModel;
use App\Models\JamModel;
use App\Models\fasilitas_softwareModel;
use App\Models\fasilitas_hardwareModel;
use App\Models\userModel;
use App\Models\RuanganModel;

class ExcelController extends BaseController
{
    public function index($jenis)
    {
        $jadwalModel = new JadwalModel();

        $thn_awal = $jadwalModel->getTahunAwal();
        $thn_akhir = $jadwalModel->getTahunAkhir();
        $semester = $jadwalModel->getSemester();

        $jadwal = $jadwalModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', $jenis)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Mata Kuliah');
        $sheet->setCellValue('C3', 'Dosen');
        $sheet->setCellValue('D3', 'Kelas');
        $sheet->setCellValue('E3', 'Jam');
        $sheet->setCellValue('F3', 'Program Studi');
        $sheet->setCellValue('G3', 'Semester');
        $sheet->setCellValue('H3', 'Jenis');
        $sheet->setCellValue('I3', 'Hari');
        $sheet->setCellValue('J3', 'Tahun');
        $column = 4;
        foreach ($jadwal as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 3));
            $sheet->setCellValue('B' . $column, $value['mk']);
            $sheet->setCellValue('C' . $column, $value['nama_dosen']);
            $sheet->setCellValue('D' . $column, $value['kelas']);
            $sheet->setCellValue('E' . $column, $value['jam']);
            $sheet->setCellValue('F' . $column, $value['nama_prodi']);
            $sheet->setCellValue('G' . $column, $value['semester']);
            $sheet->setCellValue('H' . $column, $value['jenis']);
            $sheet->setCellValue('I' . $column, $value['hari']);
            $sheet->setCellValue('J' . $column, $value['thn_awal'] . ' - ' . $value['thn_akhir']);
            $column++;
        }
        // Mendapatkan rentang seluruh isi tabel
        $tableRange = 'A3:J' . ($column - 1);

        $sheet->getStyle('A3:J3')->getFont()->setBold(true);
        $sheet->getStyle('A3:J3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Mendapatkan objek stylenya
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Penataan horizontal ke tengah
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Penataan vertikal ke tengah
            ],
        ];

        // Set judul

        if ($semester == 2) {
            $semesterText = "Genap";
        } else {
            $semesterText = "Ganjil";
        }

        $judul = ('Jadwal Praktikum' . ' ' . $jenis . ' ' . 'Lab UPA-TIK Semester' . ' ' . $semesterText . ' ' . 'Tahun Ajaran' . ' ' . $thn_awal . '-' . $thn_akhir . ' ' . 'Politeknik Negeri Bandung');
        $sheet->setCellValue('A1', $judul);
        // Gabungkan sel untuk judul
        $sheet->mergeCells('A1:J2');
        // Terapkan gaya penataan ke tengah
        $sheet->getStyle('A1:J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:J2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:J2')->getFont()->setBold(true);
        // Set border
        $sheet->getStyle('A1:J2')->applyFromArray($styleArray);


        $sheet->getStyle('A3:J' . ($column - 1))->applyFromArray($styleArray);
        // Menerapkan gaya ke seluruh isi tabel
        $sheet->getStyle($tableRange)->applyFromArray($style);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        // Generate file name based on $reguler variable
        $filename = 'jadwal_' . $jenis . '.xlsx';
        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max_age=0');
        $writer->save('php://output');
        exit();
    }




    public function filterHari($jenis, $hari)
    {
        $jadwalModel = new JadwalModel();

        $thn_awal = $jadwalModel->getTahunAwal();
        $thn_akhir = $jadwalModel->getTahunAkhir();
        $semester = $jadwalModel->getSemester();

        $jadwal = $jadwalModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', $jenis)->where('jadwal.hari', $hari)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Mata Kuliah');
        $sheet->setCellValue('C3', 'Dosen');
        $sheet->setCellValue('D3', 'Kelas');
        $sheet->setCellValue('E3', 'Jam');
        $sheet->setCellValue('F3', 'Program Studi');
        $sheet->setCellValue('G3', 'Semester');
        $sheet->setCellValue('H3', 'Jenis');
        $sheet->setCellValue('I3', 'Hari');
        $sheet->setCellValue('J3', 'Tahun');
        $column = 4;
        foreach ($jadwal as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 3));
            $sheet->setCellValue('B' . $column, $value['mk']);
            $sheet->setCellValue('C' . $column, $value['nama_dosen']);
            $sheet->setCellValue('D' . $column, $value['kelas']);
            $sheet->setCellValue('E' . $column, $value['jam']);
            $sheet->setCellValue('F' . $column, $value['nama_prodi']);
            $sheet->setCellValue('G' . $column, $value['semester']);
            $sheet->setCellValue('H' . $column, $value['jenis']);
            $sheet->setCellValue('I' . $column, $value['hari']);
            $sheet->setCellValue('J' . $column, $value['thn_awal'] . ' - ' . $value['thn_akhir']);
            $column++;
        }
        // Mendapatkan rentang seluruh isi tabel
        $tableRange = 'A3:J' . ($column - 1);

        $sheet->getStyle('A3:J3')->getFont()->setBold(true);
        $sheet->getStyle('A3:J3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Mendapatkan objek stylenya
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Penataan horizontal ke tengah
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Penataan vertikal ke tengah
            ],
        ];


        // Set judul

        if ($semester == 2) {
            $semesterText = "Genap";
        } else {
            $semesterText = "Ganjil";
        }

        $judul = ('Jadwal Praktikum' . ' ' . $jenis . ' ' . 'Lab UPA-TIK Semester' . ' ' . $semesterText . ' ' . 'Tahun Ajaran' . ' ' . $thn_awal . '-' . $thn_akhir . ' ' . 'Politeknik Negeri Bandung');
        $sheet->setCellValue('A1', $judul);
        // Gabungkan sel untuk judul
        $sheet->mergeCells('A1:J2');
        // Terapkan gaya penataan ke tengah
        $sheet->getStyle('A1:J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:J2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:J2')->getFont()->setBold(true);
        // Set border
        $sheet->getStyle('A1:J2')->applyFromArray($styleArray);


        $sheet->getStyle('A3:J' . ($column - 1))->applyFromArray($styleArray);
        // Menerapkan gaya ke seluruh isi tabel
        $sheet->getStyle($tableRange)->applyFromArray($style);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        // Generate file name based on $reguler variable
        $filename = 'jadwal_' . $jenis . '.xlsx';
        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max_age=0');
        $writer->save('php://output');
        exit();
    }
    public function filterJam($jenis, $jam)
    {
        $jadwalModel = new JadwalModel();

        $thn_awal = $jadwalModel->getTahunAwal();
        $thn_akhir = $jadwalModel->getTahunAkhir();
        $semester = $jadwalModel->getSemester();

        $jadwal = $jadwalModel->joinRuangan()->joinTA()->joinProdi()->joinJam()->where('jenis', $jenis)->where('jam', $jam)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Mata Kuliah');
        $sheet->setCellValue('C3', 'Dosen');
        $sheet->setCellValue('D3', 'Kelas');
        $sheet->setCellValue('E3', 'Jam');
        $sheet->setCellValue('F3', 'Program Studi');
        $sheet->setCellValue('G3', 'Semester');
        $sheet->setCellValue('H3', 'Jenis');
        $sheet->setCellValue('I3', 'Hari');
        $sheet->setCellValue('J3', 'Tahun');
        $column = 4;
        foreach ($jadwal as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 3));
            $sheet->setCellValue('B' . $column, $value['mk']);
            $sheet->setCellValue('C' . $column, $value['nama_dosen']);
            $sheet->setCellValue('D' . $column, $value['kelas']);
            $sheet->setCellValue('E' . $column, $value['jam']);
            $sheet->setCellValue('F' . $column, $value['nama_prodi']);
            $sheet->setCellValue('G' . $column, $value['semester']);
            $sheet->setCellValue('H' . $column, $value['jenis']);
            $sheet->setCellValue('I' . $column, $value['hari']);
            $sheet->setCellValue('J' . $column, $value['thn_awal'] . ' - ' . $value['thn_akhir']);
            $column++;
        }
        // Mendapatkan rentang seluruh isi tabel
        $tableRange = 'A3:J' . ($column - 1);

        $sheet->getStyle('A3:J3')->getFont()->setBold(true);
        $sheet->getStyle('A3:J3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Mendapatkan objek stylenya
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Penataan horizontal ke tengah
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Penataan vertikal ke tengah
            ],
        ];


        // Set judul

        if ($semester == 2) {
            $semesterText = "Genap";
        } else {
            $semesterText = "Ganjil";
        }

        $judul = ('Jadwal Praktikum' . ' ' . $jenis . ' ' . 'Lab UPA-TIK Semester' . ' ' . $semesterText . ' ' . 'Tahun Ajaran' . ' ' . $thn_awal . '-' . $thn_akhir . ' ' . 'Politeknik Negeri Bandung');
        $sheet->setCellValue('A1', $judul);
        // Gabungkan sel untuk judul
        $sheet->mergeCells('A1:J2');
        // Terapkan gaya penataan ke tengah
        $sheet->getStyle('A1:J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:J2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:J2')->getFont()->setBold(true);
        // Set border
        $sheet->getStyle('A1:J2')->applyFromArray($styleArray);

        $sheet->getStyle('A3:J' . ($column - 1))->applyFromArray($styleArray);
        // Menerapkan gaya ke seluruh isi tabel
        $sheet->getStyle($tableRange)->applyFromArray($style);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        // Generate file name based on $jenis variable
        $filename = 'jadwal_' . $jenis . '.xlsx';
        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max_age=0');
        $writer->save('php://output');
        exit();
    }

    public function filterSoftware($id_ruangan)
    {
        $softwareModel = new fasilitas_softwareModel();
        $software = $softwareModel->joinRuangan()->where('f_software.id_ruangan', $id_ruangan)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Gambar');
        $sheet->setCellValue('C3', 'Nama Hardware');
        $sheet->setCellValue('D3', 'Jumlah');
        $sheet->setCellValue('E3', 'Lab');
        $column = 4;
        foreach ($software as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 3));
            $sheet->setCellValue('B' . $column, $value['gambar']);
            $sheet->setCellValue('C' . $column, $value['nama']);
            $sheet->setCellValue('D' . $column, $value['jumlah']);
            $sheet->setCellValue('E' . $column, $value['nama_ruangan']);
            $column++;
        }
        // Mendapatkan rentang seluruh isi tabel
        $tableRange = 'A3:E' . ($column - 1);

        $sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Mendapatkan objek stylenya
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Penataan horizontal ke tengah
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Penataan vertikal ke tengah
            ],
        ];


        // Set judul

        $judul = ('Daftar Software Lab UPA-TIK');
        $sheet->setCellValue('A1', $judul);
        // Gabungkan sel untuk judul
        $sheet->mergeCells('A1:E2');
        // Terapkan gaya penataan ke tengah
        $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:E2')->getFont()->setBold(true);
        // Set border
        $sheet->getStyle('A1:E2')->applyFromArray($styleArray);

        $sheet->getStyle('A3:E' . ($column - 1))->applyFromArray($styleArray);
        // Menerapkan gaya ke seluruh isi tabel
        $sheet->getStyle($tableRange)->applyFromArray($style);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $ruangan = 'Lab UPA-TIK';
        // Generate file name based on $reguler variable
        $filename = 'daftar_Software_' . $ruangan . '.xlsx';
        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max_age=0');
        $writer->save('php://output');
        exit();
    }

    public function filterHardware($id_ruangan)
    {
        $hardwareModel = new fasilitas_hardwareModel();
        $hardware = $hardwareModel->joinRuangan()->where('f_hardware_b.id_ruangan', $id_ruangan)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Gambar');
        $sheet->setCellValue('C3', 'Nama Hardware');
        $sheet->setCellValue('D3', 'Jumlah');
        $sheet->setCellValue('E3', 'Lab');
        $column = 4;
        foreach ($hardware as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 3));
            $sheet->setCellValue('B' . $column, $value['gambar']);
            $sheet->setCellValue('C' . $column, $value['nama']);
            $sheet->setCellValue('D' . $column, $value['jumlah']);
            $sheet->setCellValue('E' . $column, $value['nama_ruangan']);
            $column++;
        }
        // Mendapatkan rentang seluruh isi tabel
        $tableRange = 'A3:E' . ($column - 1);

        $sheet->getStyle('A3:E3')->getFont()->setBold(true);
        $sheet->getStyle('A3:E3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Mendapatkan objek stylenya
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Penataan horizontal ke tengah
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Penataan vertikal ke tengah
            ],
        ];


        // Set judul

        $judul = ('Daftar Hardware Lab UPA-TIK');
        $sheet->setCellValue('A1', $judul);
        // Gabungkan sel untuk judul
        $sheet->mergeCells('A1:E2');
        // Terapkan gaya penataan ke tengah
        $sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:E2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:E2')->getFont()->setBold(true);
        // Set border
        $sheet->getStyle('A1:E2')->applyFromArray($styleArray);


        $sheet->getStyle('A3:E' . ($column - 1))->applyFromArray($styleArray);
        // Menerapkan gaya ke seluruh isi tabel
        $sheet->getStyle($tableRange)->applyFromArray($style);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        $ruangan = 'Lab UPA-TIK';
        // Generate file name based on $reguler variable
        $filename = 'daftar_Hardware_' . $ruangan . '.xlsx';
        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max_age=0');
        $writer->save('php://output');
        exit();
    }


    public function jadwalReguler($jenis)
    {
        $jadwalModel = new JadwalModel();

        $jadwal = $jadwalModel;
        // Fetch required data from the model
        $thn_awal = $jadwal->getTahunAwal();
        $thn_akhir = $jadwalModel->getTahunAkhir();
        $semester = $jadwalModel->getSemester();
        $hari = $jadwalModel->getHari();
        $jam = $jadwalModel->getJam();
        $ruangan = $jadwalModel->getRuangan();
        $jadwal = $jadwalModel->getJadwal($jenis);

        // Calculate the number of rooms
        $jumlahLab = count($ruangan);

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A3', 'Hari');
        $sheet->setCellValue('B3', 'Lab');
        $col = 'C';
        foreach ($jam as $j) {
            $sheet->setCellValue($col . '3', $j->jam);
            $col++;
        }

        // Populate data
        $row = 4;
        foreach ($hari as $index => $h) {
            // Mengecek apakah ini adalah hari pertama atau bukan
            if ($index == 0 || $h != $hari[$index - 1]) {
                // Jika ya, maka cetak hari dan merge kolomnya
                $sheet->setCellValue('A' . $row, $h);
                $sheet->mergeCells('A' . $row . ':A' . ($row + $jumlahLab - 1)); // Menggabungkan sel pada kolom A
                $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER); // Mengatur teks ke tengah
            }
            foreach ($ruangan as $r) {
                $sheet->setCellValue('A' . $row, $h);
                $sheet->setCellValue('B' . $row, $r->nama_ruangan);
                $col = 'C';
                foreach ($jam as $j) {
                    $kelas = '';
                    foreach ($jadwal as $k) {
                        if ($k->hari == $h && $k->id_ruangan == $r->id_ruangan && $k->id_jam == $j->id) {
                            $kelas = $k->kelas;
                            break;
                        }
                    }
                    $sheet->setCellValue($col . $row, $kelas);
                    $col++;
                }
                $row++;
            }
        }




        $tableRange = 'A3:M' . ($row - 1);

        $sheet->getStyle('A3:M3')->getFont()->setBold(true);
        $sheet->getStyle('A3:M3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Mendapatkan objek stylenya
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Penataan horizontal ke tengah
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Penataan vertikal ke tengah
            ],
        ];
        // Set judul

        if ($semester == 2) {
            $semesterText = "Genap";
        } else {
            $semesterText = "Ganjil";
        }

        $judul = ('Jadwal Praktikum' . ' ' . $jenis . ' ' . 'Lab UPA-TIK Semester' . ' ' . $semesterText . ' ' . 'Tahun Ajaran' . ' ' . $thn_awal . '-' . $thn_akhir . ' ' . 'Politeknik Negeri Bandung');
        $sheet->setCellValue('A1', $judul);
        // Gabungkan sel untuk judul
        $sheet->mergeCells('A1:M2');
        // Terapkan gaya penataan ke tengah
        $sheet->getStyle('A1:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:M2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:M2')->getFont()->setBold(true);
        // Set border
        $sheet->getStyle('A1:M2')->applyFromArray($styleArray);


        $sheet->getStyle('A3:M' . ($row - 1))->applyFromArray($styleArray);
        // Menerapkan gaya ke seluruh isi tabel
        $sheet->getStyle($tableRange)->applyFromArray($style);

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);
        $sheet->getColumnDimension('K')->setAutoSize(true);
        $sheet->getColumnDimension('L')->setAutoSize(true);
        $sheet->getColumnDimension('M')->setAutoSize(true);


        // Save spreadsheet to file
        $writer = new Xlsx($spreadsheet);
        $filename = 'data-Jadwal-' . $jenis . '.xlsx'; // Set file name
        $writer->save($filename);

        // Set header for Excel file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Output file to browser
        readfile($filename);
        exit();
    }

    public function excelUser()
    {
        $userModel = new UserModel();


        $user = $userModel->joinProdi()->findAll();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A3', 'No');
        $sheet->setCellValue('B3', 'Kode Lengkap');
        $sheet->setCellValue('C3', 'Nama Pengguna');
        $sheet->setCellValue('D3', 'Email');
        $sheet->setCellValue('E3', 'Status');
        $sheet->setCellValue('F3', 'Nomor Telepon');
        $sheet->setCellValue('G3', 'Prodi/Unit');
        $column = 4;
        foreach ($user as $key => $value) {
            $sheet->setCellValue('A' . $column, ($column - 3));
            $sheet->setCellValue('B' . $column, $value['nama_user']);
            $sheet->setCellValue('C' . $column, $value['username']);
            $sheet->setCellValue('D' . $column, $value['email']);
            $sheet->setCellValue('E' . $column, $value['status']);
            $sheet->setCellValue('F' . $column, $value['no_telp']);
            $sheet->setCellValue('G' . $column, $value['nama_prodi']);
            $column++;
        }
        // Mendapatkan rentang seluruh isi tabel
        $tableRange = 'A3:G' . ($column - 1);

        $sheet->getStyle('A3:G3')->getFont()->setBold(true);
        $sheet->getStyle('A3:G3')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFFF00');
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];
        // Mendapatkan objek stylenya
        $style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Penataan horizontal ke tengah
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Penataan vertikal ke tengah
            ],
        ];

        // Set judul


        $judul = ('Data Akun User LAB UPA-TIK Politeknik Negeri Bandung');
        $sheet->setCellValue('A1', $judul);
        // Gabungkan sel untuk judul
        $sheet->mergeCells('A1:G2');
        // Terapkan gaya penataan ke tengah
        $sheet->getStyle('A1:G2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:G2')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A1:G2')->getFont()->setBold(true);
        // Set border
        $sheet->getStyle('A1:G2')->applyFromArray($styleArray);


        $sheet->getStyle('A3:G' . ($column - 1))->applyFromArray($styleArray);
        // Menerapkan gaya ke seluruh isi tabel
        $sheet->getStyle($tableRange)->applyFromArray($style);


        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        $writer = new Xlsx($spreadsheet);
        // Generate file name based on $reguler variable
        $filename = 'data-User.xlsx';
        header('Content-Type: appliaction/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename);
        header('Cache-Control: max_age=0');
        $writer->save('php://output');
        exit();
    }
}
