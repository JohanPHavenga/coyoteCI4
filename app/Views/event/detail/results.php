<!-- Content-->
<?php
// get results URL if exist
if (isset($url_list[4][0])) {
    $url = $url_list[4][0]['url_name'];
} else {
    $url = $edition_data['timingprovider_url'];
}


// TIMING PROVIDER WIDGET
if ($edition_data['timingprovider_id'] > 1) {
?>
    <div class="contact-location-info margin-bottom-50 maring-right-5">
        <div class="contact-address">
            <?php
            if ($edition_data['club_id'] != 8) {
                $heading = $edition_data['club_name'];
            } else {
                $heading = "Contact Info";
            }
            ?>
            <ul>
                <li class="contact-address-headline" style='font-size: 1.3em;'>
                    <a href="<?= $url; ?>" title="View results on <?= $edition_data['timingprovider_name']; ?> site"><?= $edition_data['timingprovider_name']; ?></a>
                </li>
                <li>Offical timekeeping for this race by <?= $edition_data['timingprovider_name']; ?></li>
            </ul>
        </div>
        <div class="timingptrovider_img">
            <a href="<?= $url; ?>" title="View results on <?= $edition_data['timingprovider_name']; ?> site">
                <img src="<?= base_url('assets/images/timingproviders/' . $edition_data['timingprovider_img']); ?>" alt="<?= $edition_data['timingprovider_name']; ?> Logo" style="width: 80%;" />
            </a>
        </div>
    </div>
<?php
}


$mailing_list_notice = "<p>If you would like to be <b>notified once results are loaded</b>, "
    . "please enter your email below or to the right to be added to the "
    . "<a href='" . base_url('event/' . $slug . '/subscribe') . "' title='Add yourself to the mailing list'>mailing list</a> for this race.</p>";
// if date in future
if (!$in_past) {
    $days_from_now = convert_seconds(strtotime($edition_data['edition_date']) - time()) + 1;
?>
    <div class="single-page-section">
        <p><b><mark class="color">No results available yet</mark></b></p>
        <p>
            This race is scheduled to take place on
            <b><?= fdateHumanFull($edition_data['edition_date']); ?></b>
            <?php
            if ($days_from_now > 0) {
                echo ", <mark>" . $days_from_now . " day";
                if ($days_from_now > 1) {
                    echo "s";
                }
                echo "</mark> from now.";
            }
            ?>
        </p>
        <p>Once the race has run, depending on the timing method used, results can take <u>up to 7 working days</u> to be released.
            As soon as results are published by the organisers I will load it here.</p>
        <?= $mailing_list_notice; ?>
    </div>
    <?php
} else {
    $days_ago = convert_seconds(time() - strtotime($edition_data['edition_date']));
    if ($days_ago > 1) {
        $d = "days";
    } else {
        $d = "day";
    }

    switch ($edition_data['edition_info_status']) {
        case 10:
            // pending
    ?>
            <div class="single-page-section">
                <div class="notification warning closeable">
                    <b>Results are pending</b>
                    <a class="close"></a>
                </div>
                <p><b>Race was ran <mark><?= $days_ago; ?> <?= $d; ?></mark> ago.</b></p>
                <p><b>Please note:</b> Results can take <u>up to 7 working days</u> to be released.
                    As soon as results are published by the organisers I will load it here.</p>
                <?= $mailing_list_notice; ?>
            </div>
            <?php
            break;

        case 11;
            // loaded
            // add time provider info

            // STILL TO BE DONE
            /// --------------------------------------------------------------------
            if (isset($results['race'])) {
            ?>
                <div class="single-page-section">
                    <?php
                    if (isset($result_list)) {
                    ?>
                        <div class="row pricing-table colored">
                            <?php
                            foreach ($results['race'] as $racetype_abbr => $racetype_list) {

                                foreach ($racetype_list as $dist => $race_result) {
                                    //                                                wts($race_result);
                                    $url = base_url("event/" . $slug . "/results/" . $dist . "/" . $racetype_abbr);
                            ?>
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">
                                        <div class="plan">
                                            <div class="plan-header">
                                                <h4><?= str_replace("Results", "", $race_result['text']); ?></h4>
                                                <p class="text-muted">Results</p>
                                                <div class="plan-price"><sup></sup><?= $race_result['distance']; ?><span>km</span> </div>
                                                <a class="btn btn-light btn-light btn-light-hover" href="<?= $url; ?>"><span><i class="fa fa-list-alt"></i> View</span></a>
                                            </div>

                                        </div>
                                    </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    <?php
                    } else {
                        // old school race file load without data in the db
                        //                      wts($results);
                    ?>
                        <div class="notification success closeable">
                            <p><b>RESULTS LOADED</b></p>
                            <a class="close"></a>
                        </div>

                        <div class="row">
                            <div class="col-md-12 attachments-container">
                                <?php
                                foreach ($results['race'] as $race_type) {
                                    foreach ($race_type as $race_result) {
                                        echo "<a href='" . $race_result['url'] . "' class='attachment-box ripple-effect'><span>" . $race_result['text'] . "</span><i>" . $race_result['ext'] . "</i></a>";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            <?php
                //                            wts($results['race']);
                //                            wts($result_list);
            } elseif (isset($results['edition'])) {
                // old school edition file loaded results
            ?>
                <div class="single-page-section">
                    <div class="notification success closeable">
                        <p><b>RESULTS LOADED</b></p>
                        <a class="close"></a>
                    </div>
                    <div class="row">
                        <div class="col-md-12 attachments-container">
                            <?php
                            foreach ($results['edition'] as $edition_result) {
                                echo "<a href='" . $edition_result['url'] . "' class='attachment-box ripple-effect'><span>" . $edition_result['text'] . "</span><i>" . $edition_result['ext'] . "</i></a>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
            }

            break;
        case 12:
            // no results expected
            ?>
            <div class="single-page-section">
                <div class="notification warning closeable">
                    <b>No results expected for this event</b>
                    <a class="close"></a>
                </div>
                <p><a class="button ripple-effect" href="<?= base_url('event/' . $slug . '/contact'); ?>"><i class="icon-material-baseline-mail-outline"></i>
                        Contact race organisers</a></p>
            </div>
        <?php
            break;
        default:
        ?>
            <div class="single-page-section">
                <div class="notification error closeable">
                    <b>No results available for this event</b>
                    <a class="close"></a>
                </div>
                <p><a class="button ripple-effect" href="<?= base_url('event/' . $slug . '/contact'); ?>"><i class="icon-material-baseline-mail-outline"></i>
                        Contact race organisers</a></p>
            </div>
<?php
            break;
    }
}
?>