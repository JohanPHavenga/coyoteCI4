<?php
d($edition);
d($race_list);
d($favourite);
?>
<h4>Favourite Button</h4>
<?php
$data = [
    'class'    => "btn " . $favourite['btn_class'] . " btn-xs",
    'id'      => $favourite['id'],
    'type'    => 'submit',
    'content' => 'Favourite',
];
echo form_button($data);;
?>

<h4>Subscribe form</h4>
<?php
$field_data = [
    'name'  => 'email_sub',
    'id'    => 'email_sub',
];
if (logged_in()) {
    $field_data['value'] = set_value('email_sub', user()->email, true);
    $sub_form_url = base_url('user/subscribe/edition');
    $method = 'post';
} else {
    $sub_form_url = base_url('login');
    $method = 'get';
}

$attributes = ['id' => 'subscribe_form', 'method' => $method];
echo form_open($sub_form_url, $attributes);
echo form_input($field_data);

$field_data = [
    'name'  => 'edition_id_sub',
    'id'    => 'edition_id_sub',
    'value' => $edition['edition_id'],
    'type' => 'hidden'
];
echo form_input($field_data);

$button_data = [
    'id'      => 'subscribe',
    'type'    => 'submit',
    'content' => 'Add to mailing list',
];
echo form_button($button_data);;
echo form_close();
?>