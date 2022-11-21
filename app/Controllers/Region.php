<?php

namespace App\Controllers;

class Region extends BaseController
{

    public function __construct()
    {
    }

    public function list($region_name = null)
    {

        $view_to_load = "race/race_list";
        $this->data_to_views['edition_list'] = [];
        switch ($region_name) {
            case "capetown":
                break;
            case "gauteng":
                break;
            case "kzn-coast":
                break;
            case "garden-route":
                break;
            default:
                $view_to_load = "region/list";
                break;
        }

        return view('templates/header', $this->data_to_views)
            . view($view_to_load)
            . view('templates/footer');
    }

    public function switch()
    {
        return view('templates/header', $this->data_to_views)
            . view('region/switch')
            . view('templates/footer');
    }
}
