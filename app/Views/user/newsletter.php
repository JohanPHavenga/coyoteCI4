<h4>Subscribe to newsletter</h4>
<?php
$field_data = [
    'name'  => 'email_sub',
    'id'    => 'email_sub',
];
if (logged_in()) {
    $field_data['value'] = set_value('email_sub', user()->email, true);
    $sub_form_url = base_url('user/subscribe/newsletter');
    $method = 'post';
} else {
    $sub_form_url = base_url('login');
    $method = 'get';
}

$attributes = ['id' => 'subscribe_form', 'method' => $method];
echo form_open($sub_form_url, $attributes);
echo form_input($field_data);

$button_data = [
    'id'      => 'subscribe',
    'type'    => 'submit',
    'content' => 'Add to mailing list',
];
echo form_button($button_data);;
echo form_close();


?>

