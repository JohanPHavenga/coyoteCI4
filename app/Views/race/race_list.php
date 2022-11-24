<?php
    if (!isset($edition_list)) {
        echo "<p>No races found</p>";
    } elseif (empty($edition_list)) {
        echo "<p>No races to show</p>";
    }
?>
<ul>
    <?php
    foreach ($edition_list as $edition_id => $edition) {
        echo "<li><a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . fDateHumanShort($edition['edition_date']) . " - " . $edition['edition_name'] . "</a></li>";
    }
    ?>
</ul>

<?php
    d($edition_list);