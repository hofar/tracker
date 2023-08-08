<?php

class Qrcode_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    private $table = "qr_code";

    public function insertToken($token)
    {
        // Data yang akan dimasukkan ke dalam tabel
        $data = array(
            'token' => $token,
            'flag' => 0,
            'created_at' => date('Y-m-d H:i:s') // Tanggal dan waktu saat ini
        );

        // Masukkan data ke dalam tabel
        $this->db->insert($this->table, $data);

        // Kembalikan id data yang baru saja dimasukkan
        return $this->db->insert_id();
    }

    public function setFlagToOne($token)
    {
        // Cek apakah token ada dalam tabel
        $this->db->where('token', $token);
        $query = $this->db->get($this->table);

        if ($query->num_rows() === 1) {
            // Jika token ditemukan, ubah nilai flag menjadi 1
            $data = array(
                'flag' => 1
            );

            $this->db->where('token', $token);
            $this->db->update($this->table, $data);

            return true; // Berhasil mengubah flag menjadi 1
        } else {
            return false; // Token tidak ditemukan atau terdapat beberapa data dengan token yang sama
        }
    }
}
