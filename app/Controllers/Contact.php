<?php

namespace App\Controllers;

class Contact extends BaseController
{
    public function index()
    {
        return view('templates/header', $this->data_to_views)
            . view('contact')
            . view('templates/footer');
    }
}
