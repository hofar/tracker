<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryKeterangan_model extends CI_Model
{

    private $table = 'history_keterangan';

    private function _get_datatables_query()
    {

        $this->db->from($this->table);
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_by_program_kerja_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id_program_kerja', $id);
        $query = $this->db->get();

        return $query->result();
    }

    public function get_by_id($id)
    {
        $this->db->from($this->table);
        $this->db->where('id', $id);
        $query = $this->db->get();

        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function insert_batch($data)
    {
        $this->db->insert_batch($this->table, $data);
    }
}
