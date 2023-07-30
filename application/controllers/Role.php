<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Role extends CI_Controller
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
            "metadesc" => 'Manajemen Role',
            "content" => $this->parser->parse('role/manajemen_view', $array, true)
        );

        $this->parser->parse("template", $data);
    }

    public function ajax_list()
    {
        $this->load->helper('url');

        $list = $this->Role_model->get_datatables();
        $data = array();
        $no = filter_input(INPUT_POST, 'start');
        foreach ($list as $role) {
            $no++;
            $row = array();
            $action_button = "";
            if ($role->name !== "Administrator") {
                $action_button = action_button($role->id, 'ubah_data', 'hapus_data');
            }

            $row[] = '<input type="checkbox" class="data-check" value="' . $role->id . '">';
            $row[] = $no;
            $row[] = $role->name;
            $row[] = ($role->is_super == '1' ? 'Yes' : 'No');
            $row[] = $action_button;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Role_model->count_all(),
            "recordsFiltered" => $this->Role_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->Role_model->get_role($id)->row();

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $this->_validate();

        $akses_super = $this->input->post('akses_super', true);

        if (is_string($akses_super)) {
            $akses_super = htmlspecialchars($akses_super);
        } else {
            $akses_super = '0';
        }

        $data = array(
            'name' => htmlspecialchars($this->input->post('name', true)),
            'is_super' => $akses_super,
        );

        $insert_id = $this->Role_model->save($data);

        echo json_encode(array("status" => true));
    }

    public function ajax_update()
    {
        $this->_validate();

        $akses_super = $this->input->post('akses_super', true);

        if (is_string($akses_super)) {
            $akses_super = htmlspecialchars($akses_super);
        } else {
            $akses_super = '0';
        }

        $data = array(
            'name' => htmlspecialchars($this->input->post('name', true)),
            'is_super' => $akses_super,
        );

        $update = $this->Role_model->update(array('id' => $this->input->post('id')), $data);

        echo json_encode(array("status" => true));
    }

    public function ajax_delete($id)
    {
        $this->Role_model->delete_by_id($id);
        echo json_encode(array("status" => true));
    }

    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->Role_model->delete_by_id($id);
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
            $data['error_string'][] = 'Nama tidak boleh kosong';
            $data['status'] = false;
        }

        if ($data['status'] === false) {
            echo json_encode($data);
            exit();
        }
    }

    public function get_role()
    {
        $status = false;
        $opt = array();

        $r_role = $this->Role_model->get_role();

        if ($r_role) {
            $status = true;
            foreach ($r_role->result() as $key => $value) {
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
