<div class="single-page-section">
    <!-- Listings Container -->
    <div class="listings-container grid-layout">
        <?php
        foreach ($race_list as $race) {
        ?>
            <!-- race Listing -->
            <a href="<?= base_url('event/' . $slug . '/distances/' . url_title($race['race_name'])); ?>" class="job-listing">

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