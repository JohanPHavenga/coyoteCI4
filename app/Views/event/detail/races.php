<?php
// dd($race_list);
?>
<div class="single-page-section">
    <!-- Listings Container -->
    <div class="listings-container grid-layout">
        <?php
        $race_list = array_values($race_list);
        foreach ($race_list as $key => $race) {
            $dialog_name = "#small-dialog-" . $key + 1;
        ?>
            <!-- race Listing -->
            <a href="<?= $dialog_name; ?>" class="job-listing popup-with-zoom-anim">

                <!-- Job Listing Details -->
                <div class="job-listing-details">
                    <!-- Logo -->
                    <div class="job-listing-company-logo">
                        <mark class='box color' style='background-color: <?= race_color($race['race_distance']); ?>'><span><?= fraceDistance($race['race_distance'], true); ?></span></mark>
                    </div>

                    <!-- Details -->
                    <div class="job-listing-description">
                        <h4 class="job-listing-company"><?= $race['racetype_name']; ?></h4>
                        <h3 class="job-listing-title"><?= $race['race_name']; ?></h3>
                    </div>
                </div>

                <!-- Job Listing Footer -->
                <div class="job-listing-footer">
                    <ul>
                        <?php
                        if (!$race['race_date']) {
                            $race['race_date'] = $edition_data['edition_date'];
                        }
                        ?>
                        <li><i class="icon-material-outline-date-range"></i> <?= fdateHumanShort($race['race_date']); ?></li>
                        <li><i class="icon-material-outline-access-time"></i> <?= ftimeMil($race['race_time_start']); ?></li>
                        <li><i class="icon-material-outline-local-atm"></i>
                            <?php
                            if (intval($race['race_fee_flat']) > 0) {
                                echo (fdisplayCurrency($race['race_fee_flat']));
                            } else {
                                if (intval($race['race_fee_senior_licenced']) > 0) {
                                    echo (fdisplayCurrency($race['race_fee_senior_licenced']));
                                } else {
                                    echo "TBC";
                                }
                            }
                            ?>
                        </li>
                        <li class='show_on_small_only'>
                            <mark class='color' style='background-color: <?= race_color($race['race_distance']); ?>'><?= fraceDistance($race['race_distance'], true); ?></mark>
                        </li>
                    </ul>
                </div>
            </a>
        <?php
        }
        ?>
    </div>
</div>


<?php
foreach ($race_list as $key => $race) {
    $dialog_name = "small-dialog-" . $key + 1;
?>
    <!-- Race Pop-up
================================================== -->
    <div id="<?= $dialog_name; ?>" class="zoom-anim-dialog mfp-hide dialog-with-tabs">
        <!--Tabs -->
        <div class="sign-in-form">
            <ul class="popup-tabs-nav">
                <li><a href=""><?= $race['race_name']; ?></a></li>
            </ul>
            <div class="popup-tabs-container">
                <div class="popup-tab-content" id="<?= $dialog_name; ?>">
                    <!-- Welcome Text -->
                    <div class="welcome-text">
                        <h3><?= $race['race_name']; ?></h3>
                    </div>
                    <table class="basic-table">
                        <tbody>
                            <tr>
                                <td style='width: 50%;'>Date</td>
                                <td colspan="2">
                                    <?php
                                    if (!isset($race['race_date'])) { $race['race_date']=$edition_data['edition_date']; }
                                    if (strtotime($race['race_date']) != strtotime($edition_data['edition_date'])) {
                                        echo "<mark class='color'>" . fdateHuman($race['race_date']) . "</mark>";
                                    } else {
                                        echo fdateHuman($race['race_date']);
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Time</td>
                                <td colspan="2">Start: <b><?= ftimeMil($race['race_time_start']); ?></b>
                                    <?php
                                    if ($race['race_time_end'] != "00:00:00") {
                                    ?>
                                        <br>Cut-off: <b><?= ftimeMil($race['race_time_end']); ?></b>
                            </tr>
                            </td>
                            </tr>
                        <?php
                                    }
                        ?>
                        <tr>
                            <td>Distance</td>
                            <td colspan="2"><mark class="color" style="background-color: <?= race_color($race['race_distance']); ?>"><?= fraceDistance($race['race_distance']); ?></mark></td>
                        </tr>
                        <tr>
                            <td>Race Type</td>
                            <td colspan="2"><?= $race['racetype_name']; ?></td>
                        </tr>
                        <?php
                        if ($race['race_minimum_age'] > 0) {
                        ?>
                            <tr>
                                <td>Minimum age:</td>
                                <td colspan="2"><?= $race['race_minimum_age']; ?> years</td>
                            </tr>
                        <?php
                        }
                        if ($race['race_entry_limit'] > 0) {
                        ?>
                            <tr>
                                <td>Entry limit for the race:</td>
                                <td colspan="2"><?= $race['race_entry_limit']; ?></td>
                            </tr>
                        <?php
                        }

                        if ($race['race_fee_flat'] > 0) {
                        ?>
                            <tr>
                                <td>Race fee:</td>
                                <td>R<?= floatval($race['race_fee_flat']); ?></td>
                            </tr>
                        <?php
                        } elseif ($race['race_fee_senior_licenced'] > 0) {
                            $info_text = "Races that are ran under the rules and regulations of the ASA (10km+) requires you to have a running license. You can either buy a temporarily license for the race you want to enter, or join a running club and purchase a permanent license number for the year. If you run more that 3x 10km+ races a year, it starts making financial sense to get a permanent number.";
                        ?>
                            <tr>
                                <td></td>
                                <td style="font-size: 0.8em;"><a title="<?= $info_text; ?>">Licensed</a></td>
                                <td style="font-size: 0.8em;"><a title="<?= $info_text; ?>">Unlicensed</a></td>
                            </tr>
                            <tr>
                                <td>Entry fees:</td>
                                <td>R<?= floatval($race['race_fee_senior_licenced']); ?></td>
                                <td>R<?= floatval($race['race_fee_senior_unlicenced']); ?></td>
                            </tr>
                            <?php
                            if ($race['race_fee_junior_licenced'] > 0) {
                            ?>
                                <tr>
                                    <td>Junior (<20) Entry Fees:</td>
                                    <td>R<?= floatval($race['race_fee_junior_licenced']); ?></td>
                                    <td>R<?= floatval($race['race_fee_junior_unlicenced']); ?></td>
                                </tr>
                            <?php
                            }
                        }
                        if ($race['race_isover70free']) {
                            $fee70 = $race['race_fee_senior_unlicenced'] - $race['race_fee_senior_licenced'];
                            ?>
                            <tr>
                                <td>Great Grandmasters (70+):</td>
                                <td>Free</td>
                                <td>R<?= $fee70; ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    <ul>
                        <?php
                        // LIMIT
                        if (!empty($race['race_address'])) {
                            echo "<li><mark style='color'><strong>NOTE</strong></mark> that the <strong>starting address</strong> for this race differs from the end address:<br><strong>" . $race['race_address'] . "</strong></li>";
                        }
                        ?>
                    </ul>
                    <?= $race['race_notes']; ?>
                    <?php
                    if (!$in_past) {
                    ?>
                        <div class="row margin-top-35">
                            <div class="col-xl-6 col-lg-6">
                                <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/entries"); ?>" class="button full-width ripple-effect gray">
                                    <i class="icon-feather-edit"></i>&nbsp;Entry Details</a>
                            </div>
                            <div class="col-xl-6 col-lg-6">
                                <a href="<?= base_url("training-programs/" . url_title($race['race_name'])); ?>" class="button full-width ripple-effect gray">
                                    <i class="icon-feather-book-open"></i> Training Programs</a>
                            </div>
                        </div>
                    <?php
                    }
                    if (
                        isset($edition_data['entrytype_list'][4]) && // entrytype for online entries
                        isset($url_list[5]) && // there is a url loaded for online entries
                        isset($date_list[3][0]['date_start']) && // online entries open date exists
                        (strtotime($date_list[3][0]['date_start']) < time()) &&    // online entries date has passed
                        (strtotime($date_list[3][0]['date_end']) > time())
                    ) {
                    ?>
                        <!-- Button -->
                        <a class="button margin-top-35 full-width button-sliding-icon ripple-effect" href="<?= $url_list[5][0]['url_name']; ?>">
                            Enter Now <i class="icon-material-outline-arrow-right-alt"></i></a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<!-- Race popup / End -->