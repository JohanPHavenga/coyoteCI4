<?php

namespace App\Controllers;

class Contact extends BaseController
{
    public function __construct()
    {
        helper('reCaptcha');
    }

    public function index($status = null)
    {

        $this->data_to_views['validation'] =  \Config\Services::validation();
        $this->data_to_views['scripts_to_load'] = [
            "https://www.google.com/recaptcha/api.js",
            "contact.js"
        ];

        // validation rules
        if ($this->request->getPost()) {

            $rules = [
                'name' => ['label'  => 'Name', 'rules'  => 'required',],
                'email' => ['label'  => 'Email', 'rules'  => 'required|valid_email',],
                'subject' => ['label'  => 'Subject', 'rules'  => 'required',],
                'comments' => ['label'  => 'Message', 'rules'  => 'required',],
                'reCaptcha3' => ['label'  => 'reCaptcha3', 'rules'  => 'required|reCaptcha3[contactForm,0.9]',],
                // 'g-recaptcha-response' => ['label'  => 'Captcha', 'rules'  => 'recaptcha',],
            ];
            // $errors = [
            //     'g-recaptcha-response' => [
            //         'recaptcha' => 'Please tick the reCaptcha box'
            //     ]
            // ];

            if (!$this->validate($rules)) {
                $this->data_to_views['validation'] = $this->validator;
                return view('templates/header', $this->data_to_views)
                    . view('templates/title_bar')
                    . view('contact/form')
                    . view('templates/footer');
            } else {
                // stuur email
                $message = "<h3>Website Contact Form</h3><p>"
                    . "<b>Name:</b> " . $this->request->getVar('name') . "<br>"
                    . "<b>Email:</b> " . $this->request->getVar('email') . "</p>"
                    . "<p style='padding-left: 15px; padding-bottom:0; margin: 20px 0; border-left: 4px solid #ccc;'><b>Message:</b><br>" . nl2br($this->request->getPost('comments')) . "</p>";
                $att = [
                    "name" => $this->request->getPost('name'),
                    "email" => $this->request->getPost('email'),
                    "subject" => $this->request->getPost("subject"),
                    "message" => $message
                ];
                $this->send_email($att);
                $this->session->setFlashdata([
                    'alert_msg' => "Thank you. Your message has been send. We will get back to you shortly",
                ]);
                return redirect()->back();
            }
        } else {
            return view('templates/header', $this->data_to_views)
                . view('templates/title_bar')
                . view('contact/form')
                . view('templates/footer');
        }
    }

    public function event($slug = null)
    {
        $this->data_to_views['validation'] =  \Config\Services::validation();
        $this->data_to_views['scripts_to_load'] = [
            "https://www.google.com/recaptcha/api.js",
            "contact.js"
        ];

        $edition_summary = $this->edition_model->get_edition_id_from_slug($slug);
        $this->data_to_views['edition_data'] = $edition_data = $this->edition_model->detail($edition_summary['edition_id'], true);
        $this->data_to_views['contact_url'] = base_url("contact/event/" . $slug);
        $this->data_to_views['slug'] = $slug;
        // echo $slug;
        // d($this->data_to_views['edition_data']);
        // dd($_POST);

        // validation rules
        if ($this->request->getPost()) {

            $rules = [
                'name' => ['label'  => 'Name', 'rules'  => 'required',],
                'email' => ['label'  => 'Email', 'rules'  => 'required|valid_email',],
                'subject' => ['label'  => 'Subject', 'rules'  => 'required',],
                'comments' => ['label'  => 'Message', 'rules'  => 'required',],
                'reCaptcha3' => ['label'  => 'reCaptcha3', 'rules'  => 'required|reCaptcha3[contactForm,0.9]',],
            ];

            if (!$this->validate($rules)) {
                $this->data_to_views['validation'] = $this->validator;
                return view('templates/header', $this->data_to_views)
                    . view('event/contact')
                    . view('templates/footer');
            } else {
                // stuur email
                $message = "<h3>Query from race listing on Roadrunning.co.za</h3><p>"
                    . "<b>Name:</b> " . $this->request->getVar('name') . "<br>"
                    . "<b>Email:</b> " . $this->request->getVar('email') . "<br>"
                    . "<b>Event Name:</b> " . $edition_data['edition_name'] . "<br>"
                    . "<b>Event Date:</b> " . fdateHuman($edition_data['edition_date']) . "</p>"
                    . "<p style='padding-left: 15px; padding-bottom:0; margin: 20px 0; border-left: 4px solid #ccc;'><b>Message:</b><br>" . nl2br($this->request->getPost('comments')) . "</p>";
                $att = [
                    "to" => $this->data_to_views['edition_data']['user_email'],
                    "name" => $this->request->getPost('name'),
                    "email" => $this->request->getPost('email'),
                    "subject" => $this->request->getPost("subject"),
                    "message" => $message
                ];
                $this->send_email($att);
                $this->session->setFlashdata([
                    'alert_msg' => "Thank you. Your message has been send to the organisers",
                ]);
                return redirect()->to(base_url('event/' . $slug));
            }
        } else {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No information send to form');
        }
    }
}
