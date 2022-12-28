<div class="container">
    <div class="row">

        <!-- Content -->
        <div class="col-xl-8 col-lg-8 content-right-offset">

            <?php
            // notice banner for canclled and postponed races
            if (isset($notice_banner)) {
                echo '<p class="margin-top-20"><mark class="color"> <i class="icon-material-outline-notifications-active"></i> TAKE NOTE </mark></p>';
                echo '<div class="notification ' . $notice_banner['state'] . '">' . $notice_banner['msg'] . '</div>';
            }

            if ($show_races) {
            ?>
                <div class="single-page-section">
                    <?= view('event/detail/races'); ?>
                    <?= view('templates/horizontal_ad.php'); ?>
                </div>
            <?php
            }
            ?>

            <?php
            if ($show_info) {
            ?>
                <!-- INTRO -->
                <?= view('event/detail/intro'); ?>

                <!-- ENTRY -->
                <div class="single-page-section">
                    <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                        <h4>Entry Detail</h4>
                    </div>
                    <?= view('event/detail/entries'); ?>
                </div>

                <!-- REGISTRATION -->
                <?php
                if ((!in_array(3, $edition_data['regtype_list'])) || (detail_field_strlen($edition_data['edition_reg_detail']))) {
                ?>
                    <div class="single-page-section">
                        <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                            <h4>Registration / Number Collection</h4>
                        </div>
                        <?= view('event/detail/registration'); ?>
                    </div>
                <?php
                }
                ?>

                <!-- RACE DAY INFO -->
                <div class="single-page-section">
                    <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                        <h4>Race Day Information</h4>
                    </div>
                    <?= view('event/detail/race-day-info'); ?>
                    <?= view('templates/horizontal_ad.php'); ?>
                </div>

                <!-- MAP -->
                <div class="single-page-section">
                    <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                        <h4>Location Map</h4>
                    </div>
                    <?= view('event/detail/map'); ?>
                </div>

                <!-- ROUTE MAPS -->
                <div class="single-page-section">
                    <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                        <h4>Route Maps</h4>
                    </div>
                    <?= view('event/detail/route-maps'); ?>
                </div>
            <?php
            }
            // show info end
            ?>

            <!-- DOCUMENTS -->
            <?php
            if (!empty($file_list)) {
                if (sizeof($file_list) > 1) {
            ?>
                    <div class="single-page-section">
                        <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                            <h4>Documents</h4>
                        </div>

                        <?= view('event/detail/documents'); ?>
                    </div>
            <?php
                }
            }
            ?>
            <!-- CONTACT -->
            <div class="single-page-section">
                <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                    <h4>Contact Race Organisers</h4>
                </div>
                <?= view('event/detail/contact'); ?>
            </div>
        </div>


        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">
                <?php
                if ($show_summary) {
                ?>
                    <?= view('event/side/summary'); ?>
                <?php
                }
                ?>
                <?= view('templates/vertical_ad.php');?>
                <?= view('event/side/bookmark'); ?>
                <?= view('event/side/tags'); ?>
            </div>
        </div>

    </div>
</div>