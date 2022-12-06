<h1>Profile</h1>
<p>Update your details by using the form below</p>
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


$attributes = ['class' => 'row', 'id' => 'user-form'];
echo form_open(base_url('user/profile'), $attributes);

$field_data = [
    'name' => 'name',
    'id' => 'name',
    'placeholder' => 'Name',
    'value' => set_value('name', user()->name, true),
    'class' => 'form-control',
];
echo '<div class="col-6 form-group">';
echo form_label("Name", "name");
echo form_input($field_data);
echo "</div>";

$field_data = [
    'name' => 'surname',
    'id' => 'surname',
    'placeholder' => 'Surname',
    'value' => set_value('name', user()->surname, true),
    'class' => 'form-control',
];
echo '<div class="col-6 form-group">';
echo form_label("Surname", "surname");
echo form_input($field_data);
echo "</div>";

$field_data = [
    'name' => 'email',
    'id' => 'email',
    'type' => 'email',
    'placeholder' => 'Email Address',
    'value' => set_value('email', user()->email, true),
    'class' => 'form-control',
];
echo '<div class="col-12 form-group">';
echo form_label("Email", "email");
echo form_input($field_data);
echo "</div>";

$field_data = [
    'name' => 'phone',
    'id' => 'phone',
    'placeholder' => 'Phone Number',
    'value' => set_value('name', user()->phone, true),
    'class' => 'form-control',
];
echo '<div class="col-6 form-group">';
echo form_label("Phone", "phone");
echo form_input($field_data);
echo "</div>";

$field_data = [
    'value' => 'Submit',
    'class' => 'btn btn-secondary',
];
echo '<div class="col-12">';
echo form_submit($field_data);
echo "</div>";
echo form_close();
