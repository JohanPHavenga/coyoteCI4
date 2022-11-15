<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('templates/header', $this->data_to_views)
            . view('home')
            . view('templates/footer');
    }

    public function about()
    {
        return view('templates/header', $this->data_to_views)
            . view('about')
            . view('templates/footer');
    }
}
