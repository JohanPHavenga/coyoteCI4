<?php

namespace App\Controllers;

class Event extends BaseController
{
  protected $race_model, $url_model, $file_model;

  public function __construct()
  {
    $this->race_model = model(RaceModel::class);
    $this->url_model = model(UrlModel::class);
    $this->file_model = model(FileModel::class);
    helper('reCaptcha');
  }

  public function detail($edition_slug, $page = "detail", $param_1 = '', $param_2 = '', $param_3 = '')
  {
    // 301 redirects for old pages
    switch ($page) {
      case "accommodation":
        return redirect()->to(base_url("event/" . $edition_slug), 301);
        // return redirect(base_url("event/" . $edition_slug), 'auto', 301); 
        break;
      case "distances":
        return redirect()->to(base_url("event/" . $edition_slug . "/races"), 301);
        break;
    }

    $edition_summary = $this->edition_model->get_edition_id_from_slug($edition_slug);
    // dd($edition_summary);

    if ($edition_summary) {
      if ($edition_summary['source'] == "past") {
        // if edition name has changed
        $e = $this->edition_model->from_id([$edition_summary['edition_id']]);
        // dd($e);
        $new_slug = $e[$edition_summary['edition_id']]['edition_slug'];
        $url = base_url("event/" . $new_slug);
        return redirect()->to($url, 301);
      } elseif ($edition_summary['edition_redirect_url']) {
        // check vir 'n redirect URL 
        return redirect()->to($edition_summary['edition_redirect_url'], 301);
      } else {
        // if all is well
        $edition_id = $edition_summary['edition_id'];
        $edition_status = $edition_summary['edition_status'];
        $this->data_to_views['slug'] = $edition_slug;
      }

      $date_model = model(DateModel::class);
      $entrytype_model = model(EntrytypeModel::class);
      $regtype_model = model(RegtypeModel::class);
      $tag_model = model(TagModel::class);
      $favourite_model = model(FavouriteModel::class);

      // form validation stuffs
      $this->data_to_views['validation'] =  \Config\Services::validation();
      $this->data_to_views['scripts_to_load'] = [
        "https://www.google.com/recaptcha/api.js",
        "contact.js"
      ];

      // -- GET DATA
      $this->data_to_views['race_list'] = $race_list = $this->race_model->list($edition_id);
      $this->data_to_views['date_list'] = $date_list = $date_model->list("edition", $edition_id, false, true);
      $this->data_to_views['file_list'] = $file_list = $this->file_model->list("edition", $edition_id, true);
      $this->data_to_views['url_list'] = $url_list = $this->url_model->list("edition", $edition_id, true);
      $this->data_to_views['tag_list'] = $tag_list = $tag_model->get_edition_tag_list($edition_id);

      // Edition Data
      $edition_data = $this->edition_model->detail($edition_id, true);
      $images = $this->get_edition_img_urls($edition_id, $edition_slug, $file_list);
      $edition_data['logo_url'] = $images['logo'];
      $edition_data['thumb_url'] = $images['thumb'];
      $edition_data['edition_url'] = base_url("event/" . $edition_slug);


      $this->data_to_views['edition_data'] = $edition_data;
      $this->data_to_views['edition_data']['race_summary'] = $race_summary = $this->get_set_race_suammry($race_list, $edition_data['edition_date'], $edition_data['edition_info_prizegizing']);
      $this->data_to_views['edition_data']['entrytype_list'] = $entrytype_model->get_edition_entrytype_list($edition_id);
      $this->data_to_views['edition_data']['regtype_list'] = $regtype_model->get_edition_regtype_list($edition_id);
      $this->data_to_views['edition_data']['club_url_list'] = $this->url_model->list("club", $edition_data['club_id'], false);

      // -- CALCULATIONS
      // contact url
      $this->data_to_views['contact_url'] = base_url("contact/event/" . $edition_slug);
      $this->data_to_views['subscribe_url'] = base_url("user/subscribe/edition");
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

      // status notice
      $this->data_to_views['status_msg'] = $this->formulate_status_notice($edition_data['edition_status'], $edition_data['edition_info_status']);

      // header background image 
      $banner_img_array = ['14', '15', '17', '12', '11', '19', '18'];
      $key = array_rand($banner_img_array);
      $this->data_to_views['header_map_url'] = base_url("assets/images/banner/run_" . $banner_img_array[$key] . ".webp");

      // in past?
      if (strtotime($edition_data['edition_date']) < time()) {
        $this->data_to_views['in_past'] = true;
        $this->data_to_views['notice_banner']['msg'] = "This event has already taken place. Tap the <b>View Results</b> button to find your results";
        $this->data_to_views['notice_banner']['state'] = 'success';
      } else {
        $this->data_to_views['in_past'] = false;
      }

      // last udpated
      $this->data_to_views['last_updated'] = $this->get_timeago($edition_data['updated_date']);

      // date range
      if (($edition_data['edition_status'] == 9) || ($edition_data['edition_info_status'] == 13)) {
        // POSTPONED or UNCONFIRMED
        $this->data_to_views['event_date_range'] = "TBC";
        $this->data_to_views['edition_data']['edition_date'] = "TBC";
        foreach ($race_list as $key => $race) {
          $race_list[$key]['race_date'] = "TBC";
        }
      } else {
        if ($date_list[1][0]['date_start'] == $date_list[1][0]['date_end']) {
          $this->data_to_views['event_date_range'] = fDateHumanShort($date_list[1][0]['date_start']) . " " . fdateYear($date_list[1][0]['date_start']);
        } else {
          $this->data_to_views['event_date_range'] = fDateHumanShort($date_list[1][0]['date_start']) . "-" . fDateHumanShort($date_list[1][0]['date_end']);
        }
      }

      // race fees
      if ($race_summary['fees']['to'] > 0) {
        $this->data_to_views['race_fee_range'] = fdisplayCurrency($race_summary['fees']['from']) . "-" . fdisplayCurrency($race_summary['fees']['to']);
      } else {
        $this->data_to_views['race_fee_range'] = "Fees TBC";
      }

      // address
      $this->data_to_views['address'] = $edition_data['edition_address_end'] . ", " . $edition_data['town_name'];
      $this->data_to_views['address_nospaces'] = url_title($this->data_to_views['address'] . ", ZA");

      // page menu
      $this->data_to_views['event_menu'] = $this->get_event_menu($edition_slug, $edition_data['event_id'], $edition_id, $this->data_to_views['in_past']);
      // dd($this->data_to_views['event_menu']);
      // add running mann list to page menu is exists
      if (isset($this->data_to_views['url_list'][10])) {
        $this->data_to_views['page_menu']['more']['sub_menu']['running_mann']['display'] = $this->data_to_views['url_list'][10][0]['urltype_buttontext'] . " &nbsp;<i class='fa fa-external-link-alt'></i>";
        $this->data_to_views['page_menu']['more']['sub_menu']['running_mann']['loc'] = $this->data_to_views['url_list'][10][0]['url_name'];
      }

      // Online entries open?
      $this->data_to_views['online_entry_status'] = "unknown";
      if (isset($this->data_to_views['date_list'][3])) {
        if ((strtotime($this->data_to_views['date_list'][3][0]['date_start']) < time()) && (strtotime($this->data_to_views['date_list'][3][0]['date_end']) > time())) {
          $this->data_to_views['online_entry_status'] = "open";
        }
        if (strtotime($this->data_to_views['date_list'][3][0]['date_end']) < time()) {
          $this->data_to_views['online_entry_status'] = "closed";
        }
      }

      // PAGE LOAD LOGIC
      // override for cancelled, postponed and unconfirmed races to summary page
      $this->data_to_views['show_races'] = false;
      $this->data_to_views['show_info'] = false;
      $this->data_to_views['show_summary'] = false;
      if (
        ($edition_data['edition_status'] == 3) ||
        ($edition_data['edition_status'] == 9) ||
        ($edition_data['edition_info_status'] == 13)
      ) {
        // Cancelled 
        if ($edition_data['edition_status'] == 3) {
          $page = "detail";
          $this->data_to_views['notice_banner']['msg'] = "This event has been <b>CANCELLED</b>";
          $this->data_to_views['notice_banner']['state'] = 'error';
        }
        // Postponed
        if ($edition_data['edition_status'] == 9) {
          $page = "detail";
          $this->data_to_views['notice_banner']['msg'] = "This event has been <b>POSTPONED</b>";
          $this->data_to_views['notice_banner']['state'] = 'warning';
          $this->data_to_views['show_races'] = true;
        }
        // Prelimenary
        if ($edition_data['edition_info_status'] == 13) {
          $this->data_to_views['notice_banner']['msg'] = "Note that the date for this event is yet to be confirmed. Please add yourself to the mailing list to receive future notifications";
          $this->data_to_views['notice_banner']['state'] = 'warning';
          $this->data_to_views['show_races'] = true;
        }
      } else {
        $this->data_to_views['show_summary'] = true;
        if (!$this->data_to_views['in_past']) {
          $this->data_to_views['show_races'] = true;
          $this->data_to_views['show_info'] = true;
        }
      }


      // if results loaded, get URLS to use
      if ($edition_data['edition_info_status'] == 11) {
        $this->data_to_views['results'] = $this->get_result_arr($edition_slug);
      }

      // GPS
      $this->data_to_views['gps_parts'] = explode(",", $edition_data['edition_gps']);
      // -- end calculations

      // PAGE TITLE and META
      $this->data_to_views['page_title'] = $page_title = substr($edition_data['edition_name'], 0, -5) . " - " . fdateTitle($edition_data['edition_date']);
      if ($page == "detail") {
        $view = \Config\Services::renderer();
        $this->data_to_views['structured_data'] = $view
          ->setVar('edition_data', $edition_data)
          ->setVar('date_list', $date_list)
          ->setVar('race_list', $race_list)
          ->render("event/structured_data");
        $this->data_to_views['meta_description'] = $this->formulate_meta_description($this->data_to_views['edition_data']);
      } else {
        $this->data_to_views['page_title'] = ucwords(str_replace("-", " ", $page));
        switch ($page) {
          case "entries":
            $this->data_to_views['page_title'] = "How to enter";
            $meta_title = "Information on how to enter the ";
            break;
          case "contact":
            $this->data_to_views['page_title'] = "Contact Organisers";
            $meta_title = "Organiser contact information for the ";
            break;
          case "accommodation":
            $meta_title = "Accommodation options for the ";
            break;
          case "subscribe":
            $this->data_to_views['page_title'] = "Mailing List";
            $meta_title = "Add yourself to the mailing list for the ";
            break;
          case "results":
            $meta_title = $this->data_to_views['page_title'] . " for the ";
            foreach ($this->data_to_views['race_list'] as $race_id => $race) {
              $results = $this->race_model->get_race_detail_with_results(["race_id" => $race_id]);
              if ($results) {
                $this->data_to_views['result_list'][$race_id] = $results;
              }
            }
            // as dar iets na /results is
            if ((isset($param_1)) && (isset($this->data_to_views['results']['race']))) {
              if (is_numeric($param_1)) {
                $dist = $param_1;
                $racetype = $param_2;
                // R/W exception
                if (isset($param_3)) {
                  if (($param_3 == "W") && ($param_2 == "R")) {
                    $racetype = "R/W";
                  }
                }
                $this->data_to_views['race_data'] = $this->data_to_views['results']['race'][$racetype][$dist];
                $this->data_to_views['race_info'] = $this->data_to_views['race_list'][$this->data_to_views['race_data']['race_id']];
                $this->data_to_views['race_id'] = $this->data_to_views['race_data']['race_id'];

                $this->load->library('table');
                $this->data_to_views['css_to_load'] = [base_url("assets/js/plugins/components/datatables/datatables.min.css")];
                $this->data_to_views['scripts_to_load'] = [
                  base_url("assets/js/plugins/components/datatables/datatables.min.js"),
                  base_url("assets/js/data-tables_20200706.js"),
                ];
                // set view
                if (!empty($this->data_to_views['race_info'])) {
                  $view_to_load = "result-race";
                }
                $meta_title = $this->data_to_views['race_data']['text'] . " for the ";
                $page_title = $this->data_to_views['race_data']['distance'] . "km - " . $page_title;
              }
            }

            break;
          default:
            $meta_title = $this->data_to_views['page_title'] . " for the ";
            break;
        }
        $this->data_to_views['page_title'] = $this->data_to_views['page_title'] . " - " . $page_title;
        $this->data_to_views['meta_description'] = $meta_title . fDateYear($edition_data['edition_date']) . " edition of the " . substr($edition_data['edition_name'], 0, -5);
        $this->data_to_views['meta_description'] = str_replace("the The", "The", $this->data_to_views['meta_description']);
      }
      // -- end title and meta

      // get flat arrays
      $this->data_to_views['route_maps'] = $this->get_routemap_arr($edition_slug);
      $this->data_to_views['route_profile'] = $this->get_routeprofile_arr($edition_slug);
      $this->data_to_views['tshirt'] = $this->get_tshirt_arr($edition_slug);
      $this->data_to_views['flyer'] = $this->get_flyer_arr($edition_slug);
      $this->data_to_views['entry_form'] = $this->get_entry_form_arr($edition_slug);



      // if (file_exists(APPPATH . "views/event/" . $page . ".php")) {
      // -- LOAD VIEWS
      return view('templates/header', $this->data_to_views)
        . view('event/detail/header')
        . view('event/' . $page)
        . view('templates/footer');
      // } else {
      //   throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Event page not found');
      // }
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

  private function get_edition_img_urls($edition_id, $edition_slug, $file_list)
  {
    if (isset($file_list[1])) {
      $file_name = $file_list[1][0]['file_name'];
      $images['logo'] = base_url("file/edition/" . $edition_slug . "/logo/" . $file_name);
      if (file_exists(base_url("file/edition/" . $edition_slug . "/logo/thumb_" . $file_name))) {
        $images['thumb'] = base_url("file/edition/" . $edition_slug . "/logo/thumb_" . $file_name);
      } else {
        $images['thumb'] = base_url("file/edition/" . $edition_slug . "/logo/" . $file_name);
      }
    } else {
      $images['logo'] = base_url("assets/images/generated.webp");
      $images['thumb'] = base_url("assets/images/generated.webp");
    }
    return $images;
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

    // d($edition_date);
    // dd($race_list);

    $start_datetime = strtotime($edition_date) + 86400;

    foreach ($race_list as $race) {
      // START TIME
      if ($race['race_date'] == null) {
        $race['race_date'] = $edition_date;
      }
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
        'color' => race_color($race['race_distance']),
        'name' => $race['race_name'],
      ];
    }
    if (isset($return_arr['fees_per_race'])) {
      krsort($return_arr['fees_per_race']);
    }
    // wts($return_arr, 1);
    return $return_arr;
  }

  private function get_tshirt_arr($slug)
  {
    $tshirt_list = [];
    if (isset($this->data_to_views['file_list'][8])) {
      $tshirt_list['edition']['url'] = base_url("file/edition/" . $slug . "/t-shirt/" . $this->data_to_views['file_list'][8][0]['file_name']);
      $tshirt_list['edition']['text'] = $this->data_to_views['file_list'][8][0]['filetype_buttontext'];
      $tshirt_list['edition']['icon'] = "file-image";
      $tshirt_list['edition']['ext'] = str_replace(".", "", $this->data_to_views['file_list'][8][0]['file_ext']);
    }

    return $tshirt_list;
  }

  private function get_routemap_arr($slug)
  {
    $route_maps = [];
    if (isset($this->data_to_views['file_list'][7])) {
      $route_maps['edition']['url'] = base_url("file/edition/" . $slug . "/route map/" . $this->data_to_views['file_list'][7][0]['file_name']);
      $route_maps['edition']['text'] =  $this->data_to_views['file_list'][7][0]['filetype_buttontext'];
      $route_maps['edition']['icon'] = "file-image";
      $route_maps['edition']['ext'] = str_replace(".", "", $this->data_to_views['file_list'][7][0]['file_ext']);
    } elseif (isset($this->data_to_views['url_list'][8])) {
      $route_maps['edition']['url'] = $this->data_to_views['url_list'][8][0]['url_name'];
      $route_maps['edition']['text'] = $this->data_to_views['url_list'][8][0]['urltype_buttontext'];;
      $route_maps['edition']['icon'] = "external-link-alt";
      $route_maps['edition']['ext'] = "link";
    }

    // get race file and url lists
    foreach ($this->data_to_views['race_list'] as $race_id => $race) {
      $race_file_list = $this->file_model->list("race", $race_id, true);
      $race_url_list = $this->url_model->list("race", $race_id, true);
      if (isset($race_file_list[7])) {
        $route_maps['race'][$race_id]['url'] = base_url("file/race/" . $slug . "/route map/" . url_title($race['race_name']) . "/" . $race_file_list[7][0]['file_name']);
        $route_maps['race'][$race_id]['text'] = $race['race_name'] . " " . $race_file_list[7][0]['filetype_buttontext'];
        $route_maps['race'][$race_id]['icon'] = "file-image";
        $route_maps['race'][$race_id]['ext'] = str_replace(".", "", $race_file_list[7][0]['file_ext']);
      } elseif (isset($race_url_list[8])) {
        $route_maps['race'][$race_id]['url'] = $race_url_list[8][0]['url_name'];
        $route_maps['race'][$race_id]['text'] = $race['race_name'] . " Route Map";
        $route_maps['race'][$race_id]['icon'] = "external-link-alt";
        $route_maps['race'][$race_id]['ext'] = "link";
      }
    }

    return $route_maps;
  }

  private function get_routeprofile_arr($slug)
  {
    $route_profile = [];
    if (isset($this->data_to_views['file_list'][12])) {
      $route_profile['edition']['url'] = base_url("file/edition/" . $slug . "/route profile/" . $this->data_to_views['file_list'][12][0]['file_name']);
      $route_profile['edition']['text'] = $this->data_to_views['file_list'][12][0]['filetype_buttontext'];
      $route_profile['edition']['icon'] = "file-image";
      $route_profile['edition']['ext'] = str_replace(".", "", $this->data_to_views['file_list'][12][0]['file_ext']);
    } elseif (isset($this->data_to_views['url_list'][12])) {
      $route_profile['edition']['url'] = $this->data_to_views['url_list'][12][0]['url_name'];
      $route_profile['edition']['text'] = $this->data_to_views['url_list'][12][0]['urltype_buttontext'];;
      $route_profile['edition']['icon'] = "external-link-alt";
      $route_profile['edition']['ext'] = "link";
    }

    // get race file and url lists
    foreach ($this->data_to_views['race_list'] as $race_id => $race) {
      $race_file_list = $this->file_model->list("race", $race_id, true);
      $race_url_list = $this->url_model->list("race", $race_id, true);
      if (isset($race_file_list[12])) {
        $route_profile['race'][$race_id]['url'] = base_url("file/race/" . $slug . "/route profile/" . url_title($race['race_name']) . "/" . $race_file_list[12][0]['file_name']);
        $route_profile['race'][$race_id]['text'] = $race['race_name'] . " " . $race_file_list[12][0]['filetype_buttontext'];
        $route_profile['race'][$race_id]['icon'] = "file-image";
        $route_profile['race'][$race_id]['ext'] = str_replace(".", "", $race_file_list[12][0]['file_ext']);
      } elseif (isset($race_url_list[12])) {
        $route_profile['race'][$race_id]['url'] = $race_url_list[12][0]['url_name'];
        $route_profile['race'][$race_id]['text'] = $race['race_name'] . " Route Profile";
        $route_profile['race'][$race_id]['icon'] = "external-link-alt";
        $route_profile['race'][$race_id]['ext'] = "link";
      }
    }

    return $route_profile;
  }

  private function get_flyer_arr($slug)
  {
    $flyer_list = [];
    if (isset($this->data_to_views['file_list'][2])) {
      $flyer_list['edition']['url'] = base_url("file/edition/" . $slug . "/flyer/" . $this->data_to_views['file_list'][2][0]['file_name']);
      $flyer_list['edition']['text'] =  $this->data_to_views['file_list'][2][0]['filetype_buttontext'];
      $flyer_list['edition']['icon'] = "file-pdf";
      $flyer_list['edition']['ext'] = str_replace(".", "", $this->data_to_views['file_list'][2][0]['file_ext']);
    } elseif (isset($this->data_to_views['url_list'][2])) {
      $flyer_list['edition']['url'] = $this->data_to_views['url_list'][2][0]['url_name'];
      $flyer_list['edition']['text'] = $this->data_to_views['url_list'][2][0]['urltype_buttontext'];
      $flyer_list['edition']['icon'] = "external-link-alt";
      $flyer_list['edition']['ext'] = "link";
    }
    return $flyer_list;
  }

  private function get_entry_form_arr($slug)
  {
    $flyer_list = [];
    if (isset($this->data_to_views['file_list'][3])) {
      $flyer_list['edition']['url'] = base_url("file/edition/" . $slug . "/entry form/" . $this->data_to_views['file_list'][3][0]['file_name']);
      $flyer_list['edition']['text'] = "Download Entry Form";
      $flyer_list['edition']['icon'] = "file-pdf";
      $flyer_list['edition']['ext'] = str_replace(".", "", $this->data_to_views['file_list'][3][0]['file_ext']);
    } elseif (isset($this->data_to_views['url_list'][3])) {
      $flyer_list['edition']['url'] = $this->data_to_views['url_list'][3][0]['url_name'];
      $flyer_list['edition']['text'] = "View Entry Form";
      $flyer_list['edition']['icon'] = "external-link-alt";
      $flyer_list['edition']['ext'] = "link";
    }

    return $flyer_list;
  }

  private function get_result_arr($slug)
  {
    $results = [];
    $n = 0;
    if (isset($this->data_to_views['file_list'][4])) {
      $results['edition'][$n]['url'] = base_url("file/edition/" . $slug . "/results/" . $this->data_to_views['file_list'][4][0]['file_name']);
      $results['edition'][$n]['text'] = "Download results summary";
      switch (substr($this->data_to_views['file_list'][4][0]['file_name'], -3)) {
        case "pdf":
          $results['edition'][$n]['icon'] = "file-pdf";
          $results['edition'][$n]['ext'] = "PDF";
          break;
        case "xls":
        case "xlsx":
        case "csv":
          $results['edition'][$n]['icon'] = "file-excel";
          $results['edition'][$n]['ext'] = "XLS";
          break;
        default:
          $results['edition'][$n]['icon'] = "file-excel";
          $results['edition'][$n]['ext'] = "XLS";
          break;
      }
      $n++;
    }
    if (isset($this->data_to_views['url_list'][4])) {
      $results['edition'][$n]['url'] = $this->data_to_views['url_list'][4][0]['url_name'];
      $results['edition'][$n]['text'] = "View results";
      $results['edition'][$n]['icon'] = "external-link-alt";
      $results['edition'][$n]['ext'] = "LINK";
      $n++;
    }

    // get race file and url lists
    foreach ($this->data_to_views['race_list'] as $race_id => $race) {
      $race_file_list = $this->file_model->list("race", $race_id, true);
      $race_url_list = $this->url_model->list("race", $race_id, true);
      $round_dist = floor($race['race_distance']);
      if (isset($race_file_list[4])) {
        $results['race'][$race['racetype_abbr']][$round_dist]['url'] = base_url("file/race/" . $slug . "/results/" . url_title($race['race_name']) . "/" . $race_file_list[4][0]['file_name']);
        $results['race'][$race['racetype_abbr']][$round_dist]['text'] = $race['race_name'] . " " . $race_file_list[4][0]['filetype_buttontext'];
        $results['race'][$race['racetype_abbr']][$round_dist]['race_id'] = $race_id;
        $results['race'][$race['racetype_abbr']][$round_dist]['distance'] = $round_dist;
        switch (substr($race_file_list[4][0]['file_name'], -3)) {
          case "pdf":
            $results['race'][$race['racetype_abbr']][$round_dist]['icon'] = "file-pdf";
            $results['race'][$race['racetype_abbr']][$round_dist]['ext'] = "PDF";
            break;
          case "xls":
          case "xlsx":
          case "csv":
            $results['race'][$race['racetype_abbr']][$round_dist]['icon'] = "file-excel";
            $results['race'][$race['racetype_abbr']][$round_dist]['ext'] = "XLS";
            break;
          default:
            $results['race'][$race['racetype_abbr']][$round_dist]['icon'] = "file-excel";
            $results['race'][$race['racetype_abbr']][$round_dist]['ext'] = "XLS";
            break;
        }
        $n++;
      }
      if (isset($race_url_list[4])) {
        $results['race'][$race['racetype_abbr']][$round_dist]['url'] = $race_url_list[4][0]['url_name'];
        $results['race'][$race['racetype_abbr']][$round_dist]['text'] = $race['race_name'] . " Results";
        $results['race'][$race['racetype_abbr']][$round_dist]['icon'] = "external-link-alt";
        $results['race'][$race['racetype_abbr']][$round_dist]['icon'] = "LINK";
        $results['race'][$race['racetype_abbr']][$round_dist]['race_id'] = $race_id;
        $results['race'][$race['racetype_abbr']][$round_dist]['distance'] = $round_dist;
        $n++;
      }
    }

    return $results;
  }

  private function get_event_menu($slug, $event_id, $edition_id, $in_past)
  {

    $menu_arr = [
      "summary" => [
        "display" => "Summary",
        "loc" => base_url("event/" . $slug),
      ],
      "results" => [
        "display" => "Results",
        "loc" => base_url("event/" . $slug . "/results"),
      ],
      "entries" => [
        "display" => "How To Enter",
        "loc" => base_url("event/" . $slug . "/entries"),
      ],
      "race_day" => [
        "display" => "Race Day Info",
        "loc" => base_url("event/" . $slug . "/race-day-information"),
      ],
      "route_maps" => [
        "display" => "Route Maps",
        "loc" => base_url("event/" . $slug . "/route-maps"),
      ],
      "contact" => [
        "display" => "Race Contact",
        "loc" => base_url("event/" . $slug . "/contact"),
      ],
      // "accom" => [
      //   "display" => "Accommodation",
      //   "loc" => base_url("event/" . $slug . "/accommodation"),
      // ],
      "more" => [
        "display" => "More",
        "loc" => base_url("event/" . $slug),
        "sub_menu" => [
          "results" => [
            "display" => "Results",
            "loc" => base_url("event/" . $slug . "/results"),
          ],
          "distances" => [
            "display" => "Races",
            "loc" => base_url("event/" . $slug . "/races"),
          ],
          "entries" => [
            "display" => "How To Enter",
            "loc" => base_url("event/" . $slug . "/entries"),
          ],
          "race_day" => [
            "display" => "Race Day Info",
            "loc" => base_url("event/" . $slug . "/race-day-information"),
          ],
          "route_maps" => [
            "display" => "Route Maps",
            "loc" => base_url("event/" . $slug . "/route-maps"),
          ],
          "subscribe" => [
            "display" => "Get Notifications",
            "loc" => base_url("event/" . $slug . "/subscribe"),
          ],
          "documents" => [
            "display" => "Documents",
            "loc" => base_url("event/" . $slug . "/documents"),
          ],
          "previous" => [
            "display" => "Previous Year's Event",
            "loc" => base_url("event/" . $slug . "/"),
          ],
          "next" => [
            "display" => "Next Year's Event",
            "loc" => base_url("event/" . $slug . "/"),
          ],
          // "print" => [
          //     "display" => "Print",
          //     "loc" => base_url("event/" . $slug . "/print"),
          // ],
        ],
      ],
    ];

    // get event history to know if links should be in menu
    $event_history = $this->get_event_history($event_id, $edition_id);
    // previous
    if (isset($event_history['past'])) {
      $menu_arr['more']['sub_menu']['previous']['loc'] = base_url("event/" . $event_history['past']['edition_slug']);
    } else {
      unset($menu_arr['more']['sub_menu']['previous']);
    }
    //next
    if (isset($event_history['future'])) {
      $menu_arr['more']['sub_menu']['next']['loc'] = base_url("event/" . $event_history['future']['edition_slug']);
    } else {
      unset($menu_arr['more']['sub_menu']['next']);
    }

    // check if in past else hide to hide accommodation link
    if ($in_past) {
      unset($menu_arr['entries']);
      unset($menu_arr['race_day']);
      unset($menu_arr['route_maps']);
      unset($menu_arr['more']['sub_menu']['results']);
    } else {
      unset($menu_arr['results']);
      unset($menu_arr['more']['sub_menu']['entries']);
      unset($menu_arr['more']['sub_menu']['race_day']);
      unset($menu_arr['more']['sub_menu']['route_maps']);
    }

    return $menu_arr;
  }

  private function get_event_history($event_id, $edition_id)
  {
    $return = [];
    // get list of editions linked to this event
    $query_params = [
      "where" => ["event_id" => $event_id],
    ];
    $edition_list = $this->edition_model->list($query_params, true);

    // remove the one you are looking at
    $current_year = date("Y", strtotime($edition_list[$edition_id]['edition_date']));
    unset($edition_list[$edition_id]);

    if ($edition_list) {
      foreach ($edition_list as $edition) {
        $edition['edition_year'] = fdateYear($edition['edition_date']);
        if ($edition['edition_year'] < $current_year) {
          if (isset($return['past'])) {
            if ($edition['edition_year'] > $return['past']['edition_year']) {
              $return['past'] = $edition;
            }
          } else {
            $return['past'] = $edition;
          }
        } elseif ($edition['edition_year'] > $current_year) {
          if (isset($return['future'])) {
            if ($edition['edition_year'] < $return['future']['edition_year']) {
              $return['future'] = $edition;
            }
          } else {
            $return['future'] = $edition;
          }
        }
      }
    }
    return $return;
  }

  private function formulate_meta_description($edition_data)
  {
    $return = "Listing for the annual " .
      $edition_data['event_name'] . " in " .
      $edition_data['town_name'] . ", " .
      $edition_data['province_name'] . " on " .
      fdateHumanFull($edition_data['edition_date']) . " starting from " .
      ftimeSort($edition_data['race_summary']['times']['start']);
    return $return;
  }
}
