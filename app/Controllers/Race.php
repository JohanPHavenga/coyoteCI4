<?php

namespace App\Controllers;

class Race extends BaseController
{
    protected $search_model;

    public function __construct()
    {
        $this->search_model = model(SearchModel::class);
    }

    public function search()
    {
        $this->data_to_views['edition_list'] = $this->search_model->upcoming("1 week");

        // dd($this->data_to_views['featured']);

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function upcoming($year=null, $month=null, $day=null)
    {
        $this->data_to_views['edition_list'] = $this->search_model->upcoming();

        $this->data_to_views['year']=$year;
        $this->data_to_views['month']=$month;
        $this->data_to_views['day']=$day;

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }

    public function favourite()
    {
        $this->data_to_views['edition_list']=[];

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

    public function most_viewed()
    {
        $this->data_to_views['edition_list'] = [];

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('templates/footer');
    }       
    
    public function history()
    {
        return view('templates/header', $this->data_to_views)
            . view('race/history')
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
        $uri=base_url();
        return redirect()->to($uri, 301);
    }
    public function results() 
    {        
        $uri=base_url('results');
        return redirect()->to($uri, 301);
    }
}
