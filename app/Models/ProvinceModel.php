<?php

namespace App\Models;

use CodeIgniter\Model;

class ProvinceModel extends Model
{
    protected $table = 'provinces';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function list()
    {
        $builder = $this->db->table($this->table);

        $query = $builder->select('province_id, province_name, province_slug, province_abbr')
            ->orderBy('province_name', "ASC")
            ->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['province_id']] = $row;
        }
        // remove no province
        unset($data[12]);
        return $data;
    }

    public function get_province_id_from_slug($province_slug)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('province_id')
            ->where('province_slug', $province_slug)
            ->get();
        if (isset($query->getRow()->province_id)) {
            return $query->getRow()->province_id;
        } else {
            return false;
        }
    }
}
