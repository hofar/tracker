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

        header('Content-Type: application/json');
        echo json_encode($results);
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
        $array = array();

        $data = array(
            "title" => "Tracker Graph",
            "metadesc" => "Sistem Informasi Grafik Jaringan dan Aplikasi",
            "content" => $this->load->view("programkerja/input", "", TRUE)
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
            $row[] = '<input type="checkbox" class="data-check" value="' . $item->id . '">';
            $row[] = $item->name;
            $row[] = number_format($item->value, 0, '', '');
            $row[] = $item->type;

            //add html for action
            $row[] = '<div class="btn-group" role="group" aria-label="Group action">'
                . '<a href="#" title="Edit" onclick="ubah_data(' . $item->id . ')" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil-square"></i></i></a>'
                . '<a href="#" title="Hapus" onclick="hapus_data(' . $item->id . ')" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></a>'
                . '</div>';

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
        $data = $this->ProgramKerja_model->get_by_id($id);

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $this->_validate();

        $data = array(
            'name' => $this->input->post('name'),
            'value' => $this->input->post('value'),
            'type' => $this->input->post('type')
        );

        $insert = $this->ProgramKerja_model->save($data);

        echo json_encode(array("status" => true));
    }

    public function ajax_update()
    {
        $this->_validate();
        $data = array(
            'nama' => $this->input->post('nama'),
            'harga' => $this->input->post('harga')
        );
        $this->ProgramKerja_model->update(array('id' => $this->input->post('id')), $data);
        echo json_encode(array("status" => true));
    }

    public function ajax_delete($id)
    {
        $this->ProgramKerja_model->delete_by_id($id);
        echo json_encode(array("status" => true));
    }

    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->ProgramKerja_model->delete_by_id($id);
        }
        echo json_encode(array("status" => true));
    }

    private function _validate()
    {
        $data = array();
        $data['error_string'] = array();
        $data['inputerror'] = array();
        $data['status'] = true;

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

        if ($data['status'] === false) {
            echo json_encode($data);
            exit();
        }
    }
}
