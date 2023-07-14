<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Import extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function do_import()
    {
        $file_name = "excel_file";

        $date = date('YmdHis');
        $uploadedFileName = $_FILES[$file_name]['name'];
        $extension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);
        $newFileName = $date . '.' . $extension;

        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'xlsx';
        $config['max_size'] = 2048;
        $config['encrypt_name'] = FALSE;
        $config['file_name'] = $newFileName;

        $this->load->library('upload', $config);

        $data_dump = [];

        if (!$this->upload->do_upload($file_name)) {
            $error = $this->upload->display_errors();
            // Tangani jika terjadi kesalahan saat mengunggah file
            $data_dump["status"] = $error;
        } else {
            $data_file = $this->upload->data();
            // Proses selanjutnya setelah file berhasil diunggah
            $data_dump["status"] = "File berhasil diunggah!";
            $data_dump["nama_file"] =  $data_file['file_name'];
            $data_dump["ukuran_file"] =  $data_file['file_size'] . " KB";

            $file_path = $data_file['full_path'];

            // Membaca file Excel
            $spreadsheet = IOFactory::load($file_path);
            $worksheet = $spreadsheet->getActiveSheet();

            // Mendapatkan data dari sheet pertama
            $rows = $worksheet->toArray();
            $rows = array_slice($rows, 1);

            $data_dump["length"] = count($rows);

            if (count($rows) <= 0) {
                $data_dump["status"] = "Tidak ada data";
            } else {
                // Loop melalui baris dan lakukan pemrosesan
                foreach ($rows as $col) {
                    // Ambil nilai dari setiap kolom dan lakukan pemrosesan sesuai kebutuhan
                    $items = [];

                    $items["name"] = $col[0];
                    $items["value"] = $col[1];
                    $items["type"] = $col[2];
                    $items["status"] = $col[3];
                    $items["start_date"] = $col[4];
                    $items["end_date"] = $col[5];
                    $items["keterangan"] = $col[6];
                    // ...

                    $data_dump["data"][] = $items;
                    // Lakukan penyimpanan data ke database sesuai kebutuhan
                    // Contoh: menggunakan model CodeIgniter
                }

                $this->ProgramKerja_model->insert_batch($data_dump["data"]);
            }
        }

        $this->session->set_flashdata('status', $data_dump["status"]);

        redirect(site_url('programkerja/data'));
    }
}
