<?php

namespace App\Controllers;

class Race extends BaseController
{
    protected $search_model;

    public function __construct()
    {
        $this->search_model = model(SearchModel::class);
    }

    public function list($year = null, $month = null, $day = null)
    {
        $this->data_to_views['edition_list'] = $this->search_model->list($year, $month, $day);

        $this->data_to_views['year'] = $year;
        $this->data_to_views['month'] = $month;
        $this->data_to_views['day'] = $day;

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function search()
    {
        //$this->request->getVar('name')
        if (isset($_GET['s'])) {
            $att['s'] = $this->request->getGet('s');
            if (isset($_GET['t'])) {
                $t = $this->request->getGet('t');
                intval($t);
                $att['t'] = $this->request->getGet('t');
            } else {
                $att['t'] = '6';
            }
            $this->data_to_views['edition_list'] = $this->search_model->search($att);
        }
        return view('templates/header', $this->data_to_views)
            . view('race/search')
            . view('templates/footer');
    }

    public function upcoming()
    {
        $this->data_to_views['edition_list'] = $this->search_model->upcoming();

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function favourite()
    {
        $this->data_to_views['edition_list'] = [];

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function featured()
    {
        $this->data_to_views['edition_list'] = $this->edition_model->featured(20);

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function parkrun()
    {
        return view('templates/header', $this->data_to_views)
            . view('race/parkrun')
            . view('templates/footer');
    }

    // 301 reditects
    public function virtual()
    {
        $uri = base_url('race/upcoming');
        return redirect()->to($uri, 301);
    }
    public function results()
    {
        $uri = base_url('results');
        return redirect()->to($uri, 301);
    }
    public function most_viewed()
    {
        $uri = base_url('race/upcoming');
        return redirect()->to($uri, 301);
    }
    public function history()
    {
        $uri = base_url('race/upcoming');
        return redirect()->to($uri, 301);
    }
}
