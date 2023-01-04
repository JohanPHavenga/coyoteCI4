<?php
if (
    (detail_field_strlen($edition_data['edition_general_detail'])) ||
    ($edition_data['edition_info_medals']) ||
    ($edition_data['edition_info_togbag']) ||
    ($edition_data['edition_info_headphones']) ||
    ($edition_data['edition_info_prizegizing'] != "00:00:00")
) {
?>
    <ul>
        <?php
        // MEDALS
        if ($edition_data['edition_info_medals']) {
            echo "<li><b>MEDALS:</b> ";
            if (!empty($edition_data['edition_info_medals_text'])) {
                echo $edition_data['edition_info_medals_text'];
            } else {
                echo "Medals will be awarded to all finishers within cut-off times";
            }
            echo "</li>";
        } elseif (!empty($edition_data['edition_info_medals_text'])) {
            echo "<li><b>HANDOUTS:</b> " . $edition_data['edition_info_medals_text'];
        }

        // PRIZE-GIVING
        if (!is_null($edition_data['edition_info_prizegizing'])) {
            if ($edition_data['edition_info_prizegizing'] != "00:00:00") {
                echo "<li><b>PRIZE-GIVING:</b> Scheduled to start at " . ftimeMil($edition_data['edition_info_prizegizing']) . "</li>";
            }
        }

        // LUCKY DRAWS
        if ($edition_data['edition_info_luckydraw']) {
            echo "<li><b>LUCKY DRAWS:</b> Lucky draws will be held</li>";
        }

        // TOG BAG
        if ($edition_data['edition_info_togbag']) {
            echo "<li><b>TOG BAG:</b> Tog bag facilities will be available, used at own risk</li>";
        }

        // REFRESHMENTS
        if ($edition_data['edition_info_refreshments']) {
            echo "<li><b>REFRESHMENTS:</b> Refreshments will be on sale at the event</li>";
        }

        // SOCIAL WALKERS
        if ($edition_data['edition_info_socialwalkers']) {
            echo "<li><b>WALKERS:</b> Walkers are welcome</li>";
        }

        // HEADPHONES
        if ($edition_data['edition_info_headphones']) {
            echo "<li><b>MUSIC:</b> The use of music players with headphones or 'buds' is not allowed and may result in disqualification</li>";
        }
        ?>
    </ul>
<?php
    // GENERAL INFORMATION
    echo $edition_data['edition_general_detail'];
} else {
?>
    <div class="notification warning closeable">
        <p><b>No further details</b> regarding the race has been published yet</p>
        <a class="close"></a>
    </div>
    <p>Do you want to get notified once information is released? Add yourself to the <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/subscribe"); ?>">mailing list</a> for the event.</p>
<?php

}
?>