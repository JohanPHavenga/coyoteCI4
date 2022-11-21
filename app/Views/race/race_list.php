<?php
if (isset($year)) {
    echo "Year: " . $year;
}
if (isset($month)) {
    echo "<br>Month: " . $month;
}
if (isset($day)) {
    echo "<br>Day: " . $day;
}
?>

<ul>
    <?php
    foreach ($edition_list as $edition_id => $edition) {
        echo "<li><a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . fDateHumanShort($edition['edition_date']) . " - " . $edition['edition_name'] . "</a></li>";
    }
    ?>
</ul>