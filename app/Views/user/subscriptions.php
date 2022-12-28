<!-- Dashboard Container -->
<div class="dashboard-container">

    <?= $user_sidebar; ?>

    <!-- Dashboard Content
	================================================== -->
    <div class="dashboard-content-container" data-simplebar>
        <div class="dashboard-content-inner">

            <!-- Dashboard Headline -->
            <div class="dashboard-headline">
                <h3>Mailing Lists</h3>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <li><a href="<?= base_url(); ?>">Home</a></li>
                        <li><a href="<?= base_url("user/dashboard"); ?>">Dashboard</a></li>
                        <li>Mailing Lists</li>
                    </ul>
                </nav>
            </div>

            <!-- Row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">

                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-baseline-mail-outline"></i> Race Subscriptions</h3>
                        </div>

                        <div class="content">
                            <ul class="dashboard-box-list">
                                <?php
                                if ($edition_subs) {
                                    $show_count = 0;
                                    foreach ($edition_subs as $sub) {
                                        $unsub_link = base_url('unsubscribe/' . my_encrypt(user()->id . "|edition|" . $sub['edition_id']));
                                        if ((strtotime($sub['edition_date']) > time()) || ($show_all == "all")) {
                                            $show_count++;

                                ?>
                                            <li>
                                                <!-- Job Listing -->
                                                <div class="job-listing">

                                                    <!-- Job Listing Details -->
                                                    <div class="job-listing-details">

                                                        <!-- Logo -->
                                                        <a href="#" class="job-listing-company-logo">
                                                            <img src="<?= $sub['thumb_url']; ?>" alt="">
                                                        </a>

                                                        <!-- Details -->
                                                        <div class="job-listing-description">
                                                            <h3 class="job-listing-title"><a href="#"><?= $sub['edition_name']; ?></a></h3>

                                                            <!-- Job Listing Footer -->
                                                            <div class="job-listing-footer">
                                                                <ul>
                                                                    <li><i class="icon-material-outline-date-range"></i> <strong><?= fDateHumanShort($sub['edition_date']); ?></strong></li>
                                                                    <li><i class="icon-material-outline-location-on"></i> <?= $sub['town_name']; ?></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Buttons -->
                                                <div class="buttons-to-right single-right-button">
                                                    <a href="<?= $unsub_link; ?>" class="button red ripple-effect ico" data-tippy-placement="left" data-tippy="" data-original-title="Remove"><i class="icon-feather-trash-2"></i></a>
                                                </div>
                                            </li>
                                    <?php
                                        }
                                    }
                                } else {
                                    ?>
                                    <li>No race mailing list subscriptions found</li>
                                <?php
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-xl-12">
                    <?php
                    if ($edition_subs) {
                        if ($show_all == "all") {
                    ?>
                            <a href="<?= base_url('user/my-subscriptions'); ?>" class="button ripple-effect big margin-top-30">Show Upcoming Races Only</a>
                            <?php
                        } else {
                            if (count($edition_subs) > $show_count) {
                            ?>
                                <a href="<?= base_url('user/my-subscriptions/all'); ?>" class="button ripple-effect big margin-top-30">Show All</a>
                    <?php
                            }
                        }
                    }
                    ?>
                </div>

                <div class="col-xl-12">
                    <div class="dashboard-box margin-top-30">

                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-baseline-mail-outline"></i> Newsletter Subscription</h3>
                        </div>

                        <div class="content">
                            <ul class="dashboard-box-list">
                                <?php
                                if ($newsletter_subs) {
                                    $unsub_link = base_url('unsubscribe/' . my_encrypt(user()->id . "|newsletter|0"));
                                ?>
                                    <li>
                                        <!-- Job Listing -->
                                        <div class="job-listing">

                                            <!-- Job Listing Details -->
                                            <div class="job-listing-details">

                                                <!-- Logo -->
                                                <a href="#" class="job-listing-company-logo">
                                                    <img src="<?= base_url("assets/images/newsletter.png") ?>" alt="">
                                                </a>

                                                <!-- Details -->
                                                <div class="job-listing-description">
                                                    <h3 class="job-listing-title"><a href="#">RoadRunningZA Newsletter Subscription</a></h3>

                                                    <!-- Job Listing Footer -->
                                                    <div class="job-listing-footer">
                                                        Periodic newsletter send out by RoadRunning.co.za
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Buttons -->
                                        <div class="buttons-to-right single-right-button">
                                            <a href="<?= $unsub_link; ?>" class="button red ripple-effect ico" data-tippy-placement="left" data-tippy="" data-original-title="Remove"><i class="icon-feather-trash-2"></i></a>
                                        </div>
                                    </li>
                                <?php
                                } else {
                                ?>
                                    <li><a href="<?= base_url('newsletter'); ?>" class="button gray">Subscribe to our Newslletter</a></li>
                                <?php
                                }
                                ?>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <?= $user_footer; ?>

        </div>
    </div>
    <!-- Dashboard Content / End -->

</div>
<!-- Dashboard Container / End -->