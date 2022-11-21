<?php

namespace App\Controllers;

class Results extends BaseController
{
    public function list()
    {
        return view('templates/header', $this->data_to_views)
            . view('results/list')
            . view('templates/footer');
    }

    public function my_results()
    {
        return view('templates/header', $this->data_to_views)
            . view('results/my_results')
            . view('templates/footer');
    }
}
