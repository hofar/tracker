<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProgramKerja extends CI_Controller
{

    public $ProgramKerja_model;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $type = $this->input->get('type'); // Mendapatkan nilai parameter 'type' dari URL

        if (!empty($type)) {
            $results = $this->ProgramKerja_model->getProgramKerjaData($type);
        } else {
            $results = $this->ProgramKerja_model->getProgramKerjaData();
        }

        $data = array();
        foreach ($results as $key => $value) {
            $row = array();

            /**
             * id: "1",
             * name: "Pemenuhan Kebutuhan PC, NB & Printer",
             * value: "71",
             * type: "Network",
             * keterangan: null,
             * status: "In Progress",
             * start_date: null,
             * end_date: null
             */
            $row['id'] = $value->id;
            $row['name'] = $value->name;
            $row['value'] = $value->value;
            $row['info'] = $value->status;

            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getDataNetwork()
    {
        $results = $this->ProgramKerja_model->getProgramKerjaData("Network");

        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function getDataAplikasi()
    {
        $results = $this->ProgramKerja_model->getProgramKerjaData("Aplikasi");

        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function data()
    {
        is_logged_in();

        $array = array();
        $user_list = $this->User_model->get_all()->result();

        $data = array(
            "title" => "Tracker Graph",
            "metadesc" => "Sistem Informasi Grafik Jaringan dan Aplikasi",
            "content" => $this->load->view("programkerja/data", array(
                "user_list" => $user_list,
            ), TRUE)
        );

        $this->parser->parse("template", $data);
    }

    public function ajax_list()
    {
        $list = $this->ProgramKerja_model->get_datatables();
        $data = array();
        $no = filter_input(INPUT_POST, 'start');
        foreach ($list as $item) {
            $no++;
            $row = array();
            $row[] = checkbox($item->id);
            $row[] = $no;
            $row[] = $item->nama_user;
            $row[] = $item->name;
            $row[] = number_format($item->value, 0, '', '');
            $row[] = $item->type;
            $row[] = badge_type($item->status);
            $row[] = custom_date($item->start_date);
            $row[] = custom_date($item->end_date);

            /**
             * keterangan:
             * 1. saat admin input in progress 0, komentar "test 1", tersimpan
             * 2. kemudian saat admin complete 100, komentar "selesai, juga tersimpan"
             * 3. admin tetap bisa melihat komentar semua progress, dari 0 sampai 100
             * 4. ada tanggal dan waktu nya
             * 5. bisa ditraik ya mas report history nya.
             * 
             */

            // $group_keterangan = '';
            // $keterangan = $this->HistoryKeterangan_model->get_by_program_kerja_id($item->id);
            // if ($keterangan) {
            //     $group_keterangan .= '<ul class="list-group list-group-numbered mb-4">';
            //     foreach ($keterangan as $key => $value) {
            //         $group_keterangan .= '<li class="list-group-item d-flex justify-content-between align-items-start">';
            //         $group_keterangan .= '<div class="ms-2 me-auto">';
            //         $group_keterangan .= '<div class="fw-bold">' . ($value->keterangan) . '</div>';
            //         $group_keterangan .= '<span class="text-body-secondary">' . custom_date($value->created_at) . '</span>';
            //         $group_keterangan .= '</div>';
            //         $group_keterangan .= '<span class="badge bg-secondary rounded-pill">' . ($value->status) . '</span>';
            //         $group_keterangan .= '</li>';
            //     }
            //     $group_keterangan .= '</ul>';
            // }

            // $group_keterangan .= '<button type="button" class="btn btn-sm btn-outline-secondary btn-add-keterangan" data-id="' . $item->id . '" data-status="' . $item->status . '"><i class="bi bi-plus-circle"></i> Tambah Keterangan</button>';

            // $row[] = custom_div($item->keterangan);
            $row[] = action_button_v2($item, ['keterangan', 'ubah_data', 'hapus_data']);

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->ProgramKerja_model->count_all(),
            "recordsFiltered" => $this->ProgramKerja_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        is_logged_in();

        $data = $this->ProgramKerja_model->get_by_id($id);

        echo json_encode($data);
    }

    public function ajax_add()
    {
        is_logged_in();

        $this->_validate();

        $data = array(
            'id_user' => $this->input->post('id_user'),
            'name' => $this->input->post('name'),
            'value' => $this->input->post('value'),
            'type' => $this->input->post('type'),
            'status' => $this->input->post('status'),
            'keterangan' => $this->input->post('keterangan'),
        );

        $status = data_status();

        switch ($this->input->post('status')) {
            case $status[0]: // In Progress
                $data['start_date'] = $this->input->post('start_date');
                $data['end_date'] = null;
                break;
            case $status[1]: // Completed
                $data['start_date'] = $this->input->post('start_date');
                $data['end_date'] = $this->input->post('end_date');
                break;
            case $status[2]: // Not Started
                $data['start_date'] = null;
                $data['end_date'] = null;
                break;
        }

        $insert_id = $this->ProgramKerja_model->save($data);

        // save keterangan

        $data = array(
            'id_program_kerja' => $insert_id,
            'status' => $this->input->post('status'),
            'keterangan' => $this->input->post('keterangan')
        );

        $insert_id = $this->HistoryKeterangan_model->save($data);

        echo json_encode(array("status" => true));
    }

    public function ajax_update()
    {
        is_logged_in();

        $this->_validate();

        $data = array(
            'id_user' => $this->input->post('id_user'),
            'name' => $this->input->post('name'),
            'value' => $this->input->post('value'),
            'type' => $this->input->post('type'),
            'status' => $this->input->post('status'),
            // 'start_date' => $this->input->post('start_date'),
            // 'end_date' => $this->input->post('end_date'),
            'keterangan' => $this->input->post('keterangan'),
        );

        $status = data_status();

        switch ($this->input->post('status')) {
            case $status[0]: // In Progress
                $data['start_date'] = $this->input->post('start_date');
                $data['end_date'] = null;
                break;
            case $status[1]: // Completed
                $data['start_date'] = $this->input->post('start_date');
                $data['end_date'] = $this->input->post('end_date');
                break;
            case $status[2]: // Not Started
                $data['start_date'] = null;
                $data['end_date'] = null;
                break;
        }

        $this->ProgramKerja_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => true));
    }

    public function ajax_delete($id)
    {
        is_logged_in();

        $this->ProgramKerja_model->delete_by_id($id);
        echo json_encode(array("status" => true));
    }

    public function ajax_bulk_delete()
    {
        is_logged_in();

        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->ProgramKerja_model->delete_by_id($id);
        }
        echo json_encode(array("status" => true));
    }

    private function _validate()
    {
        is_logged_in();

        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = true;

        if ($this->input->post('id_user') == '') {
            $data['inputerror'][] = 'id_user';
            $data['error_string'][] = 'User ID tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('name') == '') {
            $data['inputerror'][] = 'name';
            $data['error_string'][] = 'Name tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('value') == '') {
            $data['inputerror'][] = 'value';
            $data['error_string'][] = 'Value tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('type') == '') {
            $data['inputerror'][] = 'type';
            $data['error_string'][] = 'Type tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status tidak boleh kosong';
            $data['status'] = false;
        }

        // if ($this->input->post('start_date') == '') {
        //     $data['inputerror'][] = 'start_date';
        //     $data['error_string'][] = 'Start Date tidak boleh kosong';
        //     $data['status'] = false;
        // }

        // if ($this->input->post('end_date') == '') {
        //     $data['inputerror'][] = 'end_date';
        //     $data['error_string'][] = 'End Date tidak boleh kosong';
        //     $data['status'] = false;
        // }

        if ($this->input->post('keterangan') == '') {
            $data['inputerror'][] = 'keterangan';
            $data['error_string'][] = 'Keterangan tidak boleh kosong';
            $data['status'] = false;
        }

        if ($data['status'] === false) {
            echo json_encode($data);
            exit();
        }
    }

    public function download_template()
    {
        is_logged_in();

        $templatePath = './database/template_import_excel.xlsx'; // Path menuju file format Excel

        // Cek apakah file format Excel tersedia
        if (file_exists($templatePath)) {
            // Tentukan header response
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="format_template.xlsx"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($templatePath));

            // Baca file format Excel dan kirimkan sebagai respons ke klien
            readfile($templatePath);
            exit;
        } else {
            // Tangani jika file format Excel tidak ditemukan
            echo "File format Excel tidak tersedia.";
        }
    }

    public function get_csrf_token()
    {
        is_logged_in();

        $csrfToken = $this->security->get_csrf_hash();
        echo json_encode(['csrfToken' => $csrfToken]);
    }
}
