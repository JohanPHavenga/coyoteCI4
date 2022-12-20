<?php

namespace App\Controllers;

class Results extends BaseController
{
    protected $result_model;

    public function __construct()
    {
        $this->result_model = model(ResultModel::class);
        $this->race_model = model(RaceModel::class);
        $this->data_to_views['status_notice_list'] = $this->status_notice_list();
        $this->data_to_views['race_distance_list'] = $this->race_model->race_distance_list();
    }

    public function list()
    {
        // add search model
        $search_model = model(SearchModel::class);
        // $this->data_to_views['edition_list'] = $search_model->results();

        $view = \Config\Services::renderer();
        $this->data_to_views['list'] = $view
            ->setVar('edition_list',  $search_model->results())
            ->setVar('status_notice_list', $this->data_to_views['status_notice_list'])
            ->render("templates/list");


        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('race/race_list')
            . view('templates/footer');
    }

    public function my_results()
    {
        if (logged_in()) {
            return redirect()->to(base_url("user/results"));
        } 

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('results/my_results')
            . view('templates/footer');
    }

    // public function search()
    // {
    //     $search_model = model(SearchModel::class);
    //     if (isset($_GET['race'])) {
    //         $ss =  $this->data_to_views['s'] = $this->request->getGet('race');
    //         $this->data_to_views['race_list_with_results'] = $search_model->my_result_search($ss, 1);
    //         $this->data_to_views['race_list_with_no_results'] = $search_model->my_result_search($ss, 0);
    //     } else {
    //         $this->data_to_views['s'] = '';
    //         $this->data_to_views['race_list_with_results'] = $this->data_to_views['race_list_with_no_results']  = [];
    //     }

    //     $this->data_to_views['race_search'] = $this->request->getVar('race');
    //     return view('templates/header', $this->data_to_views)
    //         . view('results/search')
    //         . view('templates/footer');
    // }

    // public function claim($action, $race_id, $load = "summary")
    // {

    //     // add search model
    //     switch ($action) {
    //         case "list":
    //             $att['race_id'] = $this->data_to_views['race_id'] = $race_id;
    //             if ($load == "summary") {
    //                 $att['name'] = user()->name;
    //                 $att['surname'] = user()->surname;
    //             }
    //             $this->data_to_views['result_list'] = $this->result_model->get_results_with_race_detail($att);
    //             break;
    //         default:
    //             dd("error");
    //             break;
    //     }
    //     return view('templates/header', $this->data_to_views)
    //         . view('results/claim')
    //         . view('templates/footer');
    // }
}
