<?php

namespace App\Models;

use CodeIgniter\Model;

class RaceModel extends Model
{
    protected $table = 'races';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($id)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('race_id')->where("race_id", $id);
        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function list($edition_id = false)
    {
        $builder = $this->db->table($this->table);

        if ($edition_id) {
            $builder->where('edition_id', $edition_id);
        } else {
            $builder->select(["race_id", "edition_id", "race_name", "race_distance", "race_time_start", "racetype_abbr", "racetype_icon"]);
        }
        $builder->join('racetypes', 'racetype_id');

        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['race_id']] = $row;
        }
        return $data;
    }

    public function detail($id)
    {
        if (!($id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $query = $builder->where("race_id", $id)->join('editions','edition_id')->get();
            // dd($builder->getCompiledSelect());
            return $query->getRowArray();
        }
    }

    public function get_race_detail_with_results($params = [])
    {
        $data = [];
        $builder = $this->db->table($this->table);
        $builder->select("results.*,race_name, race_distance, edition_name, edition_date, edition_slug, event_name, town_name, file_name");
        $builder->join('results', 'results.race_id = races.race_id', 'inner');
        $builder->join('files', 'results.file_id = files.file_id', 'left');
        $builder->join('editions', 'editions.edition_id=races.edition_id', 'left');
        $builder->join('events', 'editions.event_id=events.event_id', 'left');
        $builder->join('towns', 'town_id', 'left');
        if (isset($params['race_id'])) {
            $builder->where("races.race_id", $params['race_id']);
        }
        if (isset($params['result_id'])) {
            $builder->where("results.result_id", $params['result_id']);
        }
        if (isset($params['name'])) {
            $builder->groupStart();
            $builder->like('result_name', $params['name']);
            $builder->orLike('result_surname', $params['surname']);
            $builder->groupEnd();
        }
        //        echo $this->db->get_compiled_select();
        //        die();
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['result_id']] = $row;
            $data[$row['result_id']]['race_color'] = race_color($row['race_distance']);
        }
        return $data;
    }
    public function race_distance_list()
    {
        return [
            5 => "Fun Runs",
            10 => "10km",
            15 => "15km",
            21 => "Half-Marathon",
            30 => "30km",
            42 => "Marathon",
            43 => "Ultra",
        ];
    }
}
