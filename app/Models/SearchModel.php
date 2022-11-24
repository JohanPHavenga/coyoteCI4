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

        //     $builder->select('*')->from('my_table')
        // ->groupStart()
        //     ->where('a', 'a')
        //     ->orGroupStart()
        //         ->where('b', 'b')
        //         ->where('c', 'c')
        //     ->groupEnd()
        // ->groupEnd()
        // ->where('d', 'd')
        // ->get();
    }

    public function general($att)
    {
        $data = [];
        $builder = $this->db->table($this->table);

        $builder->select('edition_id, edition_name, edition_slug, edition_date')
            ->where('edition_date > ', date("Y-m-d"))
            ->orderBy('edition_date', "ASC")
            ->limit(100);

        if (isset($att['province_id'])) {
            $builder->where('province_id', $att['province_id']);
        }
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function results($time = "-3 months")
    {
        $data = [];
        $builder = $this->db->table($this->table);

        $builder->where('edition_date <= ', date("Y-m-d 00:00"))
            ->where('edition_date > ', date("Y-m-d 00:00", strtotime($time)))
            ->orderBy('edition_date', "DESC");
        if ($_SESSION['site_province'] > 0) {
            $builder->where('province_id', $_SESSION['site_province']);
        }

        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function upcoming($time = "3 months")
    {
        $builder = $this->db->table($this->table);

        $builder->where('edition_date >= ', date("Y-m-d 00:00"))
            ->where('edition_date < ', date("Y-m-d 00:00", strtotime($time)))
            ->orderBy('edition_date', "ASC");

        if ($_SESSION['site_province'] > 0) {
            $builder->where('province_id', $_SESSION['site_province']);
        }

        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function region($region_id_arr=[])
    {
        $builder = $this->db->table($this->table);

        $builder->where('edition_date >= ', date("Y-m-d 00:00"))
            ->where('edition_date < ', date("Y-m-d 00:00", strtotime("6 months")))
            ->orderBy('edition_date', "ASC");

        if ($region_id_arr) {
            $builder->whereIn('region_id', $region_id_arr);
        }

        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function list($year, $month, $day)
    {
        $data = [];
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
        if ($_SESSION['site_province'] > 0) {
            $builder->where('province_id', $_SESSION['site_province']);
        }
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function search($att)
    {
        $ss = $att['s'];
        $date = date('Y-m-d 23:59', strtotime('+' . $att['t'] . ' months'));
        $data = [];
        $builder = $this->db->table($this->table);
        $builder->groupStart()
            ->like('edition_name', $ss)
            ->orLike('event_name', $ss)
            ->orLike('town_name', $ss)
            ->orLike('town_name_alt', $ss)
            ->orLike('province_name', $ss)
            ->orLike('race_name', $ss)
            ->orWhere('province_abbr', $ss)
            ->groupEnd()
            ->where('edition_date >', date("Y-m-d 00:00", strtotime('-3 weeks')))
            ->where('edition_date <', $date)
            ->orderBy('edition_date', 'DESC')
            ->limit(100);

        if ($_SESSION['site_province'] > 0) {
            $builder->where('province_id', $_SESSION['site_province']);
        }
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }
}
