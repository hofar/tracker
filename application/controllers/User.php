<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
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
            "title" => 'App Kasir',
            "metadesc" => 'Manajemen User',
            "content" => $this->parser->parse('user/manajemen_view', $array, true)
        );

        $this->parser->parse("template", $data);
    }

    public function ajax_list()
    {
        $this->load->helper('url');

        $r_role = $this->Role_model->get_role();
        $row_role = $r_role->row();

        $list = $this->User_model->get_datatables();
        $data = array();
        $no = filter_input(INPUT_POST, 'start');
        foreach ($list as $user) {
            $no++;
            $row = array();
            $r_role = $this->Role_model->get_role($user->role_id);
            $row_role = $r_role->row();
            $action_button = "";
            if ($user->user_id !== "admin") {
                $action_button = action_button($user->id, 'ubah_data', 'hapus_data');
            }

            $row[] = '<input type="checkbox" class="data-check" value="' . $user->id . '">';
            $row[] = $user->nama;
            $row[] = $user->user_id;
            $row[] = $row_role->name;
            $row[] = $action_button;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->User_model->count_all(),
            "recordsFiltered" => $this->User_model->count_filtered(),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function ajax_edit($id)
    {
        $data = $this->User_model->get_by_id($id);

        echo json_encode($data);
    }

    public function ajax_add()
    {
        $this->_validate();

        $data = array(
            'nama' => htmlspecialchars($this->input->post('name', true)),
            'user_id' => htmlspecialchars($this->input->post('user_id', true)),
            'gambar' => 'default.jpg',
            'password' => password_hash($this->input->post('password1'), PASSWORD_DEFAULT),
            'role_id' => htmlspecialchars($this->input->post('role_select', true)),
            'is_active' => 1,
            'create_at' => date('Y-m-d H:i:s')
        );

        $insert = $this->User_model->save($data);

        echo json_encode(array("status" => true));
    }

    public function ajax_update()
    {
        $this->_validate();

        $data = array(
            'nama' => htmlspecialchars($this->input->post('name', true)),
            'user_id' => htmlspecialchars($this->input->post('user_id', true)),
            'role_id' => htmlspecialchars($this->input->post('role_select', true)),
        );

        $update = $this->User_model->update(array('id' => $this->input->post('id')), $data);

        echo json_encode(array("status" => true));
    }

    public function ajax_delete($id)
    {
        $this->User_model->delete_by_id($id);
        echo json_encode(array("status" => true));
    }

    public function ajax_bulk_delete()
    {
        $list_id = $this->input->post('id');
        foreach ($list_id as $id) {
            $this->User_model->delete_by_id($id);
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

        if ($this->input->post('user_id') == '') {
            $data['inputerror'][] = 'user_id';
            $data['error_string'][] = 'User ID tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('password1') == '') {
            $data['inputerror'][] = 'password1';
            $data['error_string'][] = 'Password tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('password2') == '') {
            $data['inputerror'][] = 'password2';
            $data['error_string'][] = 'Password tidak boleh kosong';
            $data['status'] = false;
        }

        if ($this->input->post('password1') != $this->input->post('password2')) {
            $data['inputerror'][] = 'password1';
            $data['inputerror'][] = 'password2';
            $data['error_string'][] = 'Konfirmasi password berbeda';
            $data['error_string'][] = '';
            $data['status'] = false;
        }

        if ($this->input->post('role_select') == '') {
            $data['inputerror'][] = 'role_select';
            $data['error_string'][] = 'Role Select Kosong';
            $data['status'] = false;
        }

        if ($data['status'] === false) {
            echo json_encode($data);
            exit();
        }
    }
}
