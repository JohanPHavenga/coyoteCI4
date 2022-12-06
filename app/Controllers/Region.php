<?php

namespace App\Controllers;

class Region extends BaseController
{
    protected $region_model;
    protected $search_model;

    public function __construct()
    {
        $this->region_model = model(RegionModel::class);
        $this->search_model = model(SearchModel::class);
    }

    public function list($region_name = null)
    {
        $view_to_load = "race/race_list";
        $this->data_to_views['edition_list'] = [];
        $region_slug_list = $this->region_model->slug_list();

        if ($region_name) {
            switch ($region_name) {
                case "capetown":
                    $this->data_to_views['edition_list'] = $this->search_model->region([2, 3, 4, 5, 6, 63]);
                    break;
                case "gauteng":
                    $this->data_to_views['edition_list'] = $this->search_model->region([26, 27, 28, 29, 30]);
                    break;
                case "kzn-coast":
                    $this->data_to_views['edition_list'] = $this->search_model->region([35, 32]);
                    break;
                case "garden-route":
                    $this->data_to_views['edition_list'] = $this->search_model->region([62]);
                    break;
                default:
                    // normal regions
                    $rn=strtolower($region_name);
                    if (array_key_exists($rn, $region_slug_list)) {
                        $this->data_to_views['edition_list'] = $this->search_model->region([$region_slug_list[$rn]['region_id']]);
                    } else {
                        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('The region could not be matched to a record in the database');
                    }
                    break;
            }
        } else {
            $province_model = model(ProvinceModel::class);
            $province_list = $province_model->list();
            $region_list = $this->region_model->list();
            foreach ($region_list as $province_id => $region_list_proper) {
                $this->data_to_views['region_list'][$province_list[$province_id]['province_abbr']]['province'] = $province_list[$province_id];
                $this->data_to_views['region_list'][$province_list[$province_id]['province_abbr']]['region_list'] = $region_list_proper;
            }
            ksort($this->data_to_views['region_list']);
            $view_to_load = "region/list";
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
