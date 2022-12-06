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

    public function featured($count = 1)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
            ->where('edition_isfeatured', "1")
            ->where('edition_date > ', date("Y-m-d"))
            ->orderBy('edition_date', "ASC")
            ->limit($count)
            ->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function detail($id)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->where('edition_id', $id)
            ->get();

        return $query->getRowArray();
    }

    public function get_edition_id_from_slug($edition_slug)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->select('edition_id')
            ->where('edition_slug', $edition_slug)
            ->get();

        if ($query->getRow()) {
            return $query->getRow()->edition_id;
        } else {
            return false;
        }
    }

    public function from_id($id_array=[])
    {
        $data=[];
        if (empty($id_array)) {
            return $data;
        }
        $builder = $this->db->table($this->table);

        $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
            ->where('edition_status !=', 2)
            ->whereIn('edition_id',$id_array)
            ->orderBy('edition_date', "DESC");
        
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }

        return $data;
    }

    public function list($from = false)
    {
        $builder = $this->db->table($this->table);

        $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
            ->where('edition_status !=', 2)
            ->orderBy('edition_date', "DESC");

        if ($from) {
            $builder->where('edition_date > ', date("Y-m-d 00:00", strtotime($from)));
        }

        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $year = date("Y", strtotime($row['edition_date']));
            $month = date("m", strtotime($row['edition_date']));
            $data[$year][$month][$row['edition_id']] = $row;
        }

        return $data;
    }
}
