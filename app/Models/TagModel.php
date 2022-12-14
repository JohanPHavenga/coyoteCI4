<?php

namespace App\Models;

use CodeIgniter\Model;

class TagModel extends Model
{
    protected $table = 'tags';
    protected $id_field = 'tag_id';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($id)
    {
        $builder = $this->db->table($this->table);
        $builder->where($this->id_field, $id);
        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function list()
    {
        $data=[];
        $builder = $this->db->table($this->table);
        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row[$this->id_field]] = $row;
        }
        return $data;
    }


    public function detail($id)
    {
        if (!($id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $query = $builder->where($this->id_field, $id)->get();
            return $query->getRowArray();
        }
    }

    public function get_edition_tag_list($edition_id) {
        $data=[];
        $builder = $this->db->table("edition_tag");
        $builder->select("tag_id, tag_name, tagtype_name")
                ->join("tags", "tag_id")
                ->join("tagtypes", "tagtype_id", "left")
                ->where("tag_status", 1)
                ->where("tagtype_status", 1)
                ->where("edition_id", $edition_id);
//        $this->db->orderBy("tag_name");

        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row[$this->id_field]] = $row;
        }        
        return $data;
    }
    
}
