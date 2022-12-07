<?php

namespace App\Controllers;

class User extends BaseController
{
    protected $user_model;
    public $breadcrumb;

    public function __construct()
    {
        $this->user_model = model(UserModelExtended::class);
    }

    public function dashboard()
    {
        // $this->data_to_views['user_list'] = $this->user_model->list(100);
        $result_model = model(ResultModel::class);
        $this->data_to_views['result_list'] = $result_model->list(['user_id' => user()->id]);
        return view('templates/header', $this->data_to_views)
            . view('user/dashboard')
            . view('templates/footer');
    }

    public function profile()
    {
        $this->data_to_views['validation'] =  \Config\Services::validation();

        // validation rules
        if ($this->request->getPost()) {

            $rules = [
                'name' => ['label'  => 'Name', 'rules'  => 'required',],
                'surname' => ['label'  => 'Surname', 'rules'  => 'required',],
                'email' => ['label'  => 'Email', 'rules'  => 'required|valid_email',],
            ];

            if (!$this->validate($rules)) {
                $this->data_to_views['validation'] = $this->validator;
                return view('templates/header', $this->data_to_views)
                    . view('user/profile')
                    . view('templates/footer');
            } else {
                $update = $this->user_model->set_user($this->request->getPost(), user()->id);
                $this->data_to_views['success_msg'] = "Your details has been updated";
                return view('templates/header', $this->data_to_views)
                    . view('success')
                    . view('templates/footer');
            }
        } else {
            return view('templates/header', $this->data_to_views)
                . view('user/profile')
                . view('templates/footer');
        }
    }
    public function success()
    {
        return view('templates/header', $this->data_to_views)
            . view('user/success')
            . view('templates/footer');
    }

    public function subscriptions()
    {
        helper('encrypt');

        $usersubscription_model = model(UserSubscriptionModel::class);
        $this->data_to_views['edition_subs'] = $usersubscription_model->list(user()->id, "edition");
        $this->data_to_views['newsletter_subs'] = $usersubscription_model->list(user()->id, "newsletter");

        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
            . view('user/subscriptions')
            . view('templates/footer');
    }
    public function donate()
    {
        return view('templates/header', $this->data_to_views)
            . view('user/donate')
            . view('templates/footer');
    }

    public function newsletter()
    {
        helper('encrypt');
        return view('templates/header', $this->data_to_views)
            . view('user/newsletter')
            . view('templates/footer');
    }

    public function port_users()
    {
        // empty user table
        $this->user_model->empty_table();
        // get full list of current users
        $user_list = $this->user_model->list();
        // update new user table (use ID if you can)
        $this->user_model->bulk_update($user_list);
    }

    public function subscribe($type, $email = null, $id = null)
    {
        $allowed_types = ['edition', 'newsletter'];
        if (!in_array($type, $allowed_types)) {
            return false;
        }
        if (!$email) {
            $email = $this->request->getPost('email_sub');
        }
        if (!$id) {
            if ($type == 'edition') {
                $id = $this->request->getPost('edition_id_sub');
            } else {
                $id = 0;
            }
        }

        $this->subscribe_user(["email" => $email], $type, $id);
        return redirect()->back();
    }


    public function unsubscribe($crypt)
    {
        helper('encrypt');
        // get data
        $str = my_decrypt($crypt);
        $data = explode("|", $str);
        $user_id = $data[0];
        $linked_to = $data[1];
        $linked_id = $data[2];
        // load moadels        
        $usersubscription_model = model(UserSubscriptionModel::class);
        // set negative return msg
        $this->session->setFlashdata([
            'alert_msg' => "Subscription not found. Please contact the site administrator.",
            'alert_status' => "danger",
            'alert_icon' => "minus-circle",
        ]);

        // check if the subscription exists
        if ($usersubscription_model->exists($user_id, $linked_to, $linked_id)) {
            // get basic user data
            $user_data = $this->user_model->detail($user_id);
            // remove the subscription
            $remove = $usersubscription_model->remove($user_id, $linked_to, $linked_id);
            if ($remove) {
                // set flash message
                $this->session->setFlashdata([
                    'alert_msg' => "Your have successfully been removed from the mailing list.",
                    'alert_status' => "success",
                    'alert_icon' => "minus-circle",
                ]);
            }

            // check if logged in, then redirect to my-subscriptions, else, go to edition that is being unsubscribed from, or newsletter page
            if (!logged_in()) {
                if ($linked_to == "edition") {
                    return redirect()->to(base_url("unsub_success/" . $crypt));
                } else {
                    return redirect()->to(base_url("newsletter"));
                }
            } else {
                return redirect()->to(base_url("user/my-subscriptions"));
            }
        } else {
            d("n");
            $this->session->setFlashdata([
                'alert_msg' => "Subsciption could not be found",
                'alert_status' => "danger",
                'alert_icon' => "minus-circle",
            ]);
            return redirect()->to(base_url());
        }
    }

    public function unsub_success($crypt)
    {
        helper('encrypt');
        $str = my_decrypt($crypt);
        $data = explode("|", $str);
        $linked_id = $data[2];

        $this->data_to_views['edition_info'] = $this->edition_model->detail($linked_id);
        $this->data_to_views['success_msg'] = "Your have successfully been removed from the mailing list";

        return view('templates/header', $this->data_to_views)
            . view('success')
            . view('templates/footer');
    }
}
