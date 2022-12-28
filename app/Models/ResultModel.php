<?php

namespace App\Models;

use CodeIgniter\Model;

class ResultModel extends Model
{
    protected $table = 'user_result';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function list($params = [])
    {
        $data = [];
        if ((empty($params)) || (!is_array(($params)))) {
            return $data;
        }

        $builder = $this->db->table($this->table);
        $builder->select("results.*,race_name, race_distance, race_time_start, racetype_id, edition_date, edition_name, edition_slug")
            ->join('results', 'result_id')
            ->join('races', 'race_id')
            ->join('editions', 'edition_id');

        if (isset($params['user_id'])) {
            $builder->where('user_id', $params['user_id']);
        }
        if (isset($params['result_id'])) {
            $builder->where('result_id', $params['result_id']);
        }
        if (isset($params['race_id'])) {
            $builder->where('race_id', $params['race_id']);
        }
        if (isset($params['distance'])) {
            $builder->where('race_distance', $params['distance'], false);
        }
        $builder->orderBy('edition_date', "DESC");

        // dd($builder->getCompiledSelect());
        $query = $builder->get();

        foreach ($query->getResultArray() as $key => $row) {
            $data[$key] = $row;
        }
        return $data;
    }

    public function exists($user_id, $result_id)
    {
        $builder = $this->db->table($this->table);
        $builder->where("user_id", $user_id)->where("result_id", $result_id);
        // dd($builder->countAllResults());
        return $builder->countAllResults();
    }

    public function distinct_races_with_results()
    {
        $builder = $this->db->table("results");
        $builder->distinct();
        $builder->select('race_id');
        $builder->groupBy('race_id');
        $query = $builder->get();
        // dd($builder->getCompiledSelect());
        foreach ($query->getResultArray() as $row) {
            $data[] = $row['race_id'];
        }
        return $data;
    }

    public function set_results_table($user_data)
    {
        $builder = $this->db->table("results");
        $builder->replace($user_data);
        return $this->db->insertID();
    }

    public function set_result($user_data)
    {
        $builder = $this->db->table($this->table);
        $builder->replace($user_data);
        return $this->db->affectedRows();
    }

    // public function set_result($usersub_data)
    // {
    //     if (empty($usersubscription_data)) {
    //         $id_type = $this->request->getPost("linked_to") . "_id";
    //         $id = $this->request->getPost($id_type);

    //         $usersub_data = array(
    //             'user_id' => $this->request->getPost('user_id'),
    //             'linked_to' => $this->request->getPost('linked_to'),
    //             'linked_id' => $id,
    //         );
    //     }
    //     $builder = $this->db->table($this->table);
    //     $builder->replace($usersub_data);
    //     return $this->db->affectedRows();
    // }

    public function remove_result($user_id, $result_id)
    {
        if (!($user_id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $builder->where('user_id', $user_id);
            $builder->where('result_id', $result_id);
            $builder->delete();
        }
    }

    // public function remove_result($user_id, $linked_to, $linked_id)
    // {
    //     if (!($user_id)) {
    //         return false;
    //     } else {
    //         $builder = $this->db->table($this->table);
    //         $builder->where('user_id', $user_id);
    //         $builder->where('linked_to', $linked_to);
    //         $builder->where('linked_id', $linked_id);
    //         $builder->delete();
    //     }
    // }

    public function get_results_with_race_detail($att = [])
    {
        $data = [];
        $builder = $this->db->table('results');
        $builder->select('results.*,race_name, race_distance, edition_name, edition_date, edition_slug, event_name, town_name, file_name')
            ->join("races", 'results.race_id = races.race_id', 'inner')
            ->join('files', 'results.file_id = files.file_id', 'left')
            ->join('editions', 'editions.edition_id=races.edition_id', 'left')
            ->join('events', 'editions.event_id=events.event_id', 'left')
            ->join('towns', 'town_id', 'left');

        if (isset($att['race_id'])) {
            $builder->where("races.race_id", $att['race_id']);
        }
        if (isset($att['result_id'])) {
            $builder->where("results.result_id", $att['result_id']);
        }
        if (isset($att['name'])) {
            $builder->groupStart();
            $builder->like('result_name', $att['name']);
            $builder->orLike('result_surname', $att['surname']);
            $builder->groupEnd();
        }
        // dd($builder->getCompiledSelect());
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[] = $row;
        }

        return $data;
    }

    public function get_userresult_count($user_id)
    {
        $userresult_list = [];
        $builder = $this->db->table($this->table);
        $builder->select("race_distance, COUNT(race_distance) AS count");
        $builder->join("results", "result_id");
        $builder->join("races", "race_id");
        $builder->where('user_result.user_id', $user_id);
        $builder->groupBy("race_distance");
        $builder->orderBy("race_distance", "DESC");
        $query = $builder->get();

        foreach ($query->getResultArray() as $key => $row) {
            $round_dist = round($row['race_distance']);
            $key = "dist_" . $round_dist;
            $userresult_list[$key]['count'] = $row['count'];
            $userresult_list[$key]['distance'] = $round_dist;
            $userresult_list[$key]['chart'] = "chart_" . $round_dist;
            switch ($round_dist) {
                case 42:
                    $userresult_list[$key]['name'] = "Marathons";
                    break;
                case 21:
                    $userresult_list[$key]['name'] = "Half-Marathons";
                    break;
                default:
                    $userresult_list[$key]['name'] = $round_dist . "km Races";
                    break;
            }
        }
        return $userresult_list;
    }
}
