<?php

namespace App\Controllers;

class Favourite extends BaseController
{
    protected $favourite_model, $user_model;

    public function __construct()
    {
        $this->favourite_model = model(FavouriteModel::class);
        $this->user_model = model(UserModelExtended::class);
    }

	public function add_remove_fav()
	{		
		if ($this->favourite_model->exists($this->request->getVar('user_id'), $this->request->getVar('edition_id'))) {
			return $this->remove_fav();
		} else {
			return $this->add_fav();
		}
	}

    public function add_fav()
	{		
		$data = array(
			'user_id' => $this->request->getVar('user_id'),
			'edition_id' => $this->request->getVar('edition_id')
		);
		// set favourite		
		$fav_result = $this->favourite_model->save_data($data);
		// set subsciption
		$user_info = $this->user_model->detail($data['user_id']);
		$this->subscribe_user($user_info, 'edition', $data['edition_id']);

		if ($fav_result) {
			echo  1;
		} else {
			echo  0;
		}
	}

    public function remove_fav()
	{
        $usersubscription_model = model(UserSubscriptionModel::class);
		$data = array(
			'user_id' => $this->request->getVar('user_id'),
			'edition_id' => $this->request->getVar('edition_id')
		);
		// remove favourite
		$result = $this->favourite_model->remove_fav($data['user_id'], $data['edition_id']);
		// remove subscription
		$this->user_model->detail($data['user_id']);
        $usersubscription_model->remove($data['user_id'], 'edition', $data['edition_id']);
		if ($result) {
			echo  1;
		} else {
			echo  0;
		}
	}
    

    public function my_results()
    {
        return view('templates/header', $this->data_to_views)
            . view('results/my_results')
            . view('templates/footer');
    }
}
