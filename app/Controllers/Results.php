<?php

namespace App\Controllers;

class Results extends BaseController
{
    public function list()
    {
        // add search model
        $search_model = model(SearchModel::class);
        $this->data_to_views['edition_list'] = $search_model->results();
        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function my_results()
    {
        return view('templates/header', $this->data_to_views)
            . view('results/my_results')
            . view('templates/footer');
    }
}
