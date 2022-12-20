<!-- Dashboard Container -->
<div class="dashboard-container">

    <?= $user_sidebar; ?>

    <!-- Dashboard Content
	================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Profile</h3>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url("user/dashboard"); ?>">Dashboard</a></li>
                        <li>Profile</li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">

                <?php
                $attributes = ['class' => 'row', 'id' => 'user-form'];
                echo form_open(base_url('user/profile'), $attributes);

                // ERRORS
                $errors = $validation->getErrors();
                if (!empty($errors)) {
                ?>
                    <div class="col-xl-12">
                        <div class="notification error closeable">
                            <ul>
                                <?php foreach ($errors as $error) { ?>
                                    <li><?= esc($error) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php
                }

                // SUCCESS MSG
                if (isset($success_msg)) {
                ?>
                    <div class="col-xl-12">
                        <div class="notification success closeable">
                            <p><?= $success_msg; ?></p>
                            <a class="close"></a>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">

                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-account-circle"></i> My Account</h3>
                        </div>

                        <div class="content with-padding padding-bottom-0">
                            <div class="row">
                                <?php
                                $img_url = (!empty(user()->picture)) ? user()->picture : base_url('assets/images/user-avatar-placeholder.png');
                                ?>
                                <div class="col-auto">
                                    <div class="avatar-wrapper" data-tippy-placement="bottom" data-tippy="" data-original-title="User Avatar">
                                        <img class="profile-pic" src="<?= $img_url; ?>" alt="">
                                        <!-- <div class="upload-button"></div>
                                        <input class="file-upload" type="file" accept="image/*"> -->
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="row">

                                        <?php
                                        $field_data = [
                                            'name' => 'name',
                                            'id' => 'name',
                                            'placeholder' => 'Name',
                                            'value' => set_value('name', user()->name, true),
                                            'class' => 'with-border',
                                        ];
                                        ?>
                                        <div class="col-xl-6">
                                            <div class="submit-field">
                                                <?= form_label($field_data['placeholder'], $field_data['name']); ?>
                                                <?= form_input($field_data); ?>
                                            </div>
                                        </div>

                                        <?php
                                        $field_data = [
                                            'name' => 'surname',
                                            'id' => 'surname',
                                            'placeholder' => 'Last Name',
                                            'value' => set_value('surname', user()->surname, true),
                                            'class' => 'with-border',
                                        ];
                                        ?>
                                        <div class="col-xl-6">
                                            <div class="submit-field">
                                                <?= form_label($field_data['placeholder'], $field_data['name']); ?>
                                                <?= form_input($field_data); ?>
                                            </div>
                                        </div>

                                        <?php
                                        $field_data = [
                                            'name' => 'email',
                                            'id' => 'email',
                                            'type' => 'email',
                                            'placeholder' => 'Email Address',
                                            'value' => set_value('email', user()->email, true),
                                            'class' => 'with-border',
                                        ];
                                        ?>
                                        <div class="col-xl-6">
                                            <div class="submit-field">
                                                <?= form_label($field_data['placeholder'], $field_data['name']); ?>
                                                <?= form_input($field_data); ?>
                                            </div>
                                        </div>

                                        <?php
                                        $field_data = [
                                            'name' => 'phone',
                                            'id' => 'phone',
                                            'placeholder' => 'Phone Number',
                                            'value' => set_value('phone', user()->phone, true),
                                            'class' => 'with-border',
                                        ];
                                        ?>
                                        <div class="col-xl-6">
                                            <div class="submit-field">
                                                <?= form_label($field_data['placeholder'], $field_data['name']); ?>
                                                <?= form_input($field_data); ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end Content Box -->

                    </div>
                    <!-- end Dahshboard Box-->
                </div>

                <div class="col-xl-12">
                    <button class="button ripple-effect big margin-top-30" type="submit">Save Changes</button>
                </div>

                <?php
                    echo form_close();
                ?>

            </div>
            <!-- Row / End -->

            <?= $user_footer; ?>

        </div>
    </div>
    <!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->