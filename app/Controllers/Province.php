<?php

namespace App\Controllers;

class Province extends BaseController
{
    public function list()
    {
        return view('templates/header', $this->data_to_views)
            . view('province/list')
            . view('templates/footer');
    }
}
