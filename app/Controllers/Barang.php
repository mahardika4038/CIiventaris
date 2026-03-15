<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\LokasiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;

class Barang extends BaseController
{
    protected $bModel;
    protected $lModel;

    public function __construct()
    {
        $this->bModel = new BarangModel();
        $this->lModel = new LokasiModel();
    }

    // 1. TAMPILAN UTAMA
    public function index()
    {
        $data = [
            'title' => 'Manajemen Barang',
            'rows'  => $this->bModel->select('barang.*, lokasi.nama_lokasi')
                                    ->join('lokasi', 'lokasi.id = barang.lokasi_id', 'left')
                                    ->findAll()
        ];
        return view('barang/index', $data);
    }

    // 2. FORM TAMBAH
    public function create()
    {
        $data = [
            'title'       => 'Tambah Barang',
            'list_lokasi' => $this->lModel->findAll()
        ];
        return view('barang/create', $data);
    }

    // 3. SIMPAN DATA (DENGAN KODE OTOMATIS)
    public function store()
    {
        // LOGIKA KODE BARANG OTOMATIS (BRG-0001)
        $lastBarang = $this->bModel->orderBy('id', 'DESC')->first();
        $lastId = $lastBarang ? (int)substr($lastBarang['kode_barang'], 4) : 0;
        $newKode = 'BRG-' . sprintf('%04d', $lastId + 1);

        $this->bModel->save([
            'kode_barang' => $newKode,
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'lokasi_id'   => $this->request->getPost('lokasi_id'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'stok'        => (int)$this->request->getPost('stok'),
            'status'      => 'Tersedia'
        ]);

        return redirect()->to('/barang')->with('msg', 'Barang berhasil disimpan: ' . $newKode);
    }

    // 4. FORM EDIT
    public function edit($id)
    {
        $data = [
            'title'       => 'Edit Barang',
            'row'         => $this->bModel->find($id),
            'list_lokasi' => $this->lModel->findAll()
        ];
        return view('barang/edit', $data);
    }

    // 5. UPDATE DATA
    public function update($id)
    {
        $this->bModel->update($id, [
            'nama_barang' => $this->request->getPost('nama_barang'),
            'kategori'    => $this->request->getPost('kategori'),
            'lokasi_id'   => $this->request->getPost('lokasi_id'),
            'kondisi'     => $this->request->getPost('kondisi'),
            'stok'        => (int)$this->request->getPost('stok')
        ]);

        return redirect()->to('/barang')->with('msg', 'Data barang berhasil diperbarui!');
    }

    // 6. HAPUS DATA
    public function delete($id)
    {
        $this->bModel->delete($id);
        return redirect()->to('/barang')->with('msg', 'Barang berhasil dihapus!');
    }

    // --- FITUR QR CODE & CETAK ---

    // 7. CETAK SATUAN
    public function cetakSatuan($id)
    {
        $row = $this->bModel->find($id);
        if (!$row) return redirect()->back();

        $data = [
            'rows'  => [$row],
            'title' => 'Cetak Label ' . $row['kode_barang']
        ];
        return view('barang/cetak_qr', $data);
    }

    // 8. CETAK SEMUA (MASSAL)
    public function cetakSemua()
    {
        $data = [
            'rows'  => $this->bModel->findAll(),
            'title' => 'Cetak Semua Label QR'
        ];
        return view('barang/cetak_qr', $data);
    }

    // --- FITUR EXCEL ---

    // 9. DOWNLOAD TEMPLATE EXCEL
    public function downloadTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $header = ['nama_barang', 'kategori', 'lokasi_id', 'kondisi', 'stok'];
        $sheet->fromArray($header, NULL, 'A1');

        // Contoh Data
        $sheet->setCellValue('A2', 'Contoh Laptop');
        $sheet->setCellValue('B2', 'Elektronik');
        $sheet->setCellValue('C2', '1'); 
        $sheet->setCellValue('D2', 'Baik');
        $sheet->setCellValue('E2', '10');

        $writer = new Xlsx($spreadsheet);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Template_Barang.xlsx"');
        $writer->save('php://output');
        exit();
    }

    // 10. IMPORT EXCEL KE DATABASE
    public function import()
    {
        $file = $this->request->getFile('file_excel');
        if($file && $file->isValid() && !$file->hasMoved()){
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load($file->getTempName());
            $dataArray = $spreadsheet->getActiveSheet()->toArray();

            foreach($dataArray as $key => $row){
                if($key == 0 || empty($row[0])) continue; // Skip header

                // Logika Auto Kode di dalam loop import
                $lastBarang = $this->bModel->orderBy('id', 'DESC')->first();
                $lastId = $lastBarang ? (int)substr($lastBarang['kode_barang'], 4) : 0;
                $newKode = 'BRG-' . sprintf('%04d', $lastId + 1);

                $this->bModel->save([
                    'kode_barang' => $newKode,
                    'nama_barang' => $row[0],
                    'kategori'    => $row[1],
                    'lokasi_id'   => $row[2],
                    'kondisi'     => $row[3],
                    'stok'        => (int)$row[4],
                    'status'      => 'Tersedia'
                ]);
            }
            return redirect()->to('/barang')->with('msg', 'Import data berhasil!');
        }
        return redirect()->to('/barang')->with('msg', 'File tidak valid!');
    }

    // 11. EXPORT DATA KE EXCEL
    public function export()
    {
        $rows = $this->bModel->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['No', 'Kode', 'Nama Barang', 'Kategori', 'Stok', 'Kondisi'], NULL, 'A1');
        
        $num = 2;
        foreach($rows as $k => $v) {
            $sheet->setCellValue('A'.$num, $k+1);
            $sheet->setCellValue('B'.$num, $v['kode_barang']);
            $sheet->setCellValue('C'.$num, $v['nama_barang']);
            $sheet->setCellValue('D'.$num, $v['kategori']);
            $sheet->setCellValue('E'.$num, $v['stok']);
            $sheet->setCellValue('F'.$num, $v['kondisi']);
            $num++;
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Laporan_Inventaris.xlsx"');
        (new Xlsx($spreadsheet))->save('php://output');
        exit();
    }
}