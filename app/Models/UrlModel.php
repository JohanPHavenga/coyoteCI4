<?php

namespace App\Models;

use CodeIgniter\Model;

class UrlModel extends Model
{
    protected $table = 'urls';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($linked_to, $linked_id, $urltype_id)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('url_id')
            ->where("urltype_id", $urltype_id)
            ->where("url_linked_to", $linked_to)
            ->where("linked_id", $linked_id);

        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function list($linked_to = NULL, $linked_id = 0, $by_urltype = false,)
    {
        $data = [];
        $builder = $this->db->table($this->table);
        $builder->join('urltypes', "urltype_id");
        // $builder->orderBy("url_start");

        if ($linked_to) {
            $builder->where('url_linked_to', $linked_to);
        }
        if ($linked_id) {
            $builder->where('linked_id', $linked_id);
        }
        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            if ($by_urltype) {
                $data[$row['urltype_id']][] = $row;
            } else {
                $data[$row['url_id']] = $row;
            }
        }
        return $data;
    }

    public function detail($url_id)
    {
        if (!($url_id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $query = $builder->where("url_id", $url_id)
                ->join('urltypes', 'urltype_id')
                ->get();
            // dd($builder->getCompiledSelect());
            return $query->getRowArray();
        }
    }

    public function get_urltype_list()
    {
        $builder = $this->db->table($this->table);
        $builder->from("urltypes");
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[strtolower(str_replace(" ", "_", $row['urltype_name']))] = $row['urltype_id'];
        }
        return $data;
    }

    public function get_urls($edition_list = [])
    {
        if (!empty($edition_list)) {
            foreach ($edition_list as $edition_id => $edition) {
                $edition_list[$edition_id]['url_list'] = $this->get_url_list("edition", $edition_id, false, true);
            }
        }
        return $edition_list;
    }
}
