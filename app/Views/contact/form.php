<h1>Contact Us</h1>
<p>Use the form below to send your query to us</p>
<?php

$errors = $validation->getErrors();
if (!empty($errors)) {
?>
    <div class="alert alert-danger" role="alert">
        <ul>
            <?php foreach ($errors as $error) { ?>
                <li><?= esc($error) ?></li>
            <?php } ?>
        </ul>
    </div>
<?php
}


$attributes = ['class' => 'row', 'id' => 'contact-form'];
echo form_open(base_url('contact'), $attributes);

$field_data = [
    'name' => 'name',
    'id' => 'name',
    'placeholder' => 'Name & Surname',
    'value' => set_value('name'),
    'class' => 'form-control',
];
echo '<div class="col-6 form-group">';
echo form_input($field_data);
echo "</div>";

$field_data = [
    'name' => 'email',
    'id' => 'email',
    'placeholder' => 'Email Address',
    'value' => set_value('email'),
    'class' => 'form-control',
];
echo '<div class="col-12 form-group">';
echo form_input($field_data);
echo "</div>";

$field_data = [
    'name' => 'message',
    'id' => 'message',
    'cols' => '10',
    'rows' => '6',
    'placeholder' => 'Message',
    'value' => set_value('message'),
    'class' => 'form-control',
];
echo '<div class="col-12 form-group">';
echo form_textarea($field_data);
echo "</div>";

echo reCaptcha3('reCaptcha3', ['id' => 'recaptcha_v3'], ['action' => 'contactForm']);

$field_data = [
    'value' => 'submit',
    'class' => 'btn btn-secondary',
];
echo '<div class="col-12">';
echo form_submit($field_data);
echo "</div>";
echo form_close();
