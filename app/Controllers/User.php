<?php

namespace App\Controllers;

class User extends BaseController
{
    protected $user_model;

    public function __construct()
    {
        $this->user_model = model(RaceModel::class);
    }

    public function dashboard()
    {
        return view('templates/header', $this->data_to_views)
            . view('user/dashboard')
            . view('templates/footer');
    }

    public function profile()
    {
        return view('templates/header', $this->data_to_views)
            . view('user/profile')
            . view('templates/footer');
    }
    
    public function subscriptions()
    {
        return view('templates/header', $this->data_to_views)
            . view('user/subscriptions')
            . view('templates/footer');
    }
    public function dontate()
    {
        return view('templates/header', $this->data_to_views)
            . view('user/dontate')
            . view('templates/footer');
    }

    public function newsletter()
    {
        return view('templates/header', $this->data_to_views)
            . view('user/newsletter')
            . view('templates/footer');
    }
}
