<?php

namespace App\Controllers;

class Province extends BaseController
{
    protected $province_model;

    public function __construct()
    {
        $this->province_model = model(ProvinceModel::class);
    }

    public function index()
    {
        $this->data_to_views['province_list'] = $this->province_model->list();

        return view('templates/header', $this->data_to_views)
            . view('province/list')
            . view('templates/footer');
    }

    public function races($province_slug)
    {
        // get province list for footer
        $this->data_to_views['province_list'] = $this->province_model->list();
        // add search model
        $search_model = model(SearchModel::class);
        // get province ID
        $province_id = $this->province_model->get_province_id_from_slug($province_slug);
        if ($province_id) {
            $this->data_to_views['edition_list'] = $search_model->general(["province_id" => $province_id]);
        }

        return view('templates/header', $this->data_to_views)
            . view('race/race_list')
            . view('province/list')
            . view('templates/footer');
    }

    public function switch() {
        if (logged_in()) {
            $user_model = model(UserModelExtended::class);
            $update_province = $user_model->set_user(['province' => $this->request->getPost('province_switch')], user()->id);
        }
        $_SESSION['site_province']=$this->request->getPost('province_switch');
        set_cookie("site_province", $this->request->getPost('province_switch'), 172800);

        return redirect()->back();
    }
}
