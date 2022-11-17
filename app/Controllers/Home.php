<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $this->data_to_views['all_editions_count'] = $this->edition_model->record_count();
        $this->data_to_views['last_updated'] = $this->edition_model->last_updated(5);
        $this->data_to_views['featured'] = $this->edition_model->featured(5);

        // dd($this->data_to_views['featured']);

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
