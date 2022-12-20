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
                        <li>Results</li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12">

                    <div class="dashboard-box main-box-in-row">
                        <div class="headline">
                            <h3><i class="icon-material-outline-library-add"></i> Link results</h3>
                        </div>
                        <div class="content with-padding">
                            <div class="row">
                                <div class="col-xl-12">
                                    <p>Use the search form below to search for your results to add them to your profile. Alternatively
                                        you can add results manually if the result set is not available.
                                    </p>
                                    <?php
                                    $search_url = base_url("result/search");
                                    $attributes = array('class' => 'search, inline-form', 'id' => 'search_results', 'method' => 'get');
                                    echo form_open($search_url, $attributes);

                                    echo "<div>";
                                    echo form_input([
                                        'name' => 'race',
                                        'id' => 'race_name',
                                        'value' => set_value('result_search'),
                                        'class' => 'form-control required',
                                        'placeholder' => 'Search for a race',
                                        'autocomplete' => 'off',
                                        'required' => '',
                                    ]);
                                    echo "</div>";

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
                    <!-- end dashboard-box -->

                    <!-- <div class="col-lg-4 m-b-20">
                        <h4 class="text-uppercase">Auto Search</h4>
                        <p>Use your name & surname to auto find suggested results</p>
                        <a href="<?= base_url("result/auto"); ?>" class="btn btn-primary">Auto Search</a>
                    </div> -->
                </div>

                <div class="col-xl-12">
                    <?php
                    if (empty($result_count)) {
                    ?>
                        <div class="notification warning closeable margin-top-30">
                            <p>This is your dashboard showing your results. You currently have no linked results. <br>Start by <a href='/user/my-results'>searching and claiming</a> results.</p>
                            <a class="close"></a>
                        </div>
                        <?php
                    } else {
                        foreach ($result_count as $key => $rc) {
                        ?>
                            <!-- Dashboard Box -->
                            <div class="dashboard-box main-box-in-row">
                                <div class="headline">
                                    <h3><i class="icon-feather-bar-chart-2"></i> <?= $rc['name']; ?></h3>
                                </div>
                                <div class="content">
                                    <!-- Chart -->
                                    <!-- <div class="chart">
                                        <canvas id="chart" width="100" height="45"></canvas>
                                    </div> -->
                                    <!-- Dashboard Box / End -->
                                    <?php
                                    $table = new \CodeIgniter\View\Table();
                                    $template = array(
                                        'table_open' => '<table id="datatable_date" class="basic-table margin-top-20">',
                                        'tbody_open'  => '<tbody>',
                                        'tbody_close' => '</tbody>',
                                    );

                                    $table->setTemplate($template);
                                    // $table->setHeading('Date', 'Event', 'Distance', 'Time');
                                    foreach ($result_list[$key] as $result) {
                                        $url = base_url("result/view/" . $result['result_id']);
                                        $row = [
                                            "<a href='$url'>" . fdateShort($result['edition_date']) . "</a>",
                                            "<a href='$url'>" . $result['edition_name'] . "</a>",
                                            "<a href='$url'><mark class='color' style='background-color:" . race_color(intval($result['race_distance'])) . "'>" . round($result['race_distance'], 0) . "km</span></a>",
                                            "<a href='$url'>" . $result['result_time'] . "</a>",
                                        ];
                                        $table->addRow($row);
                                    }
                                    echo $table->generate();
                                    ?>
                                </div>
                            </div>

                    <?php
                        }
                    }
                    ?>
                </div>
            </div>

            <?= $user_footer; ?>

        </div>
    </div>
    <!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->