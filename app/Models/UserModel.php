<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModelExtended extends Model
{
    protected $table = 'users';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function empty_table() {
        $builder = $this->db->table($this->table);
        $query=$builder->emptyTable($this->table);
        return $query;
    }

    public function list($limit=null) {
        $builder = $this->db->table('users_legacy');
        if ($limit) {
            $builder->limit($limit);
        }
        $query=$builder->get();
        return $query->getResultArray();
    }

    public function bulk_update($user_list) {
        foreach ($user_list as $user) {
            $data[$user['user_id']]['id']=$user['user_id'];
            $data[$user['user_id']]['email']=$user['user_email'];
            $data[$user['user_id']]['name']=$user['user_name'];
            $data[$user['user_id']]['surname']=$user['user_surname'];
            $data[$user['user_id']]['password_hash']="";
            $data[$user['user_id']]['active']=1;
            $data[$user['user_id']]['force_pass_reset']=1;
            $data[$user['user_id']]['club_id']=$user['club_id'];
            $data[$user['user_id']]['phone']=$user['user_contact'];
            $data[$user['user_id']]['picture']=$user['user_picture'];
            $data[$user['user_id']]['profile_link']=$user['user_link'];
            $data[$user['user_id']]['created_at']=time();
        }

        $builder = $this->db->table($this->table);
        $builder->insertBatch($data);
    }
}
