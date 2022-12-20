<!-- Dashboard Container -->
<div class="dashboard-container">

    <?= $user_sidebar; ?>

    <!-- Dashboard Content
	================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Claim a Result</h3>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url("user/dashboard"); ?>">Dashboard</a></li>
                        <li><a href="<?= base_url("user/results"); ?>">Results</a></li>
                        <li>Claim</li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">

                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-account-circle"></i> <?= $result_list[0]['edition_name']; ?></h3>
                            <h4> <?= $result_list[0]['race_name']; ?></h3>
                        </div>

                        <div class="content with-padding padding-bottom-0">
                            <div class="row">
                                <?php
                                $table = new \CodeIgniter\View\Table();
                                $template = array(
                                    'table_open' => '<table id="datatable_date" class="basic-table margin-bottom-20">',
                                    'tbody_open'  => '<tbody>',
                                    'tbody_close' => '</tbody>',
                                );

                                $table->setTemplate($template);
                                $table->setHeading('POS', 'Name', 'Club', 'Cat', 'Time', 'Yours?');
                                foreach ($result_list as $result) {
                                    $link = base_url('result/claim/result/' . $result['result_id']);
                                    $row = [
                                        $result['result_pos'],
                                        $result['result_name'] . " " . $result['result_surname'],
                                        $result['result_club'],
                                        $result['result_cat'],
                                        $result['result_time'],
                                        "<a href='$link'>CLAIM</a></td>",
                                    ];
                                    $table->addRow($row);
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
                    <div class="notification warning closeable">
                        <p><b>Can't find yourself?</b> Above is a summary view of entries matching your name or surname.<br> Click below to view full result set.</p>
                        <a class="close"></a>
                    </div>
                    <a href="<?= base_url("result/claim/list/".$race_id."/full"); ?>" class="button ripple-effect big margin-top-10">Show Full Result List</a>
                </div>
                <div class="col-xl-12">
                    <a href="<?= base_url("user/results"); ?>" class="button gray ripple-effect big margin-top-30"><i class="icon-material-outline-arrow-back"></i> Back</a>
                </div>

            </div>
            <!-- Row / End -->

            <?= $user_footer; ?>

        </div>
    </div>
    <!-- Dashboard Content / End -->