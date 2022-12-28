<?php

namespace App\Controllers;

class User extends BaseController
{
    protected $user_model, $result_model;
    public $breadcrumb;

    public function __construct()
    {
        $this->user_model = model(UserModelExtended::class);
        $this->result_model = model(ResultModel::class);

        $router = service('router');
        $method  = $router->methodName();

        $view = \Config\Services::renderer();
        $menus = $this->get_menus();
        $this->data_to_views['user_sidebar'] = $view
            ->setVar('user_menu', $menus['user_menu'])
            ->setVar('method', $method)
            ->render("user/sidebar");

        $view = \Config\Services::renderer();
        $this->data_to_views['user_footer'] = $view->render("user/footer");
    }

    public function dashboard()
    {
        $sub_model = model(UserSubscriptionModel::class);

        $result_list = $this->result_model->list(['user_id' => user()->id]);
        $this->data_to_views['result_num'] = count($result_list);
        $this->data_to_views['sub_num'] = $sub_model->exists(user()->id);
        foreach ($result_list as $result) {
            $key = "dist_" . round($result['race_distance']);
            $this->data_to_views["result_list"][$key][$result['result_id']] = $result;
        }
        $this->data_to_views["result_count"] = $this->result_model->get_userresult_count(user()->id);
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
                    . view('user/profile')
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

    public function subscriptions($show_all = null)
    {
        helper('encrypt');
        $sub_id_list = [];

        $usersubscription_model = model(UserSubscriptionModel::class);
        $search_model = model(SearchModel::class);
        $edition_subs = $usersubscription_model->list(user()->id, "edition");
        foreach ($edition_subs as $subs) {
            $sub_id_list[] = $subs['edition_id'];
        }
        $this->data_to_views['edition_subs'] = $search_model->from_edition_id($sub_id_list);
        $this->data_to_views['newsletter_subs'] = $usersubscription_model->list(user()->id, "newsletter");

        $this->data_to_views['show_all'] = $show_all;

        return view('templates/header', $this->data_to_views)
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
        $this->data_to_views['page_title'] = "Subscribe to our Newsletter";
        helper('encrypt');
        return view('templates/header', $this->data_to_views)
            . view('templates/title_bar')
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
            // d("n");
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

    public function results()
    {
        $this->data_to_views['user_result_list'] = $this->result_model->list(['user_id' => user()->id]);

        $sub_model = model(UserSubscriptionModel::class);

        $result_list = $this->result_model->list(['user_id' => user()->id]);
        $this->data_to_views['result_num'] = count($result_list);
        $this->data_to_views['sub_num'] = $sub_model->exists(user()->id);
        foreach ($result_list as $result) {
            $key = "dist_" . round($result['race_distance']);
            $this->data_to_views["result_list"][$key][$result['result_id']] = $result;
        }
        $this->data_to_views["result_count"] = $this->result_model->get_userresult_count(user()->id);

        return view('templates/header', $this->data_to_views)
            . view('user/results')
            . view('templates/footer');
    }

    public function result_search()
    {
        $search_model = model(SearchModel::class);
        if (isset($_GET['race'])) {
            $ss =  $this->data_to_views['s'] = $this->request->getGet('race');
            $this->data_to_views['race_list_with_results'] = $search_model->my_result_search($ss, 1);
            $this->data_to_views['race_list_with_no_results'] = $search_model->my_result_search($ss, 0);
        } else {
            $this->data_to_views['s'] = '';
            $this->data_to_views['race_list_with_results'] = $this->data_to_views['race_list_with_no_results']  = [];
        }

        $this->data_to_views['race_search'] = $this->request->getVar('race');
        return view('templates/header', $this->data_to_views)
            . view('results/search')
            . view('templates/footer');
    }

    public function result_claim($action, $race_id, $load = "summary")
    {

        // add search model
        switch ($action) {
            case "list":
                $att['race_id'] = $this->data_to_views['race_id'] = $race_id;
                if ($load == "summary") {
                    $att['name'] = user()->name;
                    $att['surname'] = user()->surname;
                }
                $this->data_to_views['result_list'] = $this->result_model->get_results_with_race_detail($att);
                break;

            case "result":
                $result_id = $race_id;
                if (is_numeric($result_id)) {
                    // check if already exists
                    if (!$this->result_model->exists(user()->id, $result_id)) {
                        $this->result_model->set_result(["user_id" => user()->id, "result_id" => $result_id]);
                        $this->session->setFlashdata(['alert_msg' => "Result has been added to your profile",]);
                        return redirect()->to(base_url("user/results"));
                    } else {
                        $this->session->setFlashdata(['alert_msg' => "That result is already linked to your profile",]);
                        return redirect()->back();
                    }
                } else {
                    $this->session->setFlashdata(['alert_msg' => "That result does not exist. Use the form below to find race results",]);
                    return redirect()->to(base_url("user/results"));
                }
                break;

            default:
                $this->session->setFlashdata(['alert_msg' => "Unknown action. Please tray again",]);
                return redirect()->to(base_url("user/results"));
                break;
        }
        return view('templates/header', $this->data_to_views)
            . view('results/claim')
            . view('templates/footer');
    }

    public function result_view($result_id)
    {
        $race_model = model(RaceModel::class);
        if ((is_numeric($result_id)) && ($this->result_model->exists(user()->id, $result_id))) {
            $result = $race_model->get_race_detail_with_results(["result_id" => $result_id]);
            $this->data_to_views['result_detail'] = $result[$result_id];

            // dd($this->data_to_views['result_detail']);

            if ($this->data_to_views['result_detail']['file_id']) {
                $this->data_to_views['result_detail']['official_result'] = 1;
            } else {
                $this->data_to_views['result_detail']['official_result'] = 0;
                if ($this->data_to_views['result_detail']['result_pos'] == 0) {
                    $this->data_to_views['result_detail']['result_pos'] = "Unknown";
                }
            }
            $this->data_to_views['page_title'] = "Detail for result #" . $result_id;
            $this->data_to_views['meta_description'] = "Result details for " . $result[$result_id]['result_name'] . " " . $result[$result_id]['result_surname'] . " in the " . $result[$result_id]['edition_name'] . " race.";

            return view('templates/header', $this->data_to_views)
                . view('results/view')
                . view('templates/footer');
        } else {
            $this->session->setFlashdata(['alert_msg' => "That result does not exist. Use the form below to find race results",]);
            return redirect()->to(base_url("user/results"));
        }
    }

    public function result_remove($result_id)
    {
        if ((is_numeric($result_id)) && ($this->result_model->exists(user()->id, $result_id))) {
            $user_id = user()->id;
            // check if already exists
            $this->result_model->remove_result($user_id, $result_id);
            $this->session->setFlashdata(['alert_msg' => "Result has been removed from your profile",]);
        } else {
            $this->session->setFlashdata(['alert_msg' => "You are not linked to that result",]);
        }
        return redirect()->to(base_url("user/results"));
    }

    public function result_add($race_id)
    {
        $race_model = model(RaceModel::class);;
        $this->data_to_views['race_id'] = $race_id;
        // get race info
        $this->data_to_views['race_info'] = $race_model->detail($race_id);

        //Category Dropdown
        $this->data_to_views['category_dropdown'] = [
            "" => "",
            "Junior" => "Junior",
            "Senior" => "Senior",
            "40-49" => "40-49",
            "50-59" => "50-59",
            "60-69" => "60-69",
            "70+" => "70+",
        ];

        $this->data_to_views['page_title'] = "Add result manually";

        $this->data_to_views['validation'] =  \Config\Services::validation();

        // validation rules
        if ($this->request->getPost()) {

            $rules = [
                'result_time' => ['label'  => 'Time', 'rules'  => 'trim|required',],
                'result_pos' => ['label'  => 'Position', 'rules'  => 'trim|required',],
                'result_name' => ['label'  => 'Name', 'rules'  => 'required|trim',],
                'result_surname' => ['label'  => 'Surname', 'rules'  => 'required|trim',],
                'result_sex' => ['label'  => 'Gender', 'rules'  => 'required',],
            ];

            if (!$this->validate($rules)) {
                $this->data_to_views['validation'] = $this->validator;
                return view('templates/header', $this->data_to_views)
                    . view('results/add')
                    . view('templates/footer');
            } else {
                // write result to result table
                $result_id = $this->result_model->set_results_table($this->request->getPost());
                // write to user_result table
                $this->result_model->set_result(["user_id" => user()->id, "result_id" => $result_id]);
                $this->session->setFlashdata(['alert_msg' => "Result has been added to your profile",]);
                return redirect()->to(base_url("user/results"));
                // redirect to user/my-results
            }
        } else {
            return view('templates/header', $this->data_to_views)
                . view('results/add')
                . view('templates/footer');
        }

    }
}
