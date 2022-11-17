<?php
echo "<p>There are " . $all_editions_count . " editions in the database<p>";
echo "<p>The last 5 updated were:</p><ul>";

foreach ($last_updated as $edition) {
    echo "<li>".$edition['edition_name']."</li>";
}

echo "</ul>";
