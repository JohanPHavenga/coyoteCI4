<?php
echo "<h1>Subscriptions</h1>";
echo "<ul>";
foreach ($edition_subs as $sub) {
    $unsub_link = base_url('unsubscribe/' . my_encrypt(user()->id . "|edition|" . $sub['edition_id']));
    echo "<li>" . $sub['edition_name'] . " | <a href='$unsub_link'>Unsub</a></li>";
}
echo "</ul>";

if ($newsletter_subs) {
$unsub_link = base_url('unsubscribe/' . my_encrypt(user()->id . "|newsletter|0"));
echo "<p><a href='$unsub_link'>Unsubscribe from newsletter</a></p>";
}

d($edition_subs);
d($newsletter_subs);
