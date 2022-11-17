<?php
echo "<p>There are " . $all_editions_count . " editions in the database<p>";

echo "<p>Featured races:</p><ul>";

foreach ($featured as $edition) {
    echo "<li><a href='".base_url('event/'.$edition['edition_slug'])."'>".$edition['edition_name']."</a></li>";
}

echo "</ul>";

echo "<p>The last 5 updated were:</p><ul>";

foreach ($last_updated as $edition) {
    echo "<li><a href='".base_url('event/'.$edition['edition_slug'])."'>".$edition['edition_name']."</a></li>";
}

echo "</ul>";
