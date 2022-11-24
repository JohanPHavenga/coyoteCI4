<h1>Region List</h1>
<?php
foreach ($region_list as $province_abbr => $province_info) {
    echo "<li><a href='" . base_url('province/' . $province_info['province']['province_slug']) . "'>" . $province_info['province']['province_name'] . "</a></li>";
    echo "<ul>";
    foreach ($province_info['region_list'] as $region_id => $region) {
        echo "<li><a href='" . base_url('region/' . $region['region_slug']) . "'>" . $region['region_name'] . "</a></li>";
    }
    echo "</ul>";
}
?>