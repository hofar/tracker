<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Keterangan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        is_logged_in();
    }

    public function index()
    {
        $this->manajemen();
    }

    public function manajemen()
    {
        $array = array();

        $data = array(
            "title" => 'Tracker Graph',
            "metadesc" => 'Kelola Keterangan',
            "content" => $this->parser->parse('keterangan/manajemen_view', $array, true)
        );

        $this->parser->parse("template", $data);
    }

    public function ajax_list()
    {
        $this->load->helper('url');

        $list = $this->HistoryKeterangan_model->get_datatables();
        $data = array();
        $no = filter_input(INPUT_POST, 'start');
        foreach ($list as $item) {
            $no++;
            $row = array();

            // $row[] = '<input type="checkbox" class="data-check" value="' . $item->id . '">';
            $row[] = $no;
            $row[] = $item->sasaran_program;
            $row[] = $item->type;
            $row[] = badge_type($item->status);
            $row[] = $item->keterangan;
            $row[] = custom_date($item->created_at);
            // $row[] = action_button($item->id, 'ubah_data', 'hapus_data');

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->HistoryKeterangan_model->count_all(),
            "recordsFiltered" => $this->HistoryKeterangan_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->HistoryKeterangan_model->get_by_id($id);

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $this->_validate();

        $data = array(
            'type' => $this->input->post('type'),
            'status' => $this->input->post('status'),
            'id_program_kerja' => $this->input->post('id_program_kerja'),
            'keterangan' => $this->input->post('keterangan')
        );

        $insert_id = $this->HistoryKeterangan_model->save($data);

        // update status in program_kerja table

        $status = data_status();

        $data = array(
            'status' => $this->input->post('status')
        );

        switch ($this->input->post('status')) {
            case $status[1]:
                $data['value'] = 100;
                break;
        }

        $this->ProgramKerja_model->update(array('id' => $this->input->post('id_program_kerja')), $data);

        echo json_encode(array("status" => true));
    }

    public function ajax_add_keterangan()
    {
        $this->_validate_v2();

        $data = array(
            'id_program_kerja' => $this->input->post('id_program_kerja'),
            'status' => $this->input->post('status'),
            'keterangan' => $this->input->post('keterangan')
        );

        $insert_id = $this->HistoryKeterangan_model->save($data);

        // update status in program_kerja table

        $status = data_status();

        $data = array(
            'value' => $this->input->post('value'),
            'status' => $this->input->post('status'),
            'end_date' => null,
        );

        switch ($this->input->post('status')) {
            case $status[1]:
                $data['value'] = 100;
                $data['end_date'] = date('Y-m-d H:i:s');
                break;
        }

        $this->ProgramKerja_model->update(array('id' => $this->input->post('id_program_kerja')), $data);

        echo json_encode(array("status" => true));
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = array(
            'type' => $this->input->post('type'),
            'status' => $this->input->post('status'),
            'id_program_kerja' => $this->input->post('id_program_kerja'),
            'keterangan' => $this->input->post('keterangan')
        );

        $this->HistoryKeterangan_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => true));
    }

    public function ajax_delete($id)
    {
        $this->HistoryKeterangan_model->delete_by_id($id);
        echo json_encode(array("status" => true));
    }

    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->HistoryKeterangan_model->delete_by_id($id);
        }
        echo json_encode(array("status" => true));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = true;

        if ($this->input->post('id_program_kerja') == '') {
            $data['inputerror'][] = 'id_program_kerja';
            $data['error_string'][] = 'Sasaran Program tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('type') == '') {
            $data['inputerror'][] = 'type';
            $data['error_string'][] = 'Status tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('keterangan') == '') {
            $data['inputerror'][] = 'keterangan';
            $data['error_string'][] = 'Keterangan tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('created_at') == '') {
            $data['inputerror'][] = 'created_at';
            $data['error_string'][] = 'Tanggal &amp; Waktu tidak boleh kosong';
            $data['status'] = false;
        }

        if ($data['status'] === false) {
            echo json_encode($data);
            exit();
        }
    }

    private function _validate_v2()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = true;

        if ($this->input->post('id_program_kerja') == '') {
            $data['inputerror'][] = 'id_program_kerja';
            $data['error_string'][] = 'Sasaran Program tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('value') == '') {
            $data['inputerror'][] = 'value';
            $data['error_string'][] = 'Value tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('status') == '') {
            $data['inputerror'][] = 'status';
            $data['error_string'][] = 'Status tidak boleh kosong';
            $data['status'] = false;
        }

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

    public function get_keterangan()
    {
        $status = false;
        $opt = array();

        $r_keterangan = $this->HistoryKeterangan_model->get_keterangan();

        if ($r_keterangan) {
            $status = true;
            foreach ($r_keterangan->result() as $key => $value) {
                $opt[] = $value;
            }
        }

        $response = array(
            'status' => $status,
            'opt' => $opt,
        );
        echo json_encode($response);
    }
}
