<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

class Cron extends BaseController
{
    protected $emailque_model, $search_model;
    protected $feedback_arr;

    public function __construct()
    {
        $this->emailque_model = model(EmailqueModel::class);
        $this->search_model = model(SearchModel::class);
    }

    public function intra_day()
    {
        // set to run every 5 min and continue until que empty
        while ($this->have_mail_in_mailque()) {
            $this->process_mail_que();
        }
        d($this->feedback_arr);
    }

    public function daily()
    {
        $this->runtime_log_purge("-3 months");
        $this->emailque_purge("-3 months");
        $this->search_purge("-1 month");
        $this->update_event_info_status();
        $this->autoemails_closing_date();
        $this->build_search_table();
        d($this->feedback_arr);
    }

    public function build_search_table()
    {
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();
        $control_count = 0;

        $race_model = model(RaceModel::class);
        $result_model = model(ResultModel::class);
        $file_model = model(FileModel::class);

        $this->search_model->clear_search_table();

        $field_arr = [
            "edition_id", "edition_name", "edition_slug", "edition_date", "edition_isfeatured", "edition_status", "edition_info_status",
            "event_name", "town_name", "town_name_alt", "region_id", "region_name", "regions.province_id", "province_name", "province_abbr", "club_name", 'asa_member_abbr'
        ];
        $query_params = [
            "join" => [
                "events" => "event_id",
                "towns" => "town_id",
                "regions" => "region_id",
                "provinces" => "regions.province_id=provinces.province_id",
                "edition_asa_member" => "edition_id",
                "asa_members" => "asa_member_id",
                "organising_club" => "event_id",
                "clubs" => "club_id",
            ],
        ];
        $edition_list = $this->edition_model->list($query_params, true, $field_arr);
        $race_list = $race_model->list();
        $race_result_list = $result_model->distinct_races_with_results();
        $edition_logos = $file_model->get_all_edition_logos();


        foreach ($race_list as $race_id => $race) {
            // if edition active
            if (isset($edition_list[$race['edition_id']])) {
                // set generic images
                $img_url = base_url("assets/images/generated.jpg");
                $thumb_url = base_url("assets/images/generated_thumb.jpg");
                // check if file has logo
                if (isset($edition_logos[$race['edition_id']])) {
                    $img_filepath = "uploads/edition/" . $race['edition_id'] . "/" . $edition_logos[$race['edition_id']];
                    // check if image exists (this is more for localhost purposes to make the script work without all the images)
                    if (file_exists($img_filepath)) {
                        $img_url = base_url("file/edition/" . $edition_list[$race['edition_id']]['edition_slug'] . "/logo/" . $edition_logos[$race['edition_id']]);

                        $thumb_filepath = "uploads/edition/" . $race['edition_id'] . "/thumb_" . $edition_logos[$race['edition_id']];
                        // check if thumbnail already exists
                        if (!file_exists($thumb_filepath)) {
                            // else genrate thumb                    
                            $thumb = $this->createThumbnail(
                                "uploads/edition/" . $race['edition_id'] . "/" . $edition_logos[$race['edition_id']],
                                "uploads/edition/" . $race['edition_id'] . "/thumb_" . $edition_logos[$race['edition_id']],
                                140,
                                140,
                                array(255, 255, 255)
                            );
                        }
                        $thumb_url = base_url("file/edition/" . $edition_list[$race['edition_id']]['edition_slug'] . "/logo/thumb_" . $edition_logos[$race['edition_id']]);
                    }
                }
                // d($race);
                // dd($edition_list[164]);
                $search_data[$race_id] = $edition_list[$race['edition_id']];
                $search_data[$race_id]['race_id'] = $race['race_id'];
                $search_data[$race_id]['race_name'] = $race['race_name'];
                $search_data[$race_id]['race_distance'] = $race['race_distance'];
                $search_data[$race_id]['race_distance_int'] = intval($race['race_distance']);
                $search_data[$race_id]['race_time_start'] = $race['race_time_start'];
                $search_data[$race_id]['race_color'] = race_color($race['race_distance']);
                $search_data[$race_id]['racetype_abbr'] = $race['racetype_abbr'];
                $search_data[$race_id]['racetype_icon'] = $race['racetype_icon'];
                if (in_array($race_id, $race_result_list)) {
                    $has_local_results = 1;
                } else {
                    $has_local_results = 0;
                }
                $search_data[$race_id]['has_local_results'] = $has_local_results;
                $search_data[$race_id]['img_url'] = $img_url;
                $search_data[$race_id]['thumb_url'] = $thumb_url;
                $control_count++;
                // dd($search_data);
            }
        }
        $this->search_model->set_search_table_bulk($search_data);

        $log_data['runtime_count'] = $control_count;

        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }


    // ========================================================================
    // LOGGING SCRIPTS
    // ========================================================================

    private function log_runtime($log_data)
    {
        $log_data['runtime_start'] = $log_data['start']->toDateTimeString();
        $log_data['runtime_end'] = $log_data['end']->toDateTimeString();
        // $log_data['runtime_duration'] = $log_data['start']->diff($log_data['end'])->format("%s");

        $duration = $log_data['start']->difference($log_data['end']);
        $log_data['runtime_duration'] = date('H:i:s', mktime($duration->getHours(), $duration->getMinutes(), $duration->getSeconds()));

        // wts($log_data,1);
        unset($log_data['start']);
        unset($log_data['end']);
        $this->feedback_arr[] = $log_data;
        $log = $this->edition_model->log_runtime($log_data);
    }

    private function get_date()
    {
        $time = new Time('now');
        return $time;
    }

    // ========================================================================
    // THE ACTUAL JOBS TO RUN
    // ========================================================================

    private function emailque_purge($timeframe = "-3 months")
    {
        // removes emailque data older than a year
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();
        // remove hisroty records older than a year
        $log_data['runtime_count'] = $this->emailque_model->remove_old_emails(date("Y-m-d", strtotime($timeframe)));
        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }

    private function search_purge($timeframe = "-1 month")
    {
        // removes emailque data older than a year
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();
        // remove hisroty records older than a year
        $log_data['runtime_count'] = $this->edition_model->remove_old_searches(date("Y-m-d", strtotime($timeframe)));
        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }

    private function runtime_log_purge($timeframe = "-3 months")
    {
        // removes history data older than a year
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();

        $log_data['runtime_count'] = $this->edition_model->runtime_log_cleanup(date("Y-m-d", strtotime($timeframe)));

        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }

    private function have_mail_in_mailque()
    {
        // if anything is returned, return true
        return $this->emailque_model->list(1, 5);
    }

    public function process_mail_que()
    {
        // process the mail que and sends out emails
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();

        $mail_que = [];
        $mail_que = $this->emailque_model->list($this->ini_array['emailque']['que_size'], 5);
        if ($mail_que) {
            $log_data['runtime_count'] = count($mail_que);
            foreach ($mail_que as $mail_id => $mail_data) {
                $mail_sent = $this->send_mail($mail_data);
                if ($mail_sent) {
                    $status_id = 6;
                } else {
                    $status_id = 7;
                }
                $this->emailque_model->set_emailque_status($mail_id, $status_id);
            }
        }
        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }

    private function update_event_info_status()
    {
        // script to move the event_info_status flag alog once an event has completed        
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();

        $query_params = [
            "where" => ["edition_info_status" => 16, "edition_date <= " => date("Y-m-d H:i:s", strtotime("yesterday"))],
        ];
        $edition_list_to_update = $this->search_model->advanced($query_params, true);
        if ($edition_list_to_update) {
            foreach ($edition_list_to_update as $edition_id => $edition) {
                $this->edition_model->set_edition(["edition_info_status" => 10], $edition_id);
                $this->search_model->set_search_table(["edition_info_status" => 10], 'edition_id', $edition_id);
            }
            $log_data['runtime_count'] = count($edition_list_to_update);
        } else {
            $log_data['runtime_count'] = 0;
        }

        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }

    private function autoemails_closing_date()
    {
        $log_data['runtime_jobname'] = __FUNCTION__;
        $log_data['start'] = $this->get_date();

        $date_model = model(DateModel::class);

        $query_params = [
            "whereIn" => ["edition_status" => [1, 3, 4, 17]],
            "where" => ["edition_date >= " => date("Y-m-d H:i:s"), "edition_date <= " => date("Y-m-d H:i:s", strtotime("3 months"))],
        ];
        $edition_list = $date_model->get_dates($this->search_model->advanced($query_params));

        $n = 0;
        foreach ($edition_list as $edition_id => $edition) {
            if (isset($edition['date_list'][3][0]['date_end'])) {
                if (date("Y-m-d", strtotime($edition['date_list'][3][0]['date_end'])) == date("Y-m-d", strtotime($edition['edition_date']))) {
                    $online_close_date = 0;
                } else {
                    $online_close_date = strtotime($edition['date_list'][3][0]['date_end']);
                }
            } else {
                $online_close_date = 0;
            }
            if (($online_close_date > time()) && ($online_close_date < strtotime("3 days"))) {
                // if ($this->auto_mailer(4, $edition_id)) {
                //     $n++;
                // }
            }
        }

        $log_data['runtime_count'] = $n;

        // LOG RUNTIME DATA
        $log_data['end'] = $this->get_date();
        $this->log_runtime($log_data);
    }
}
