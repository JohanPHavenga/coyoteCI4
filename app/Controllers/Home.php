<?php

namespace App\Controllers;

class Home extends BaseController
{
    protected $race_model;

    public function __construct()
    {
        $this->race_model = model(RaceModel::class);
        $this->result_model = model(ResultModel::class);
        $this->search_model = model(SearchModel::class);
    }

    public function index()
    {
        $this->data_to_views['page_title']="RoadRunning.co.za - Run without being chased";
        $this->data_to_views['transparent_header'] = true;

        $this->data_to_views['all_editions_count'] = $this->edition_model->record_count();
        $this->data_to_views['all_races_count'] = $this->race_model->record_count();
        $this->data_to_views['all_races_with_results_count'] = count($this->result_model->distinct_races_with_results());

        $this->data_to_views['race_distance_list'] = $this->race_model->race_distance_list();
        $this->data_to_views['status_notice_list'] = $this->status_notice_list();

        $this->data_to_views['last_updated'] = $this->edition_model->last_updated(5);

        $view = \Config\Services::renderer();
        $featured_list = $this->data_to_views['featured'] = $this->edition_model->featured(5);
        $this->data_to_views['featured_list'] = $view
            ->setVar('edition_list',  $this->search_model->from_edition_id(array_keys($featured_list)))
            ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
            ->render("templates/list");

            
        $view = \Config\Services::renderer();
        $last_updated = $this->data_to_views['last_updated'] = $this->edition_model->last_updated(5);
        $this->data_to_views['last_updated_list'] = $view
            ->setVar('edition_list', $this->search_model->from_edition_id(array_keys($last_updated)))
            ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
            ->render("templates/list");

        // dd($this->data_to_views['featured']);

        return view('templates/header', $this->data_to_views)
            . view('home')
            . view('templates/footer');
    }

    public function about()
    {
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('about')
            . view('templates/footer');
    }

    public function error404()
    {
        $this->output->set_status_header('404');
        $this->load->view('error404');
    }

    public function faq()
    {
        $this->data_to_views['bc_arr'] = $this->replace_key($this->data_to_views['bc_arr'], $this->data_to_views['page_title'], "FAQ");
        $this->data_to_views['page_title'] = "Frequently Asked Questions";
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('faq')
            . view('templates/footer');
    }

    public function training_programs($race_name = null)
    {
        if (isset($race_name)) {
            switch (strtolower(urldecode($race_name))) {
                case "marathon":
                    $t_prog_text = "Marathon Training Programs";
                    $t_prog_link = "https://coachparry.com/marathon-training-roadmap/?via=roadrunningza";
                    break;
                case "half-marathon":
                case "half marathon":
                    $t_prog_text = "Half-Marathon Training Programs";
                    $t_prog_link = "https://coachparry.com/half-marathon-training-roadmap/?via=roadrunningza";
                    break;
                case "10km":
                case "10km road":
                case "10km-road":
                case "10km-run":
                case "10km run":
                case "10km race":
                case "10km-race":
                    $t_prog_text = "10K Training Programs";
                    $t_prog_link = "https://coachparry.com/10k-training-roadmap/?via=roadrunningza";
                    break;
                default:
                    $t_prog_text = "Training Programs";
                    $t_prog_link = "https://coachparry.com/training-programmes/#run?via=roadrunningza";
                    break;
            }
        } else {
            $t_prog_text = "Training Programs";
            $t_prog_link = "https://coachparry.com/training-programmes/#run?via=roadrunningza";
        }

        $this->data_to_views['page_title'] = $t_prog_text;
        $this->data_to_views['meta_description'] = "Link through to " . $t_prog_text . "s from Coach Parry";
        $this->data_to_views['coach_parry_link'] = $t_prog_link;
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('training-programs')
            . view('templates/footer');
    }

    public function friends()
    {
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('friends')
            . view('templates/footer');
    }

    public function support()
    {
        $this->data_to_views['page_title'] = "Support the website";

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('support')
            . view('templates/footer');
    }

    public function sitemap()
    {
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('sitemap')
            . view('templates/footer');
    }

    public function terms_conditions()
    {
        $this->data_to_views['page_title'] = "Terms & Conditions";
        $this->data_to_views['companyName'] = "RoadRunningZA";
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('terms-conditions')
            . view('templates/footer');
    }

    public function disclaimer()
    {
        $this->data_to_views['companyName'] = "RoadRunningZA";
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('disclaimer')
            . view('templates/footer');
    }

    public function privacy_policy()
    {
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('privacy_policy')
            . view('templates/footer');
    }
}
