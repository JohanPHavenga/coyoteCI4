<?php

namespace App\Models;

use CodeIgniter\Model;

class EmailqueModel extends Model
{
    protected $table = 'emailques';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function check_id($id)
    {
        $builder = $this->db->table($this->table);
        $builder->select("emailque_id")->where("emailque_id", $id)->get();
        if ($builder->countAllResults() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_emailstatus_name($id)
    {
        $builder = $this->db->table('status');
        $query = $builder->where("status_id", $id)->get();
        $result = $query->getRowArray();
        return $result['status_name'];
    }

    public function list($top = 0, $status = 4)
    {
        $data=[];
        $builder = $this->db->table($this->table);
        if ($top > 0) {
            $builder->limit($top);
        }
        $builder->where("emailque_status", $status);
        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['emailque_id']] = $row;
        }
        return $data;
    }

    public function detail($id) {
        $builder = $this->db->table($this->table);
        $query = $builder->where("emailque_id", $id)->get();
        return $query->getRowArray();
    }

    public function remove_old_emails($before_date)
    {
        // get count for records older than date provided
        $builder = $this->db->table($this->table);
        $builder->select("emailque_id")->where('updated_date < ', $before_date);
        $record_count = $builder->countAllResults();

        // remove old records
        $builder = $this->db->table($this->table);
        $builder->where('updated_date < ', $before_date);
        $builder->delete();

        return $record_count;
    }

    
}
