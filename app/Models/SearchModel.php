<?php

namespace App\Models;

use CodeIgniter\Model;

class SearchModel extends Model
{
    protected $table = 'temp_search';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function upcoming($time="3 months")
    {
        $builder = $this->db->table($this->table);

        $query = $builder->where('edition_date >= ', date("Y-m-d 00:00"))
                         ->where('edition_date < ', date("Y-m-d 00:00", strtotime($time)))
                         ->orderBy('edition_date', "ASC")
                         ->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }
    
}
