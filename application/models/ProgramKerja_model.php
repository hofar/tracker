<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProgramKerja_model extends CI_Model
{

    private $table = 'program_kerja';

    public function getProgramKerjaData($type = null)
    {
        if (!empty($type)) {
            $this->db->where('type', $type);
        }
        $query = $this->db->get($this->table);
        return $query->result();
    }
}
