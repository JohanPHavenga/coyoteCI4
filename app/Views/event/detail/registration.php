<ul>
    <?php
    // OTD Reg
    if (isset($edition_data['regtype_list'][1])) {
        echo "<li>Registration will take place <b>on the day</b> from <b>" .
            ftimeMil($date_list[9][0]['date_start']);
        if (!time_is_midnight($date_list[9][0]['date_end'])) {
            echo " - " . ftimeMil($date_list[9][0]['date_end']);
        }
        echo "</b></li>";
    } else {
        echo "<li class='red em'>No number collection on race day</li>";
    }

    // PRE Reg
    if (isset($edition_data['regtype_list'][2])) {
        echo "<li><b>Registration / Number collection will take place on:</b><ul>";
        foreach ($date_list[10] as $date) {
            echo "<li>" . fdateHumanFull($date['date_start'], true, true) . "-" . ftimeMil($date['date_end']) . " @ " . $date['venue_name'] . "</li>";
        }
        echo "</ul></li>";
    }
    ?>
</ul>
<?php
// always show what is in the box
if (strlen($edition_data['edition_reg_detail']) > 10) {
    echo $edition_data['edition_reg_detail'];
}
?>