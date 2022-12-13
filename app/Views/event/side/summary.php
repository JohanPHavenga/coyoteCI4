<?php
if (
    isset($edition_data['entrytype_list'][4]) && // entrytype for online entries
    isset($url_list[5]) && // there is a url loaded for online entries
    isset($date_list[3][0]['date_start']) && // online entries open date exists
    (strtotime($date_list[3][0]['date_start']) < time())    // online entries date has passed
) {
    // check if entries has closed
    if (strtotime($date_list[3][0]['date_end']) > time()) {
        echo "<a href='" . $url_list[5][0]['url_name'] . "' class='apply-now-button popup-with-zoom-anim'>Enter Now <i class='icon-material-outline-arrow-right-alt'></i></a>";
    }
}
?>
<!-- Sidebar Widget -->
<div class="sidebar-widget">
    <div class="job-overview">
        <div class="job-overview-headline">Event Summary</div>
        <div class="job-overview-inner">
            <ul>
                <li>
                    <i class="icon-material-outline-location-on"></i>
                    <span>Location</span>
                    <h5><?= $address; ?></h5>
                </li>
                <li>
                    <i class="icon-material-outline-access-time"></i>
                    <span>Start Time</span>
                    <h5><time datetime="<?= ftimeSort($edition_data['race_summary']['times']['start']); ?>">
                            <?= ftimeSort($edition_data['race_summary']['times']['start']); ?>
                        </time></h5>
                </li>
                <li>
                    <i class="icon-material-outline-local-atm"></i>
                    <span>Race Fees</span>
                    <h5><?= $race_fee_range; ?></h5>
                </li>
                <li>
                    <i class="icon-material-outline-access-time"></i>
                    <span>Last Updated</span>
                    <h5><?= $last_updated; ?></h5>
                </li>
            </ul>
        </div>
    </div>
</div>