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
        $favourite_model = model(FavouriteModel::class);
        $edition_id = $this->edition_model->get_edition_id_from_slug($edition_slug);

        if ($edition_id) {
            $this->data_to_views['edition'] = $this->edition_model->detail($edition_id);
            $this->data_to_views['race_list'] = $this->race_model->get_race_list($edition_id);

            // Favourite stuffs
            $this->data_to_views['favourite']['id'] = "";
            $this->data_to_views['favourite']['link'] = "#";
            $this->data_to_views['favourite']['btn_class'] = "btn-light";
            if (!logged_in()) {
                $this->data_to_views['favourite']['link'] = "href='" . base_url("login") . "'";
            } else {
                $this->data_to_views['favourite']['is_favourite'] = $is_fav = $favourite_model->get_favourite_edition(user()->id, $edition_id);
                $this->data_to_views['favourite']['id'] = "fav_but_add";
                if ($is_fav) {
                    $this->data_to_views['favourite']['btn_class'] = "btn-primary";
                    $this->data_to_views['favourite']['id'] = "fav_but_remove";
                }
            }

            return view('templates/header', $this->data_to_views)
                . view('event/detail')
                . view('templates/footer');
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('The race could not be matched to a record in the database');
        }
    }

    public function add()
    {
        return view('templates/header', $this->data_to_views)
            . view('event/add')
            . view('templates/footer');
    }
}
