<?php

namespace App\Controllers;

class Race extends BaseController
{
    protected $search_model;

    public function __construct()
    {
        $this->search_model = model(SearchModel::class);
        $this->race_model = model(RaceModel::class);
        $this->data_to_views['status_notice_list'] = $this->status_notice_list();
        $this->data_to_views['race_distance_list'] = $this->race_model->race_distance_list();
    }

    public function list($year = null, $month = null, $day = null)
    {
        $this->data_to_views['edition_list'] = $this->search_model->list($year, $month, $day);

        $this->data_to_views['year'] = $year;
        $this->data_to_views['month'] = $month;
        $this->data_to_views['day'] = $day;

        $this->data_to_views['page_title'] = '';
        if (isset($day)) {
            $this->data_to_views['page_title'] = $day;
        }
        if (isset($month)) {
            $month_name = date("F", mktime(0, 0, 0, intval($month), 10));
            $this->data_to_views['page_title'] =  $this->data_to_views['page_title'] . " " . $month_name;
            $this->data_to_views['bc_arr'] = $this->replace_key($this->data_to_views['bc_arr'], intval($month), $month_name);
        }
        if (isset($year)) {
            $this->data_to_views['page_title'] =  $this->data_to_views['page_title'] . " " . $year;
        }

        // dd($this->data_to_views['bc_arr']);

        $view = \Config\Services::renderer();
        $this->data_to_views['list'] = $view
            ->setVar('edition_list',  $this->search_model->list($year, $month, $day))
            ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
            ->render("templates/list");


        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('race/race_list')
            . view('templates/footer');
    }

    public function upcoming()
    {
        $this->data_to_views['page_title'] = "Upcoming Races";

        $view = \Config\Services::renderer();
        $this->data_to_views['list'] = $view
            ->setVar('edition_list',  $this->search_model->upcoming("+3 months"))
            ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
            ->render("templates/list");

        // dd($this->data_to_views['edition_list']);

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('race/race_list')
            . view('templates/footer');
    }

    public function favourite()
    {
        $this->data_to_views['page_title'] = "Bookmarked Races";
        if (logged_in()) {
            $favourite_model = model(FavouriteModel::class);
            $fav_races_ids = $favourite_model->get_favourite_list(user()->id);
            // $this->data_to_views['edition_list'] = $this->edition_model->from_id($fav_races_ids);

            $view = \Config\Services::renderer();
            $this->data_to_views['list'] = $view
                ->setVar('edition_list',  $this->search_model->from_edition_id($fav_races_ids))
                ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
                ->render("templates/list");
        } else {
            $this->data_to_views['notice'] = "Please <b><a href='" . base_url('login') . "'>log in</a></b> to enable this functionality";
        }

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('race/bookmarked')
            . view('templates/footer');
    }

    public function featured()
    {
        $this->data_to_views['page_title'] = "Featured Races";
        $featured_list = $this->edition_model->featured(20);

        $view = \Config\Services::renderer();
        $this->data_to_views['list'] = $view
            ->setVar('edition_list',  $this->search_model->from_edition_id(array_keys($featured_list)))
            ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
            ->render("templates/list");

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('race/featured')
            . view('templates/footer');
    }

    public function parkrun()
    {
        $this->data_to_views['bc_arr'] = $this->replace_key($this->data_to_views['bc_arr'], $this->data_to_views['page_title'], "parkrun");
        $this->data_to_views['page_title'] = "parkrun";
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('race/parkrun')
            . view('templates/footer');
    }

    public function search()
    {
        // dd($_GET);
        $att = [];
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
        }

        // DISTANCE
        if (isset($_GET['distance'])) {
            foreach ($_GET['distance'] as $distance) {
                $att['distance'][] = $distance;
            }
        }
        // LOCATION
        if (isset($_GET['location'])) {
            $att['location'] = $_GET['location'];
        }
        // VERIFIED
        if (isset($_GET['verified'])) {
            $att['verified'] = $_GET['verified'];
        }

        // $this->data_to_views['edition_list'] = $this->search_model->search($att);

        if (!empty($_GET)) {
            $view = \Config\Services::renderer();
            $this->data_to_views['list'] = $view
                ->setVar('edition_list', $this->search_model->search($att))
                ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
                ->render("templates/list");
        } 

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
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

        // $this->data_to_views['edition_list'] = $this->search_model->advanced($search_params, true);

        $view = \Config\Services::renderer();
        $this->data_to_views['list'] = $view
            ->setVar('edition_list', $this->search_model->advanced($search_params, true))
            ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
            ->render("templates/list");

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
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
