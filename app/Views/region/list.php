<div class="container">
    <div class="row">

        <div class="col-xl-9 col-lg-8 content-right-offset">
            <ul class="list-1 color">
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
            </ul>
        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="sidebar-container margin-top-15">


            </div>
        </div>

    </div>
</div>