<?php

namespace App\Controllers\Admin;

use App\Controllers\AdminController;

class Dashboard extends AdminController
{
    public function index()
    {     
        return view('admin/templates/header', $this->data_to_views)
            . view('admin/dashboard')
            . view('admin/templates/footer');
    }
}