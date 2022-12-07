<?php

namespace App\Models;

use CodeIgniter\Model;

class EditionModel extends Model
{
    protected $table = 'editions';

    public function record_count()
    {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

    public function last_updated($count = 1)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
            ->orderBy('updated_date', "DESC")
            ->limit($count)
            ->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function featured($count = 1)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
            ->where('edition_isfeatured', "1")
            ->where('edition_date > ', date("Y-m-d"))
            ->orderBy('edition_date', "ASC")
            ->limit($count)
            ->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }
        return $data;
    }

    public function detail($id, $extended = false)
    {
        $builder = $this->db->table($this->table);

        $builder->where('edition_id', $id);
        if ($extended) {
            $builder->select('editions.*, events.event_id, event_name, 
                            clubs.club_id, club_name, edition_user.user_id, users.name as user_name, users.surname as user_surname, users.email as user_email, users.phone as user_contact, 
                            asa_members.asa_member_id, asa_member_name, asa_member_abbr, asa_member_url, timingprovider_name, timingprovider_url, timingprovider_img, 
                            towns.town_id, town_name, regions.region_id, region_name, provinces.province_id, province_name')
                ->join('events', 'event_id')
                ->join('towns', 'town_id')
                ->join('regions', 'region_id')
                ->join('provinces', 'regions.province_id=provinces.province_id')
                ->join('organising_club', 'event_id', 'left')
                ->join('clubs', 'club_id', 'left')
                ->join('edition_user', 'edition_id', 'left')
                ->join('users', 'edition_user.user_id=users.id', 'left')
                ->join('edition_asa_member', 'edition_id', 'left')
                ->join('asa_members', 'asa_member_id', 'left')
                ->join('timingproviders', 'timingprovider_id', 'left');
            $query = $builder->get();
            $return_arr = $query->getRowArray();
            $return_arr = $this->add_more_edition_info($return_arr);
            $return_arr['annual_name'] = $return_arr['event_name'] . " " . date("Y", strtotime($return_arr['edition_date']));
            return $return_arr;
        } else {
            $query = $builder->get();
            return $query->getRowArray();
        }
    }

    public function get_edition_id_from_slug($edition_slug)
    {
        $builder = $this->db->table($this->table);

        $query = $builder->select('edition_id')
            ->where('edition_slug', $edition_slug)
            ->get();

        if ($query->getRow()) {
            return $query->getRow()->edition_id;
        } else {
            return false;
        }
    }

    public function from_id($id_array = [])
    {
        $data = [];
        if (empty($id_array)) {
            return $data;
        }
        $builder = $this->db->table($this->table);

        $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
            ->where('edition_status !=', 2)
            ->whereIn('edition_id', $id_array)
            ->orderBy('edition_date', "DESC");

        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $data[$row['edition_id']] = $row;
        }

        return $data;
    }

    public function list($from = false)
    {
        $builder = $this->db->table($this->table);

        $builder->select('edition_id, edition_name, edition_slug, edition_date, updated_date')
            ->where('edition_status !=', 2)
            ->orderBy('edition_date', "DESC");

        if ($from) {
            $builder->where('edition_date > ', date("Y-m-d 00:00", strtotime($from)));
        }

        $query = $builder->get();

        foreach ($query->getResultArray() as $row) {
            $year = date("Y", strtotime($row['edition_date']));
            $month = date("m", strtotime($row['edition_date']));
            $data[$year][$month][$row['edition_id']] = $row;
        }

        return $data;
    }

    private function add_more_edition_info($edition)
    {
        // ADD MORE INFO TO EDITION FOR LISTS
        // FUNCTIONS moved to the core controller
        $edition['edition_url'] = base_url("event/" . $edition['edition_slug']);
        // add img url
        $edition['logo_url'] = $this->get_edition_img_url($edition['edition_id'], $edition['edition_slug']);
        // add entrytype list
        // $edition['entrytype_list'] = $this->get_edition_entrytype_list($edition['edition_id']);

        return $edition;
    }

    private function get_edition_img_url($edition_id, $edition_slug)
    {
        $builder = $this->db->table("files");
        $builder->select('file_name')
            ->where('filetype_id', 1)
            ->where('file_linked_to', 'edition')
            ->where('linked_id', $edition_id);
            // dd($builder->getCompiledSelect());
            $query = $builder->get();    
        $file_name=$query->getRow()->file_name;
        return base_url("file/edition/" . $edition_slug . "/logo/" . $file_name);
    }
}
