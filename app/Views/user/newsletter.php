<div class="container">
    <div class="row">

        <div class="col-xl-9 col-lg-8 content-right-offset">
            <p>Please enter your email address below to subscribe to our monthly newsletter. If you are not logged in, you will be redirected to the login page.</p>
            <div class="row">
                <div class="col-lg-8">
                    <?php
                    $field_data = [
                        'name'  => 'email_sub',
                        'id'    => 'email_sub',
                        'placeholder' => 'Email Address',
                        'class' => 'with-border',
                        'required' => 'required',
                        'type' => 'email',
                    ];

                    if (logged_in()) {
                        $field_data['value'] = set_value('email_sub', user()->email, true);
                        $sub_form_url = base_url('user/subscribe/newsletter');
                        $method = 'post';
                    } else {
                        $sub_form_url = base_url('login');
                        $method = 'get';
                    }

                    $attributes = ['id' => 'subscribe_form', 'class' => 'search, inline-form', 'method' => $method];
                    echo form_open($sub_form_url, $attributes);

                    echo '<div class="input-with-icon-left">';
                    echo form_input($field_data);
                    echo '<i class="icon-material-outline-email"></i>';
                    echo '</div>';

                    $button_data = [
                        'id'      => 'subscribe',
                        'type'    => 'submit',
                        'content' => '<i class="icon-feather-arrow-right"></i>',
                    ];
                    echo form_button($button_data);;
                    echo form_close();
                    ?>
                </div>
            </div>

            <h3 class="margin-top-30 margin-bottom-20">Why subscribe?</h3>
            <p>
                If you subscribe to our newsletter you will receive a <b>monthly</b> update of results loaded for the events that was,
                plus a list of upcoming events over the next two months.</p>
            <p>
                It is still a <b>work in progress</b>, so please, if there is any suggestions out there to make it better, hit the
                reply button and give me a piece of your mind. </p>

        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="sidebar-container margin-top-15">
            </div>
        </div>

    </div>
</div>