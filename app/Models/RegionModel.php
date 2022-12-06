<?php

namespace App\Models;

use CodeIgniter\Model;

class RegionModel extends Model
{
    protected $table = 'regions';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function list($province_id = false)
    {
        $builder = $this->db->table($this->table);

        $builder->select('region_id, region_name, region_slug, province_id')
            ->where('region_status !=', 2)
            ->orderBy('region_name', "ASC");

        if ($province_id) {
            $builder->where('province_id', $province_id);
        }

        $query = $builder->get();
        if ($province_id) {
            foreach ($query->getResultArray() as $row) {
                $data[$row['region_id']] = $row;
            }
            // unset no region
            unset($data[61]);
        } else {
            foreach ($query->getResultArray() as $row) {
                $data[$row['province_id']][$row['region_id']] = $row;
            }
            // unset no province
            unset($data[12]);
        }

        return $data;
    }

    public function slug_list()
    {
        $builder = $this->db->table($this->table);

        $builder->select('region_id, region_name, region_slug, province_id')
            ->where('region_status !=', 2)
            ->orderBy('region_name', "ASC");
        
        $query = $builder->get();
       
        foreach ($query->getResultArray() as $row) {
            $data[strtolower($row['region_slug'])] = $row;
        }

        return $data;
    }
}
