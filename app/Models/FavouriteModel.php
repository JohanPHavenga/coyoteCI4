<?php

namespace App\Models;

use CodeIgniter\Model;

class FavouriteModel extends Model
{
    protected $table = 'favourites';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function get_race_list($edition_id)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->where('edition_id', $edition_id)->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['race_id']] = $row;
        }
        return $data;
    }

    public function get_favourite_edition($user_id, $edition_id)
    {
        $builder = $this->db->table($this->table);
        $builder->where('user_id', $user_id)
            ->where('edition_id', $edition_id);

        if ($builder->countAllResults()>0) {
        	return true;
        } else {
        	return false;
        }
    }

    public function get_favourite_list($user_id)
    {
        $builder = $this->db->table($this->table);
        $query = $builder->where('user_id', $user_id)->get();

        foreach ($query->getResultArray() as $row) {
            $edition_arr[] = $row['edition_id'];
        }

        return $edition_arr;
    }

    public function save_data($data)
    {
        $builder = $this->db->table($this->table);
        if ($builder->replace($data)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function remove_fav($user_id, $edition_id)
    {
        $builder = $this->db->table($this->table);
        $builder->where('user_id', $user_id);
        $builder->where('edition_id', $edition_id);
        $builder->delete();
    }
}
