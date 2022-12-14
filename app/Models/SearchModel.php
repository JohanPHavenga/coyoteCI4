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
            ->where('edition_info_status', 11)
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
        $data = [];
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

    public function region($region_id_arr = [])
    {
        $data = [];
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

    public function search($att, $in_past = false)
    {
        $ss = $att['s'];
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
            ->limit(100);

        if ($in_past) {
            $builder->where('edition_date <', date("Y-m-d 23:59"))
                ->orderBy('edition_date', 'DESC');
        } else {
            $date = date('Y-m-d 23:59', strtotime('+' . $att['t'] . ' months'));
            $builder->where('edition_date >', date("Y-m-d 00:00", strtotime('-3 weeks')))
                ->where('edition_date <', $date)
                ->orderBy('edition_date', 'ASC');
        }
        // dd($builder->getCompiledSelect());

        if ($_SESSION['site_province'] > 0) {
            $builder->where('province_id', $_SESSION['site_province']);
        }
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function my_result_search($ss, $has_results=1)
    {        
        $data = [];
        $builder = $this->db->table($this->table);
        $builder->groupStart()
            ->like('edition_name', $ss)
            ->orLike('event_name', $ss)
            ->orLike('race_name', $ss)
            ->groupEnd()
            ->where('edition_status', 1)
            ->where('edition_date <', date("Y-m-d 23:59"))
            ->where('has_local_results', $has_results)
            ->orderBy('edition_date', 'DESC')
            ->limit(100);
        
        // dd($builder->getCompiledSelect());
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['race_id']] = $row;
        }
        return $data;
    }


    public function advanced($query_params=[], $flat = false)
    {
        $builder = $this->db->table($this->table);

        $builder->select('edition_id, edition_name, edition_slug, edition_date')
            ->where('edition_status !=', 2);

        foreach ($query_params as $operator => $clause_arr) {
            if (is_array($clause_arr)) {
                foreach ($clause_arr as $field => $value) {
                    $builder->$operator($field, $value);
                }
            } else {
                $this->db->$operator($clause_arr);
            }
        }
        if (!isset($query_params['orderBy'])) {
            $builder->orderBy('edition_date', 'DESC');
        }

        // dd($builder->getCompiledSelect());

        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            if ($flat) {
                $data[$row['edition_id']] = $row;
            } else {
                $year = date("Y", strtotime($row['edition_date']));
                $month = date("m", strtotime($row['edition_date']));
                $data[$year][$month][$row['edition_id']] = $row;
            }
        }

        return $data;
    }
}
