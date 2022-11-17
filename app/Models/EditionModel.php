<?php

namespace App\Models;

use CodeIgniter\Model;

class EditionModel extends Model
{
    protected $table = 'editions';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function last_updated($count = 1)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
                         ->orderBy('updated_date', "DESC")
                         ->limit($count)
                         ->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }
}
