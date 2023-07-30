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

                    $input_name = $col[0];
                    $input_value = $col[1];
                    $input_type = $col[2];
                    $input_status = $col[3];
                    $input_start_date = $col[4];
                    $input_end_date = $col[5];
                    $input_keterangan = $col[6];

                    $items = array(
                        "name" => $input_name,
                        "value" => $input_value,
                        "type" => $input_type,
                        "status" => $input_status,
                        // "start_date" => $input_start_date,
                        // "end_date" => $input_end_date,
                        "keterangan" => $input_keterangan,
                    );
                    // ...

                    // $data_dump["data"][] = $items;
                    // Lakukan penyimpanan data ke database sesuai kebutuhan
                    // Contoh: menggunakan model CodeIgniter

                    $status = data_status();

                    switch ($input_status) {
                        case $status[0]: // In Progress
                            $items['start_date'] = $input_start_date;
                            $items['end_date'] = null;
                            break;
                        case $status[1]: // Completed
                            $items['start_date'] = $input_start_date;
                            $items['end_date'] = $input_end_date;
                            break;
                        case $status[2]: // Not Started
                            $items['start_date'] = null;
                            $items['end_date'] = null;
                            break;
                    }

                    $insert_id = $this->ProgramKerja_model->save($items);

                    // save keterangan

                    $items = array(
                        'id_program_kerja' => $insert_id,
                        'status' => $input_status,
                        'keterangan' => $input_keterangan
                    );

                    $insert_id = $this->HistoryKeterangan_model->save($items);
                }

                // $this->ProgramKerja_model->insert_batch($data_dump["data"]);
            }
        }

        $this->session->set_flashdata('status', $data_dump["status"]);

        redirect(site_url('ProgramKerja/data'));
    }
}
