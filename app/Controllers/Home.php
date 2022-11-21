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

    public function error404()
    {
        $this->output->set_status_header('404');
        $this->load->view('error404');
    }

    public function faq()
    {
        return view('templates/header', $this->data_to_views)
            . view('faq')
            . view('templates/footer');
    }

    public function training_programs()
    {
        return view('templates/header', $this->data_to_views)
            . view('training-programs')
            . view('templates/footer');
    }

    public function friends()
    {
        return view('templates/header', $this->data_to_views)
            . view('friends')
            . view('templates/footer');
    }

    public function support()
    {
        return view('templates/header', $this->data_to_views)
            . view('support')
            . view('templates/footer');
    }

    public function sitemap()
    {
        return view('templates/header', $this->data_to_views)
            . view('sitemap')
            . view('templates/footer');
    }

    public function terms_conditions()
    {
        return view('templates/header', $this->data_to_views)
            . view('terms-conditions')
            . view('templates/footer');
    }

    public function disclaimer()
    {
        return view('templates/header', $this->data_to_views)
            . view('disclaimer')
            . view('templates/footer');
    }

    public function privacy_policy()
    {
        return view('templates/header', $this->data_to_views)
            . view('privacy_policy')
            . view('templates/footer');
    }
}