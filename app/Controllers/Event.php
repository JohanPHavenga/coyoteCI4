<?php

namespace App\Controllers;

class Event extends BaseController
{
    protected $race_model;

    public function __construct()
    {
        $this->race_model = model(RaceModel::class);
    }

    public function detail($edition_slug)
    {

        $favourite_model = model(FavouriteModel::class);
        $date_model = model(DateModel::class);
        $file_model = model(FileModel::class);

        $edition_id = $this->edition_model->get_edition_id_from_slug($edition_slug);

        if ($edition_id) {
            // -- GET DATA
            $this->data_to_views['edition_data'] = $edition_data = $this->edition_model->detail($edition_id, true);
            $this->data_to_views['race_list'] = $race_list = $this->race_model->list($edition_id);
            $this->data_to_views['date_list'] = $date_list = $date_model->list("edition", $edition_id, false, true);
            $this->data_to_views['file_list'] = $file_list = $file_model->list("edition", $edition_id, true);

            $this->data_to_views['race_summary'] = $race_summary = $this->get_set_race_suammry($race_list, $edition_data['edition_date'], $edition_data['edition_info_prizegizing']);

            dd($race_summary);

            // -- FAVOURITE stuffs
            $this->data_to_views['favourite']['id'] = "";
            $this->data_to_views['favourite']['link'] = "#";
            $this->data_to_views['favourite']['btn_class'] = "btn-light";
            if (!logged_in()) {
                $this->data_to_views['favourite']['link'] = "href='" . base_url("login") . "'";
            } else {
                $this->data_to_views['favourite']['is_favourite'] = $is_fav = $favourite_model->get_favourite_edition(user()->id, $edition_id);
                $this->data_to_views['favourite']['id'] = "fav_but_add";
                if ($is_fav) {
                    $this->data_to_views['favourite']['btn_class'] = "btn-primary";
                    $this->data_to_views['favourite']['id'] = "fav_but_remove";
                }
            }
            // -- end favourite

            // dd($file_list);

            // -- CALCULATIONS
            // header background image 
            $banner_img_array=['14','15','17','12','11','19','18'];
            $key=array_rand($banner_img_array);
            $this->data_to_views['header_map_url'] = base_url("assets/images/banner/run_".$banner_img_array[$key].".webp");
            
            // in past?
            if (strtotime($edition_data['edition_date']) < time()) {
                $this->data_to_views['in_past'] = true;
            } else {
                $this->data_to_views['in_past'] = false;
            }
            // date range
            if ($date_list[1][0]['date_start'] == $date_list[1][0]['date_end']) {
                $this->data_to_views['event_date_range'] = fDateHumanShort($date_list[1][0]['date_start']) . " " . fdateYear($date_list[1][0]['date_start']);
            } else {
                $this->data_to_views['event_date_range'] = fDateHumanShort($date_list[1][0]['date_start'])."-".fDateHumanShort($date_list[1][0]['date_end']);
            }

            // race fees

            // -- end calculations

            // PAGE TITLE and META
            $this->data_to_views['page_title'] = $page_title = substr($edition_data['edition_name'], 0, -5) . " - " . fdateTitle($edition_data['edition_date']);
            // -- end title and meta

            // -- LOAD VIEWS
            return view('templates/header', $this->data_to_views)
                . view('event/detail')
                . view('templates/footer');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('The race could not be matched to a record in the database');
        }
    }

    public function add()
    {
        return view('templates/header', $this->data_to_views)
            . view('event/add')
            . view('templates/footer');
    }    

    private function get_set_race_suammry($race_list, $edition_date, $prize_giving_time)
  {
    if (!$race_list) {
      return false;
    }
    $return_arr = [
      "times" => [
        "start" => "",
        "end" => ""
      ],
      "fees" => [
        "from" => 10000,
        "to" => 0
      ],
      "list" => [],
    ];

    dd($race_list);

    $start_datetime = strtotime($edition_date) + 86400;

    foreach ($race_list as $race) {
      // START TIME
      if (strtotime($race['race_date']) == strtotime($edition_date)) {

        if ((strtotime($edition_date) + time_to_sec($race['race_time_start'])) < $start_datetime) {
          $start_datetime = strtotime($edition_date) + time_to_sec($race['race_time_start']);
        }
        $return_arr['times']['start'] = date("Y-m-d H:i:s", $start_datetime);

        // END TIME
        if (time_to_sec($prize_giving_time) > 0) {
          $return_arr['times']['end'] = date("Y-m-d H:i:s", strtotime($edition_date) + time_to_sec($prize_giving_time));
        }
      }

      // FEES
      $fee_fields_to_check = ['race_fee_flat', 'race_fee_senior_licenced', 'race_fee_senior_unlicenced'];
      foreach ($fee_fields_to_check as $field) {
        if ($race[$field] != 0) {
          if ($race[$field] < $return_arr['fees']['from']) {
            $return_arr['fees']['from'] = $race[$field];
          }
          if ($race[$field] > $return_arr['fees']['to']) {
            $return_arr['fees']['to'] = $race[$field];
          }

          $return_arr['fees_per_race'][intval($race['race_distance'])]['name'] = $race['race_name'];
          $return_arr['fees_per_race'][intval($race['race_distance'])]['fees'][$field] = $race[$field];
        }
      }
      // LIST
      $return_arr['list'][] = [
        'distance' => $race['race_distance'],
        'type' => $race['racetype_name'],
        'abbr' => $race['racetype_abbr'],
        'color' => $race['race_color'],
        'name' => $race['race_name'],
      ];
    }
    if (isset($return_arr['fees_per_race'])) {
      krsort($return_arr['fees_per_race']);
    }
    // wts($return_arr, 1);
    return $return_arr;
  }
}
