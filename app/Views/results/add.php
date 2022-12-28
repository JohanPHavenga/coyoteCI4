<!-- Dashboard Container -->
<div class="dashboard-container">

    <?= $user_sidebar; ?>

    <!-- Dashboard Content
	================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Add Result</h3>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url("user/dashboard"); ?>">Dashboard</a></li>
                        <li><a href="<?= base_url("user/results"); ?>">Results</a></li>
                        <li>Add</li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <?php
                $attributes = ['class' => 'row', 'id' => 'user-form'];
                echo form_open(base_url('result/add/' . $race_id), $attributes);
                ?>
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">
                        <!-- Headline -->
                        <div class="headline">
                            <h3>Manual Result Add</h3>
                        </div>
                        <div class="content with-padding padding-bottom-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-uppercase margin-bottom-10"><mark class="color" style="background-color: <?= race_color(intval($race_info['race_distance'])); ?>"><?= fraceDistance($race_info['race_distance']); ?></mark></h3>
                                    <h4 class="text-uppercase margin-bottom-5"><?= $race_info['edition_name']; ?></h4>
                                    <h5 class="text-uppercase" style="color: #999;"><?= fdateHumanFull($race_info['edition_date'], true); ?></h5>
                                </div>
                            </div>
                            <div class="row margin-top-20">
                                <div class="col-md-12">
                                    <p>Use the form below to add a manual result to the race for yourself.<br>
                                        <mark><b>PLEASE NOTE:</b></mark> Should official results be loaded for this race in the future, your manual result will be overwritten.
                                    </p>
                                    <?php
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
                                    ?>
                                    <div class="col">
                                        <div class="row">
                                            <?php
                                            $field_data = [
                                                'id' => 'event_date',
                                                'placeholder' => 'Event Date',
                                                'value' => set_value('event_date', date("Y-m-d", strtotime($race_info['edition_date']))),
                                                'class' => 'with-border',
                                                'type' => 'date',
                                                'disabled' => '',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_time',
                                                'id' => 'result_time',
                                                'placeholder' => 'Your Time',
                                                'value' => set_value('result_time'),
                                                'class' => 'with-border',
                                                'type' => 'time',
                                                'step' => 1,
                                                'required' => '',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_pos',
                                                'id' => 'result_pos',
                                                'placeholder' => 'Your Overall Position',
                                                'value' => set_value('result_pos', 0),
                                                'class' => 'with-border',
                                                'type' => 'number',
                                                'min' => 0,
                                                'required' => '',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                    <small>Leave as 0 if not certain</small>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_cat',
                                                'id' => 'result_cat',
                                                'class' => 'with-border',
                                                'placeholder' => 'Category',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_dropdown($field_data['name'], $category_dropdown, set_value($field_data['name'], ''), [
                                                        'id' => $field_data['id'],
                                                        'class' => $field_data['class'],
                                                    ]); ?>
                                                    <small>If you know it</small>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_racenum',
                                                'id' => 'result_racenum',
                                                'placeholder' => 'Race Number',
                                                'value' => set_value('result_racenum', 0),
                                                'class' => 'with-border',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_asanum',
                                                'id' => 'result_asanum',
                                                'placeholder' => 'License Number',
                                                'value' => set_value('result_asanum', 0),
                                                'class' => 'with-border',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_name',
                                                'id' => 'result_name',
                                                'placeholder' => 'Name',
                                                'value' => set_value('result_name', user()->name),
                                                'class' => 'with-border',
                                                'required' => ''
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_surname',
                                                'id' => 'result_surname',
                                                'placeholder' => 'Surname',
                                                'value' => set_value('result_surname', user()->surname),
                                                'class' => 'with-border',
                                                'required' => ''
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_sex',
                                                'id' => 'result_sex',
                                                'class' => 'with-border',
                                                'placeholder' => 'Gender',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_dropdown($field_data['name'], ["" => "", "M" => "Male", "F" => "Female"], set_value($field_data['name'], ''), [
                                                        'id' => $field_data['id'],
                                                        'class' => $field_data['class'],
                                                        'required' => ''
                                                    ]); ?>
                                                </div>
                                            </div>

                                            <?php
                                            $field_data = [
                                                'name' => 'result_age',
                                                'id' => 'result_age',
                                                'placeholder' => 'Your Age',
                                                'value' => set_value('result_age', 0),
                                                'class' => 'with-border',
                                                'type' => 'number',
                                                'min' => 0,
                                                'max' => 100,
                                                'required' => '',
                                            ];
                                            ?>
                                            <div class="col-xl-6">
                                                <div class="submit-field">
                                                    <?= form_label($field_data['placeholder'], $field_data['id']); ?>
                                                    <?= form_input($field_data); ?>
                                                    <small>At the time of the race</small>
                                                </div>
                                            </div>


                                            <?php
                                            echo form_hidden('race_id', $race_id);
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Row / End -->
                </div>
                <div class="col-xl-12">
                    <button class="button ripple-effect big margin-top-30" type="submit">Add Result</button>
                </div>
                <?php
                echo form_close();
                ?>
            </div>
            <!-- Dashboard Content / End -->

            <?= $user_footer; ?>
        </div>
    </div>