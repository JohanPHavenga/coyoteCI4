<?php

namespace App\Models;

use CodeIgniter\Model;

class RaceModel extends Model
{
    protected $table = 'races';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($id)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('race_id')->where("race_id", $id);
        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function list($edition_id)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->where('edition_id', $edition_id)->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['race_id']] = $row;
        }
        return $data;
    }

    public function detail($id)
    {
        if (!($id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $query = $builder->where("race_id", $id)->get();
            // dd($builder->getCompiledSelect());
            return $query->getRowArray();
        }
    }
    
}
