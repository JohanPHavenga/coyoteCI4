<?php

namespace App\Controllers;

class Event extends BaseController
{
    protected $race_model;

    public function __construct()
    {
        $this->race_model = model(RaceModel::class);
    }

    public function detail($edition_slug)
    {
        $edition_id=$this->edition_model->get_edition_id_from_slug($edition_slug);
        $this->data_to_views['edition'] = $this->edition_model->get_edition_detail($edition_id);
        $this->data_to_views['race_list'] = $this->race_model->get_race_list($edition_id);


        return view('templates/header', $this->data_to_views)
            . view('event/detail')
            . view('templates/footer');
    }
}
