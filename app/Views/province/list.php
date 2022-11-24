<ul>
<?php
foreach ($province_list as $province_id => $province) {
    echo "<li><a href='" . base_url('province/' . $province['province_slug']) . "'>" . $province['province_name'] . "</a></li>";    
}
?>
</ul>