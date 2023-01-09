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

    public function general($att)
    {
        $data = [];
        $builder = $this->db->table($this->table);

        $builder->where('edition_date > ', date("Y-m-d"))
            ->limit(100);

        if (isset($att['province_id'])) {
            $builder->where('province_id', $att['province_id']);
        }
        switch ($_SESSION['sort_by']) {
            case "date":
                $builder->orderBy('edition_date', "ASC");
                break;
            case "distance":
                $builder->orderBy('race_distance_int', "DESC");
                break;
            default:
                $builder->orderBy('edition_date', "ASC");
                break;
        }
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            if (!isset($data[$row['edition_id']])) {
                $data[$row['edition_id']] = $row;
            }
            $data[$row['edition_id']]['races'][$row['race_id']] = $row;
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
            if (!isset($data[$row['edition_id']])) {
                $data[$row['edition_id']] = $row;
            }
            $data[$row['edition_id']]['races'][$row['race_id']] = $row;
        }
        return $data;
    }

    public function upcoming($time = "3 months")
    {
        $data = [];
        $builder = $this->db->table($this->table);

        $builder->where('edition_date >= ', date("Y-m-d 00:00"))
            ->where('edition_date < ', date("Y-m-d 00:00", strtotime($time)))
            ->limit('100');

        switch ($_SESSION['sort_by']) {
            case "date":
                $builder->orderBy('edition_date', "ASC");
                break;
            case "distance":
                $builder->orderBy('race_distance_int', "DESC");
                break;
            default:
                $builder->orderBy('edition_date', "ASC");
                break;
        }

        if ($_SESSION['site_province'] > 0) {
            $builder->where('province_id', $_SESSION['site_province']);
        }

        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            if (!isset($data[$row['edition_id']])) {
                $data[$row['edition_id']] = $row;
            }
            $data[$row['edition_id']]['races'][$row['race_id']] = $row;
        }
        return $data;
    }

    public function region($region_id_arr = [])
    {
        $data = [];
        $builder = $this->db->table($this->table);

        $builder->where('edition_date >= ', date("Y-m-d 00:00"))
            ->where('edition_date < ', date("Y-m-d 00:00", strtotime("6 months")))
            ->orderBy('edition_date', "ASC")
            ->limit(100);

        if ($region_id_arr) {
            $builder->whereIn('region_id', $region_id_arr);
        }

        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            if (!isset($data[$row['edition_id']])) {
                $data[$row['edition_id']] = $row;
            }
            $data[$row['edition_id']]['races'][$row['race_id']] = $row;
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
            if (!isset($data[$row['edition_id']])) {
                $data[$row['edition_id']] = $row;
            }
            $data[$row['edition_id']]['races'][$row['race_id']] = $row;
        }
        return $data;
    }

    public function search($att, $in_past = false)
    {
        $data = [];
        $builder = $this->db->table($this->table);
        switch ($_SESSION['sort_by']) {
            case "date":
                $builder->orderBy('edition_date', "ASC");
                break;
            case "distance":
                $builder->orderBy('race_distance_int', "DESC");
                break;
            default:
                $builder->orderBy('edition_date', "ASC");
                break;
        }
        $builder->limit(100);

        if (!isset($att['t'])) {
            $att['t'] = 3;
        }

        if (isset($att['s'])) {
            $ss = $att['s'];
            $builder->groupStart()
                ->like('edition_name', $ss)
                ->orLike('event_name', $ss)
                ->orLike('town_name', $ss)
                ->orLike('town_name_alt', $ss)
                ->orLike('province_name', $ss)
                ->orLike('race_name', $ss)
                ->orWhere('province_abbr', $ss)
                ->groupEnd();
        }

        if ($in_past) {
            $builder->where('edition_date <', date("Y-m-d 23:59"))
                ->orderBy('edition_date', 'DESC');
        } else {
            $date = date('Y-m-d 23:59', strtotime('+' . $att['t'] . ' months'));
            $builder->where('edition_date >', date("Y-m-d 00:00", strtotime('-3 weeks')))
                ->where('edition_date <', $date);
        }

        if (isset($att['distance'])) {
            $builder->whereIn('race_distance_int', $att['distance']);
        }

        if (isset($att['location'])) {
            $builder->groupStart()
                ->orLike('town_name', $att['location'])
                ->orLike('town_name_alt', $att['location'])
                ->orLike('province_name', $att['location'])
                ->orLike('province_abbr', $att['location'])
                ->groupEnd();
        }

        if (isset($att['verified'])) {
            $builder->where('edition_info_status', 16);
        }

        // dd($builder->getCompiledSelect());

        if ($_SESSION['site_province'] > 0) {
            $builder->where('province_id', $_SESSION['site_province']);
        }
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            if (!isset($data[$row['edition_id']])) {
                $data[$row['edition_id']] = $row;
            }
            $data[$row['edition_id']]['races'][$row['race_id']] = $row;
        }
        return $data;
    }

    public function my_result_search($ss, $has_results = 1)
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


    public function advanced($query_params = [], $flat = false)
    {
        $data = [];
        $builder = $this->db->table($this->table);

        $builder->where('edition_status !=', 2)->limit(100);

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
                if (!isset($data[$row['edition_id']])) {
                    $data[$row['edition_id']] = $row;
                }
                if (isset($row['race_id'])) {
                    $data[$row['edition_id']]['races'][$row['race_id']] = $row;
                }
            } else {
                $year = date("Y", strtotime($row['edition_date']));
                $month = date("m", strtotime($row['edition_date']));
                $data[$year][$month][$row['edition_id']] = $row;
            }
        }

        return $data;
    }

    public function from_edition_id($id_array = [], $key_field='edition_id')
    {
        $data = [];
        if (empty($id_array)) {
            return $data;
        }
        $builder = $this->db->table($this->table);

        $builder->where('edition_status !=', 2)
            ->whereIn('edition_id', $id_array);

        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            // $data[$row['edition_id']] = $row;
            if (!isset($data[$row[$key_field]])) {
                $data[$row[$key_field]] = $row;
            }
            if (isset($row['race_id'])) {
                $data[$row[$key_field]]['races'][$row['race_id']] = $row;
            }
        }
        
        return $data;
    }

    public function set_search_table($data, $id_type, $id = null)
    {
        $builder = $this->db->table($this->table);

        if ($id) {
            $builder->where($id_type, $id);
            $builder->update($data);
            return $id;
        } else {
            $builder->set($data);
            $builder->insert();
            return $this->db->insertID();
        }
    }

    function clear_search_table()
    {
        $builder = $this->db->table($this->table);
        $builder->truncate();
    }

    function set_search_table_bulk($bulk_search_data)
    {

        $builder = $this->db->table($this->table);
        $builder->insertBatch($bulk_search_data);

        return true;
    }
}
