<?php

namespace App\Models;

use CodeIgniter\Model;

class DateModel extends Model
{
    protected $table = 'dates';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($linked_to, $linked_id, $datetype_id)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('date_id')
            ->where("datetype_id", $datetype_id)
            ->where("date_linked_to", $linked_to)
            ->where("linked_id", $linked_id);

        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function list($linked_to = NULL, $linked_id = 0, $by_date_type_linked_id=false, $by_date_only=false,)
    {
        $data = [];        
        $builder = $this->db->table($this->table);
        $builder->join('datetypes', "datetype_id");
        $builder->join('venues', "venue_id", "left");
        $builder->orderBy("date_start");

        if ($linked_to) {
            $builder->where('date_linked_to', $linked_to);
        }
        if ($linked_id) {
            $builder->where('linked_id', $linked_id);
        }
        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            if ($by_date_type_linked_id) {
                $data[$row['datetype_id']][$row["linked_id"]] = $row;
            } elseif ($by_date_only) {
                $data[$row['datetype_id']][] = $row;
            } else {
                $data[$row['date_id']] = $row;
            }
        }
        return $data;
    }

    public function detail($date_id)
    {
        if (!($date_id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $query = $builder->where("date_id", $date_id)->get();
            // dd($builder->getCompiledSelect());
            return $query->getRowArray();
        }
    }

    public function get_dates($edition_list=[]) {
        if (!empty($edition_list)) {
            foreach ($edition_list as $edition_id => $edition) {
                $edition_list[$edition_id]['date_list']=$this->list("edition",$edition_id, false, true);
            }
        }
        return $edition_list;
    }
   

}
