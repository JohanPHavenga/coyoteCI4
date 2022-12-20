<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSubscriptionModel extends Model
{
    protected $table = 'usersubscriptions';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function exists($user_id, $linked_to = null, $linked_id = null)
    {
        $builder = $this->db->table($this->table);
        $builder->select('id')->where("user_id", $user_id);
        if ($linked_to) {
            $builder->where("linked_to", $linked_to);
        }
        if ($linked_id) {
            $builder->where("linked_id", $linked_id);
        }
        $count=$builder->countAllResults();
        if ($count > 0) {
            return $count;
        } else {
            return false;
        }
    }

    public function list($id, $linked_to = NULL, $linked_id = 0)
    {
        $data = [];
        $builder = $this->db->table($this->table);
        $builder->where("user_id", $id);

        if ($linked_to) {
            $builder->where('linked_to', $linked_to);
            if ($linked_to == "edition") {
                $builder->select("edition_id, edition_name, edition_date");
                $builder->join('editions', "editions.edition_id=usersubscriptions.linked_id");
                $builder->orderBy('edition_date', "DESC");
            }
        }
        if ($linked_id) {
            $builder->where('linked_id', $linked_id);
        }
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function set_usersub($usersub_data)
    {
        if (empty($usersub_data)) {
            $id_type = $this->request->getPost("linked_to") . "_id";
            $id = $this->request->getPost($id_type);

            $usersub_data = array(
                'user_id' => $this->request->getPost('user_id'),
                'linked_to' => $this->request->getPost('linked_to'),
                'linked_id' => $id,
            );
        }
        $builder = $this->db->table($this->table);
        $builder->replace($usersub_data);
        return $this->db->affectedRows();
    }

    public function remove($user_id, $linked_to, $linked_id)
    {
        if (!($user_id)) {
            return false;
        } else {
            $builder = $this->db->table($this->table);
            $builder->where('user_id', $user_id);
            $builder->where('linked_to', $linked_to);
            $builder->where('linked_id', $linked_id);
            $builder->delete();
            return true;
        }
    }
}
