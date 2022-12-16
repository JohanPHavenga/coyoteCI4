<?php

namespace App\Models;

use CodeIgniter\Model;

class FileModel extends Model
{
    protected $table = 'files';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($linked_to, $linked_id, $filetype_id)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->select('file_id')
            ->where("filetype_id", $filetype_id)
            ->where("file_linked_to", $linked_to)
            ->where("linked_id", $linked_id);

        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function list($linked_to = NULL, $linked_id = 0, $by_filetype = false,)
    {
        $data = [];
        $builder = $this->db->table($this->table);
        $builder->join('filetypes', "filetype_id");
        // $builder->orderBy("file_start");

        if ($linked_to) {
            $builder->where('file_linked_to', $linked_to);
        }
        if ($linked_id) {
            $builder->where('linked_id', $linked_id);
        }
        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            if ($by_filetype) {
                $data[$row['filetype_id']][] = $row;
            } else {
                $data[$row['file_id']] = $row;
            }
        }
        return $data;
    }

    public function detail($file_id)
    {
        if (!($file_id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $query = $builder->where("file_id", $file_id)
                ->join('filetypes', 'filetype_id')
                ->get();
            // dd($builder->getCompiledSelect());
            return $query->getRowArray();
        }
    }

    public function get_filetype_list()
    {
        $builder = $this->db->table($this->table);
        $builder->from("filetypes");
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[strtolower(str_replace(" ", "_", $row['filetype_name']))] = $row['filetype_id'];
        }
        return $data;
    }

    public function get_files($edition_list = [])
    {
        if (!empty($edition_list)) {
            foreach ($edition_list as $edition_id => $edition) {
                $edition_list[$edition_id]['file_list'] = $this->get_file_list("edition", $edition_id, false, true);
            }
        }
        return $edition_list;
    }

    public function get_all_edition_logos() {
        $builder = $this->db->table($this->table);
        $builder->select("linked_id AS edition_id, file_name");
        $builder->where(["filetype_id"=>1,"file_linked_to"=>"edition"]);
        $query = $builder->get();
        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row['file_name'];
        }
        return $data;
    }

}
