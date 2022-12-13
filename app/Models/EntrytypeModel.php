<?php

namespace App\Models;

use CodeIgniter\Model;

class EntrytypeModel extends Model
{
    protected $table = 'entrytypes';
    protected $id_field = 'entrytype_id';
    protected $no_info_id = 5;

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($id)
    {
        $builder = $this->db->table($this->table);
        $builder->where($this->id_field, $id);
        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_entrytype_field_array()
    {
        $fields = $this->db->getFieldNames($this->table);
        foreach ($fields as $field) {
            $data[$field] = "";
        }
        return $data;
    }

    public function list()
    {
        $data = [];
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row[$this->id_field]] = $row;
        }
        return $data;
    }

    public function get_entrytype_dropdown()
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select("entrytype_id, entrytype_name")
            ->where("entrytype_status", 1)
            ->orderBy("entrytype_id", "ASC")
            ->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row[$this->id_field]] = $row['entrytype_name'];
        }
        move_to_top($data, $this->no_info_id);
        return $data;        
    }

    public function detail($id)
    {
        if (!($id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $query = $builder->where($this->id_field, $id)->get();
            return $query->getRowArray();
        }
    }

    public function get_edition_entrytype_list($edition_id)
    {
        $data = [];
        $builder = $this->db->table("edition_entrytype");
        $query = $builder->select($this->id_field)
            ->where("edition_id", $edition_id)
            ->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row[$this->id_field]] = $row[$this->id_field];
        }
        return $data;
    }
}
