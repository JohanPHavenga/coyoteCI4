<!-- Spacer / End-->
<div class="container">
    <div class="row">

        <div class="col-xl-9 col-lg-8 content-right-offset">
            <?php
            if (isset($list)) {
            ?>
                <div class="sort-by">
                    <span>Sort by:</span>
                    <?php
                    // sorting switch form
                    $sort_options = [
                        "date" => "Date",
                        "distance" => "Distance",
                    ];
                    $attributes = ['class' => '', 'id' => 'sort_by_picker', 'method' => 'post'];
                    echo form_open(base_url('race/sort_picker'), $attributes);

                    $js = "class='selectpicker hide-tick' onchange='this.form.submit()'";
                    echo form_dropdown('sort_by_picker', $sort_options, $_SESSION['sort_by'], $js);
                    echo form_close();
                    ?>
                </div>

            <?php
                echo $list;
            } else {
            ?>
                <div class="row">
                    <div class="col-xl-12">
                        <?php
                        $search_url = base_url("search");
                        $attributes = array('class' => 'search, inline-form', 'id' => 'search_results', 'method' => 'get');
                        echo form_open($search_url, $attributes);

                        echo form_input([
                            'name' => 's',
                            'id' => 'search_form',
                            'value' => $search_string,
                            'placeholder' => 'Search for a race',
                            'autocomplete' => 'off',
                            'required' => '',
                        ]);

                        $button_data = [
                            'id' => 'subscribe',
                            'type' => 'submit',
                            'content' => '<i class="icon-feather-arrow-right"></i>',
                        ];
                        echo form_button($button_data);;
                        echo form_close();
                        ?>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="sidebar-container margin-top-10">
                <?php
                // search form
                $attributes = ['class' => '', 'id' => 'side_bar_search', 'method' => 'get'];
                echo form_open(base_url('search'), $attributes);
                if (isset($_GET['s'])) {
                    $data = [
                        'name'  => 's',
                        'id'    => 'hidden_search_field',
                        'value' => $_GET['s'],
                        'placeholder' => 'Search',
                        'type' => 'hidden',
                    ];
                    echo form_input($data);
                };
                ?>
                <!-- Distances -->
                <div class="sidebar-widget">
                    <h3>Distances</h3>
                    <select class="selectpicker" multiple data-selected-text-format="count" data-size="7" title="All Distances" name="distance[]">
                        <?php
                        foreach ($race_distance_list as $distance => $name) {
                            $sel = '';
                            if (isset($_GET['distance'])) {
                                if (in_array($distance, $_GET['distance'])) {
                                    $sel = "selected";
                                } else {
                                    $sel = '';
                                }
                            }
                            echo "<option value='$distance' $sel>$name</option>";
                        }
                        ?>
                    </select>
                </div>

                <!-- Location -->
                <div class="sidebar-widget">
                    <h3 class="margin-top-15">Location</h3>
                    <div class="input-with-icon">
                        <div id="autocomplete-container">
                            <?php
                            if (isset($_GET['location'])) {
                                $loc = $_GET['location'];
                            } else {
                                $loc = '';
                            }
                            ?>
                            <input id="" type="text" placeholder="Location" name="location" value="<?= $loc; ?>">
                        </div>
                        <i class="icon-material-outline-location-on"></i>
                    </div>
                </div>

                <!-- Job Types -->
                <div class="sidebar-widget">
                    <h3>Options</h3>

                    <div class="switches-list">
                        <div class="switch-container">
                            <?php
                            $sel = '';
                            if (isset($_GET['verified'])) {
                                $sel = 'checked';
                            }
                            ?>
                            <label class="switch"><input type="checkbox" name="verified" value="1" <?= $sel; ?>><span class="switch-button"></span> Verified Information</label>
                        </div>
                    </div>

                </div>
                <?php
                $data = [
                    'type'    => 'submit',
                    'content' => 'Search <i class="icon-feather-arrow-right"></i>',
                    'class' => 'button'
                ];
                echo form_button($data);;
                echo form_close();
                ?>



            </div>
        </div>

    </div>
</div>
<?php
// d($edition_list);
