<div class="listings-container compact-list-layout margin-top-35">
    <?php
    if (empty($edition_list)) {
    ?>
        <div class="notification error closeable">
            <b>No races found. Please try again.</b>
            <a class="close"></a>
        </div>
        <?php
    } else {
        foreach ($edition_list as $edition_id => $edition) {
            if ($edition['edition_status'] == 1) {
                $key = $edition['edition_info_status'];
            } else {
                $key = $edition['edition_status'];
            }
            $status_msg['state'] = $status_notice_list[$key]['state'];
            $status_msg['short_msg'] = $status_notice_list[$key]['short_msg'];
            $status_msg['msg'] = $status_notice_list[$key]['msg'];
        ?>
            <!-- Job Listing -->
            <a href="<?= base_url('event/' . $edition['edition_slug']); ?>" class="job-listing">

                <!-- Job Listing Details -->
                <div class="job-listing-details">

                    <!-- Logo -->
                    <div class="job-listing-company-logo">
                        <img src="<?= $edition['thumb_url']; ?>" alt="">
                    </div>

                    <!-- Details -->
                    <div class="job-listing-description">
                        <h3 class="job-listing-title"><?= $edition['edition_name']; ?>
                            <?php
                            if ($status_msg['state'] == "verified") {
                            ?>
                                <div class="verified-badge" title="<?= $status_msg['short_msg']; ?>" data-tippy-placement="top"></div>
                            <?php
                            }
                            ?>
                        </h3>

                        <!-- Job Listing Footer -->
                        <div class="job-listing-footer">
                            <ul>
                                <li><i class="icon-material-outline-date-range"></i> <strong><?= fDateHumanShort($edition['edition_date']); ?></strong></li>
                                <li><i class="icon-material-outline-location-on"></i> <?= $edition['town_name']; ?></li>
                                <li>
                                    <?php
                                    foreach ($edition['races'] as $race) {
                                    ?>
                                        <mark class='color' style='background-color: <?= race_color($race['race_distance']); ?>'><span><?= fraceDistance($race['race_distance'], true); ?></span></mark>
                                    <?php
                                    }
                                    ?>
                                </li>
                                <li title="<?= $status_msg['msg']; ?>" data-tippy-placement="top"><i class="icon-material-outline-info"></i> <?= ucfirst($status_msg['short_msg']); ?></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Bookmark -->
                    <!-- <span class="bookmark-icon"></span> -->
                </div>
            </a>
    <?php
        }
    }
    ?>
</div>