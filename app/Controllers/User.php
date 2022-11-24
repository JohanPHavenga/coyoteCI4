<?php

namespace App\Controllers;

class User extends BaseController
{
    protected $user_model;

    public function __construct()
    {
        $this->user_model = model(UserModelExtended::class);
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

    public function port_users() {
        // empty user table
        $this->user_model->empty_table();
        // get full list of current users
        $user_list=$this->user_model->list();
        // update new user table (use ID if you can)
        $this->user_model->bulk_update($user_list);
    }
}
