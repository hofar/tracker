<?php

defined("BASEPATH") or exit("No direct script access allowed");

class QRCode extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->generator();
    }

    public function generator()
    {
        is_logged_in();

        $array = array();

        $data = array(
            "title" => "QR Code - Generator",
            "metadesc" => "Generate QR Code",
            "content" => $this->load->view("qrcode/generator", $array, TRUE)
        );

        $this->parser->parse("template", $data);
    }

    public function insertToken()
    {
        is_logged_in();

        // Tangkap data JSON dari permintaan POST
        $dataJSON = file_get_contents('php://input');

        // Ubah data JSON menjadi array menggunakan json_decode
        $data = json_decode($dataJSON, true);

        // Tangkap token dari array data
        $token = $data['token'];

        // Panggil model untuk memasukkan data token ke dalam tabel
        $inserted_id = $this->Qrcode_model->insertToken($token);

        if ($inserted_id) {
            // Jika data berhasil dimasukkan ke dalam tabel, kirimkan respon berhasil ke AJAX
            echo json_encode(array('status' => 'success', 'message' => 'Token inserted successfully'));
        } else {
            // Jika terjadi kesalahan saat memasukkan data, kirimkan respon gagal ke AJAX
            echo json_encode(array('status' => 'error', 'message' => 'Failed to insert token'));
        }
    }

    public function setFlagToOne()
    {
        // Ambil data token dari permintaan AJAX POST
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['token'])) {
            // Panggil model untuk mengeset flag menjadi 1 berdasarkan token yang diberikan
            $result = $this->Qrcode_model->setFlagToOne($data['token']);

            if ($result) {
                // Jika data berhasil diubah, kirimkan respon berhasil ke AJAX
                http_response_code(200);
            } else {
                // Jika terjadi kesalahan saat mengubah data, kirimkan respon gagal ke AJAX
                http_response_code(500);
            }
        } else {
            // Jika token tidak diberikan, kirimkan respon gagal ke AJAX
            http_response_code(400);
        }
    }
}
