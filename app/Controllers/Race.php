<?php

namespace App\Controllers;

class Race extends BaseController
{
    protected $search_model;

    public function __construct()
    {
        $this->search_model = model(SearchModel::class);
    }

    public function upcoming()
    {
        $this->data_to_views['edition_list'] = $this->search_model->upcoming();

        // dd($this->data_to_views['featured']);

        return view('templates/header', $this->data_to_views)
            . view('race/upcoming')
            . view('templates/footer');
    }

}
