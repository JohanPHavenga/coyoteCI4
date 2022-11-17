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

    public function get_race_list($edition_id)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->where('edition_id', $edition_id)->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['race_id']] = $row;
        }
        return $data;
    }
    
}
