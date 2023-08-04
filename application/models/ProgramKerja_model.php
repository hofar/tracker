<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProgramKerja_model extends CI_Model
{

    private $table = 'program_kerja';
    private $table_user = 'user';

    public $column_order; //set column field database for datatable orderable
    public $column_search; //set column field database for datatable searchable just firstname , lastname , address are searchable
    public $order; // default order

    public function __construct()
    {
        parent::__construct();
        $this->load->database();

        $this->column_order = array(null, null, $this->table_user . '.nama', $this->table . 'name', $this->table . 'value', $this->table . 'type', $this->table . 'status', $this->table . 'start_date', $this->table . 'end_date', null);
        $this->column_search = array('name', $this->table_user . '.nama', $this->table . 'value', $this->table . 'type', $this->table . 'status', $this->table . 'start_date', $this->table . 'end_date');
        $this->order = array('id' => 'desc'); // default order
    }

    private function _get_datatables_query()
    {
        $this->db->select(array(
            $this->table . '.*',
            $this->table_user . '.nama AS nama_user'
        ));
        $this->db->from($this->table);
        $this->db->join($this->table_user, $this->table_user . '.id = ' . $this->table . '.id_user');

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

    public function getProgramKerjaData($type = null)
    {
        if (!empty($type)) {
            $this->db->where('type', $type);
        }
        $query = $this->db->get($this->table);
        return $query->result();
    }

    public function insert_batch($data)
    {
        $this->db->insert_batch($this->table, $data);
    }
}
