<!-- Dashboard Container -->
<div class="dashboard-container">

    <?= $user_sidebar; ?>

    <!-- Dashboard Content
	================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>View Result</h3>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url("user/dashboard"); ?>">Dashboard</a></li>
                        <li><a href="<?= base_url("user/results"); ?>">Results</a></li>
                        <li>View</li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">

                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-account-circle"></i> <?= $result_detail['edition_name']; ?></h3>
                            <h4> <?= $result_detail['race_name']; ?></h3>
                                <a href="<?= base_url("result/remove/" . $result_detail['result_id']); ?>" class="message-action"><i class="icon-feather-trash-2"></i> Remove Result</a>
                        </div>

                        <div class="content with-padding padding-bottom-0">
                            <div class="row">
                                <?php
                                if (!$result_detail['official_result']) {
                                ?>
                                    <div class="col-xl-12 col-md-12">
                                        <div class="notification notice">
                                            <b>NOTE</b> - This is a manually loaded result
                                        </div>
                                    </div>
                                <?php
                                }

                                $table = new \CodeIgniter\View\Table();
                                $template = array(
                                    'table_open' => '<table id="datatable_date" class="basic-table margin-bottom-20">',
                                    'tbody_open'  => '<tbody>',
                                    'tbody_close' => '</tbody>',
                                );

                                $table->setTemplate($template);
                                $table->addRow(["Position", $result_detail['result_pos']]);
                                $table->addRow(["Time", $result_detail['result_time']]);
                                $table->addRow(["Name", $result_detail['result_name']]);
                                $table->addRow(["Surame", $result_detail['result_surname']]);
                                if ($result_detail['result_club']) {
                                    $table->addRow(["Club", $result_detail['result_club']]);
                                }
                                if ($result_detail['result_age']) {
                                    $table->addRow(["Age", $result_detail['result_age']]);
                                }
                                if ($result_detail['result_sex']) {
                                    $table->addRow(["Gender", $result_detail['result_sex']]);
                                }
                                if ($result_detail['result_cat']) {
                                    $table->addRow(["Category", $result_detail['result_cat']]);
                                }
                                if ($result_detail['result_asanum']) {
                                    $table->addRow(["ASA Number", $result_detail['result_asanum']]);
                                }
                                if ($result_detail['result_racenum']) {
                                    $table->addRow(["Race Number", $result_detail['result_racenum']]);
                                }
                                echo $table->generate();
                                ?>
                            </div>
                        </div>
                        <!-- end Content Box -->

                    </div>
                    <!-- end Dahshboard Box-->
                </div>

                <div class="col-xl-12 margin-top-30">
                    <a href="<?= base_url("user/results"); ?>" class="button gray ripple-effect"><i class="icon-material-outline-arrow-back"></i> Back</a>
                    <?php
                    if ($result_detail['official_result']) {
                    ?>
                        <a href="<?= base_url("file/race/" . $result_detail['edition_slug'] . "/results/" . url_title($result_detail['race_name']) . "/" . $result_detail['file_name']); ?>" class="button margin-left-10">
                            <i class="icon-line-awesome-file-excel-o"></i> Download Result File</a>
                    <?php
                    }
                    ?>
                </div>

            </div>
            <!-- Row / End -->

            <?= $user_footer; ?>

        </div>
    </div>
    <!-- Dashboard Content / End -->