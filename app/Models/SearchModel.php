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

    public function upcoming($time = "3 months")
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

    public function list($year, $month, $day)
    {
        $data=[];
        $builder = $this->db->table($this->table);

        if ($year) {
            if ($month) {
                if ($day) {
                    $builder->where('edition_date = ', date("$year-$month-$day 00:00"));
                } else {
                    $builder->where('edition_date >= ', date($year . "-" . $month . "-1 00:00"));
                    $builder->where('edition_date <= ', date($year . "-" . $month . "-31 23:59"));
                }
            } else {
                $builder->where('edition_date >= ', date($year . "-1-1 00:00"));
                $builder->where('edition_date <= ', date($year . "-12-31 23:59"));
            }
        } else {
            $builder->where('edition_date >= ', date("Y-m-d 00:00", strtotime("today")));
            $builder->where('edition_date <= ', date("Y-m-d 00:00", strtotime("+3 months")));
        }
        $builder->orderBy('edition_date', "ASC");
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }
}
