<?php
defined('BASEPATH') or exit('No direct script access allowed');

class HistoryKeterangan_model extends CI_Model
{

    private $table = 'history_keterangan';
    private $table_proker = 'program_kerja';
    public $column_order;
    public $column_search;
    public $order;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->column_order = array(null, $this->table_proker . '.name', $this->table . '.type', $this->table . '.status', $this->table . '.keterangan', $this->table . '.created_at'); //set column field database for datatable orderable
        $this->column_search = array($this->table_proker . '.name', $this->table . '.type', $this->table . '.status', $this->table . '.keterangan', $this->table . '.created_at');  //set column field database for datatable searchable just firstname , lastname , address are searchable
        $this->order = array('id' => 'desc'); // default order
    }

    private function _get_datatables_query()
    {
        $this->db->select(array(
            $this->table . '.*',
            $this->table_proker . '.name AS sasaran_program'
        ));
        $this->db->from($this->table);
        $this->db->join($this->table_proker, $this->table_proker . '.id = ' . $this->table . '.id_program_kerja');

        $i = 0;

        foreach ($this->column_search as $item) { // loop column
            if ($_POST['search']['value']) { // if datatable send POST for search
                if ($i === 0) { // first loop
                    $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i) { //last loop
                    $this->db->group_end();
                }
                //close bracket
            }
            $i++;
        }

        if (isset($_POST['order'])) { // here order processing
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->db->query("SET sql_mode = '' ");
        $this->_get_datatables_query();
        $input_length = filter_input(INPUT_POST, 'length');
        $input_start = filter_input(INPUT_POST, 'start');
        if ($input_length != -1) {
            $this->db->limit($input_length, $input_start);
        }
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
