<!-- Dashboard Container -->
<div class="dashboard-container">

    <?= $user_sidebar; ?>

    <!-- Dashboard Content
	================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Results</h3>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url("user/dashboard"); ?>">Dashboard</a></li>
                        <li><a href="<?= base_url("user/results"); ?>">Results</a></li>
                        <li>Search</li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12">
                    <?php
                    if ((empty($race_list_with_results)) && (empty($race_list_with_no_results))) {
                    ?>
                        <div class="notification error closeable">
                            <p>Not races found with the search string provided</p>
                            <a class="close"></a>
                        </div>
                        <?php
                    } else {
                        if (!empty($race_list_with_results)) {
                        ?>
                            <div class="dashboard-box main-box-in-row">
                                <div class="content with-padding">
                                    <div class="col-xl-12">
                                        <div class="dashboard-box margin-top-0 margin-bottom-50">

                                            <!-- Headline -->
                                            <div class="headline">
                                                <h3><i class="icon-material-outline-business"></i> Races With Results</h3>
                                            </div>

                                            <div class="content">
                                                <ul class="dashboard-box-list">
                                                    <?php
                                                    foreach ($race_list_with_results as $race) {
                                                        $link = base_url('result/claim/list/' . $race['race_id']);
                                                    ?>
                                                        <li>
                                                            <div class="boxed-list-item">
                                                                <!-- Content -->
                                                                <div class="item-content">
                                                                    <h4><?= $race['edition_name'] . " | " . $race['race_name']; ?></h4>
                                                                </div>
                                                            </div>

                                                            <a href="<?= $link; ?>" class="button ripple-effect margin-top-5 margin-bottom-10">View <i class="icon-material-outline-arrow-right-alt"></i></a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        }

                        if (!empty($race_list_with_no_results)) {
                        ?>
                            <div class="dashboard-box main-box-in-row">
                                <div class="content with-padding">
                                    <div class="col-xl-12">
                                        <div class="dashboard-box">

                                            <!-- Headline -->
                                            <div class="headline">
                                                <h3><i class="icon-material-outline-business"></i> Races Without Linked Results</h3>
                                            </div>

                                            <div class="content">
                                                <div class="notification warning closeable">
                                                    <p><b>No loaded results found</b>. Click on the table row to MANUALLY ADD a result for yourself for the race listed</p>
                                                    <p style='font-size: 0.9em'>Note this is for personal record kepping purposes only. Official results used for qualifying times needs to be send from the official timekeepers website directly.</p>
                                                    <a class="close"></a>
                                                </div>
                                                <ul class="dashboard-box-list">
                                                    <?php
                                                    foreach ($race_list_with_no_results as $race) {
                                                        $link = base_url('result/add/' . $race['race_id']);
                                                    ?>
                                                        <li>
                                                            <div class="boxed-list-item">
                                                                <!-- Content -->
                                                                <div class="item-content">
                                                                    <h4><?= $race['edition_name'] . " | " . $race['race_name']; ?></h4>
                                                                </div>
                                                            </div>

                                                            <a href="<?= $link; ?>" class="button ripple-effect margin-top-5 margin-bottom-10">Manually Add <i class="icon-material-outline-add"></i></a>
                                                        </li>
                                                    <?php
                                                    }
                                                    ?>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                    <div class="dashboard-box main-box-in-row">
                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-business"></i> Search for races</h3>
                        </div>
                        <div class="content with-padding">
                            <div class="row">
                                <div class="col-xl-12">
                                    <?php
                                    $search_url = base_url("result/search");
                                    $attributes = array('class' => 'search, inline-form', 'id' => 'search_results', 'method' => 'get');
                                    echo form_open($search_url, $attributes);

                                    echo form_input([
                                        'name' => 'race',
                                        'id' => 'race_name',
                                        'value' => set_value('result_search', $s),
                                        'class' => 'form-control required',
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
                        </div>
                    </div>

                </div>
            </div>
            <!-- end dashboard-box -->

        </div>
    </div>

    <?= $user_footer; ?>

</div>
<!-- Dashboard Container / End -->