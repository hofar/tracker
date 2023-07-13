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
}
