<!-- Dashboard Container -->
<div class="dashboard-container">

    <?= $user_sidebar; ?>

    <!-- Dashboard Content
	================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Howdy, <?= user()->name; ?>!</h3>
                <span>We are glad to see you again</span>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?=base_url();?>">Home</a></li>
                        <li>Dashboard</li>
                    </ul>
                </nav>
            </div>

            <!-- Fun Facts Container -->
            <div class="fun-facts-container">
                <div class="fun-fact" data-fun-fact-color="#36bd78">
                    <div class="fun-fact-text">
                        <span>Linked Results</span>
                        <h4><?= $result_num; ?></h4>
                    </div>
                    <div class="fun-fact-icon"><i class="icon-feather-watch"></i></div>
                </div>
                <div class="fun-fact" data-fun-fact-color="#b81b7f">
                    <div class="fun-fact-text">
                        <span>Subscriptions</span>
                        <h4><?=$sub_num;?></h4>
                    </div>
                    <div class="fun-fact-icon"><i class="icon-feather-mail"></i></div>
                </div>
            </div>

            <!-- Row -->
            <div class="row">

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
            <!-- Row / End -->

            <?= $user_footer; ?>

        </div>
    </div>
    <!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->