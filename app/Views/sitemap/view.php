<h2>Sitemap</h2>
<h3>Static Pages</h3>
<ul>
<?php
   // main menu
   foreach ($menus['static_pages'] as $menu_item) {
       echo "<li><a href='" . $menu_item['loc'] . "'>" . $menu_item['display'] . "</a>";
       if (isset($menu_item['sub-menu'])) {
           echo "<ul>";
           foreach ($menu_item['sub-menu'] as $sub_menu_item) {
               echo "<li><a href='" . $sub_menu_item['loc'] . "'>" . $sub_menu_item['display'] . "</a>";
           }
           echo "</ul>";
       }
       echo "</li>";
   }
   ?>
</ul>

<h3>Provinces</h3>
<h3>Regions</h3>

<h3>Calendar</h3>
<?php
foreach ($edition_list as $year => $month_list) {
    d($month_list);
}
?>

<h3>Full Race List</h3>
<?php
foreach ($edition_list as $year => $month_list) {
    echo "<h3>" . $year . "</h3>";
    foreach ($month_list as $month_num => $race_list) {
        $dateObj   = DateTime::createFromFormat('!m', $month_num);
        $month_name = $dateObj->format('F'); // March
        echo "<h4>" . $month_name . "</h4>";
        echo "<ul>";
        foreach ($race_list as $edition_id => $edition) {
            echo "<li><a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . fDateHumanShort($edition['edition_date']) . " - " . $edition['edition_name'] . "</a></li>";
        }
        echo "</ul>";
    }
}
dd($edition_list);
