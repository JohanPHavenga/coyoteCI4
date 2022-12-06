<?php

namespace App\Controllers;

class Contact extends BaseController
{
    public function index($status = null)
    {
        helper('reCaptcha');
        
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
                'message' => ['label'  => 'Message', 'rules'  => 'required',],
                'reCaptcha3' => ['label'  => 'Message', 'rules'  => 'required|reCaptcha3[contactForm,0.9]',],
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
                    . view('contact/form')
                    . view('templates/footer');
            } else {
                // stuur email
                $this->send_email();
                $this->data_to_views['status'] = $status;
                return view('templates/header', $this->data_to_views)
                    . view('contact/success')
                    . view('templates/footer');
            }
        } else {
            return view('templates/header', $this->data_to_views)
                . view('contact/form')
                . view('templates/footer');
        }
    }

    public function send_email()
    {
        $email = \Config\Services::email();

        $to = ' info@roadrunning.co.za';
        $subject = 'Website Contact: ' . $this->request->getVar('name');

        $email->setTo($to);
        // $email->setBCC("johan.havenga@gmail.com");
        $email->setFrom($this->request->getVar('email'), $this->request->getVar('name'));
        $email->setSubject($subject);
        $message = "<h3>Website Contact Form</h3><p>"
            . "<b>Name:</b> " . $this->request->getVar('name') . "<br>"
            . "<b>Email:</b> " . $this->request->getVar('email') . "</p>"
            . "<p style='padding-left: 15px; padding-bottom:0; margin: 20px 0; border-left: 4px solid #ccc;'><b>Message:</b><br>" . nl2br($this->request->getVar('message')) . "</p>";

        $email->setMessage($message);

        if ($email->send()) {
            return true;
        } else {
            $data = $email->printDebugger(['headers']);
            dd($data);
            return false;
        }
    }
}
