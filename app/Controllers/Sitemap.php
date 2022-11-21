<?php

namespace App\Controllers;

class Sitemap extends BaseController
{
    public function index()
    {
        $this->data_to_views['edition_list']=$this->edition_model->get_edition_list();
        
        return view('templates/header', $this->data_to_views)
            . view('sitemap/view')
            . view('templates/footer');
    }

    public function xml()
    {
        return view('sitemap/xml');
    }
}
