<?php

namespace App\Controllers;

class Race extends BaseController
{
    protected $search_model;

    public function __construct()
    {
        $this->search_model = model(SearchModel::class);
    }

    public function list($year = null, $month = null, $day = null)
    {
        $this->data_to_views['edition_list'] = $this->search_model->list($year, $month, $day);

        $this->data_to_views['year'] = $year;
        $this->data_to_views['month'] = $month;
        $this->data_to_views['day'] = $day;

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('race/race_list')
            . view('templates/footer');
    }

    public function upcoming()
    {
        $this->data_to_views['edition_list'] = $this->search_model->upcoming();

        // dd($this->data_to_views['edition_list']);

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function favourite()
    {
        $this->data_to_views['edition_list'] = [];
        if (logged_in()) {
            $favourite_model = model(FavouriteModel::class);
            $fav_races_ids = $favourite_model->get_favourite_list(user()->id);
            $this->data_to_views['edition_list'] = $this->edition_model->from_id($fav_races_ids);
        } else {
            $this->data_to_views['notice'] = "Please log in to be able to favourite races";
        }

        return view('templates/header', $this->data_to_views)
            . view('templates/notice')
            . view('race/race_list')
            . view('templates/footer');
    }

    public function featured()
    {
        $this->data_to_views['edition_list'] = $this->edition_model->featured(20);

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function parkrun()
    {
        return view('templates/header', $this->data_to_views)
            . view('race/parkrun')
            . view('templates/footer');
    }

    public function search()
    {
        //$this->request->getVar('name')
        if (isset($_GET['s'])) {
            $att['s'] = $this->data_to_views['search_string'];
            if (isset($_GET['t'])) {
                $t = $this->request->getGet('t');
                intval($t);
                $att['t'] = $this->request->getGet('t');
            } else {
                $att['t'] = '6';
            }
            $this->data_to_views['edition_list'] = $this->search_model->search($att);
        } else {
            $this->data_to_views['edition_list'] = [];
        }
        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function tag($tag_type, $query)
    {
        //STATUS
        $search_params['whereIn']["edition_status"] = [1];

        $search_params['where']["edition_date >= "] = date("Y-m-d 00:00:00");
        $search_params['where']["edition_date <= "] = date("Y-m-d 23:59:59", strtotime("1 year"));

        
        $search_params['orderBy']["edition_date"] = "ASC";

        $query = urldecode($query);

        switch ($tag_type) {
            case "race_name":
                $search_params['where']["race_name"] = $query;
                break;
            case "race_distance":
                $search_params['where']["race_distance"] = floatval(str_replace("km", "", $query));
                break;
            case "region_name":
                $search_params['where']["region_name"] = $query;
                break;
            case "province_name":
                $search_params['where']["province_name"] = $query;
                break;
            case "club_name":
                $search_params['where']["club_name"] = $query;
                break;
            case "town_name":
                $search_params['where']["town_name"] = $query;
                break;
            case "event_name":
                $search_params['where']["edition_date >= "] = date("2016-01-01 00:00:00");
                $search_params['where']["event_name"] = $query;
                $search_params['orderBy']["edition_date"] = "DESC";
                break;
            case "edition_year":
                return redirect()->to(base_url("calendar/" . $query));
                break;
            case "edition_month":
                $query_part = explode(" ", $query);
                $month_num = date("m", strtotime("$query_part[0]-$query_part[1]"));
                return redirect()->to(base_url("calendar/" . $query_part[1] . "/" . $month_num));
                break;
            case "asa_member_abbr":
                $search_params['where']["asa_member_abbr"] = $query;
                break;
            default:
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Tag type not found');
                break;
        }

        $this->data_to_views['edition_list'] = $this->search_model->advanced($search_params, true);

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }



    // 301 reditects
    public function virtual()
    {
        $uri = base_url('race/upcoming');
        return redirect()->to($uri, 301);
    }
    public function results()
    {
        $uri = base_url('results');
        return redirect()->to($uri, 301);
    }
    public function most_viewed()
    {
        $uri = base_url('race/upcoming');
        return redirect()->to($uri, 301);
    }
    public function history()
    {
        $uri = base_url('race/upcoming');
        return redirect()->to($uri, 301);
    }
}
