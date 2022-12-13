<?php

namespace App\Models;

use CodeIgniter\Model;

class GenericModel extends Model
{
    protected $table = 'generics';
    protected $id_field = 'generic_id';
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

    public function list()
    {
        $data=[];
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row[$this->id_field]] = $row;
        }
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
    
}
