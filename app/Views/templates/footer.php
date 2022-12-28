<?php
// remove footer for dashboard

if (($controller != "\App\Controllers\User") || ($method == "newsletter")) {
?>

    <!-- Spacer -->
    <div class="margin-top-70"></div>
    <!-- Spacer / End-->

    <!-- Footer
================================================== -->
    <div id="footer">

        <!-- Footer Top Section -->
        <div class="footer-top-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">

                        <!-- Footer Rows Container -->
                        <div class="footer-rows-container">

                            <!-- Left Side -->
                            <div class="footer-rows-left">
                                <div class="footer-row">
                                    <div class="footer-row-inner footer-logo margin-top-5">
                                        <img src="<?= base_url('assets/images/roadrunning_logo_dark_80.svg'); ?>" alt="">
                                    </div>
                                </div>
                            </div>

                            <!-- Right Side -->
                            <div class="footer-rows-right">

                                <!-- Social Icons -->
                                <div class="footer-row">
                                    <div class="footer-row-inner">
                                        <ul class="footer-social-links">
                                            <li>
                                                <a href="https://www.facebook.com/roadrunningcoza" title="Facebook" data-tippy-placement="bottom" data-tippy-theme="light">
                                                    <i class="icon-brand-facebook-f"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://twitter.com/roadrunningcoza" title="Twitter" data-tippy-placement="bottom" data-tippy-theme="light">
                                                    <i class="icon-brand-twitter"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="https://www.linkedin.com/company/roadrunningza/" title="LinkedIn" data-tippy-placement="bottom" data-tippy-theme="light">
                                                    <i class="icon-brand-linkedin-in"></i>
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>

                                <!-- Language Switcher -->
                                <div class="footer-row">
                                    <div class="footer-row-inner">
                                        <?php
                                        // province switch form
                                        $attributes = ['class' => 'province_switch_footer', 'method' => 'post'];
                                        echo form_open(base_url('province/switch'), $attributes);

                                        $js = "class='selectpicker language-switcher' data-selected-text-format='count' data-size='5' onchange='this.form.submit()'";
                                        echo form_dropdown('province_switch', $province_options, $_SESSION['site_province'], $js);
                                        echo form_close();
                                        ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- Footer Rows Container / End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Top Section / End -->

        <!-- Footer Middle Section -->
        <div class="footer-middle-section">
            <div class="container">
                <div class="row">

                    <!-- Links -->
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        <div class="footer-links">
                            <h3>Races</h3>
                            <ul>
                                <?php
                                foreach ($menus['static_pages']['races']['sub-menu'] as $key => $page) {
                                    echo "<li><a href='$page[loc]'><span>$page[display]</span></a></li>";
                                }
                                foreach ($menus['static_pages']['results']['sub-menu'] as $key => $page) {
                                    echo "<li><a href='$page[loc]'><span>$page[display]</span></a></li>";
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Links -->
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        <div class="footer-links">
                            <h3>Helpful Links</h3>
                            <ul>
                                <li><a href="<?= $menus['static_pages']['resources']['sub-menu']['training']['loc']; ?>"><span><?= $menus['static_pages']['resources']['sub-menu']['training']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['resources']['sub-menu']['faq']['loc']; ?>"><span><?= $menus['static_pages']['resources']['sub-menu']['faq']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['contact']['sub-menu']['about']['loc']; ?>"><span><?= $menus['static_pages']['contact']['sub-menu']['about']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['contact']['loc']; ?>"><span><?= $menus['static_pages']['contact']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['contact']['sub-menu']['support']['loc']; ?>"><span><?= $menus['static_pages']['contact']['sub-menu']['support']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['sitemap']['loc']; ?>"><span><?= $menus['static_pages']['sitemap']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['terms']['loc']; ?>"><span><?= $menus['static_pages']['terms']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['disclaimer']['loc']; ?>"><span><?= $menus['static_pages']['disclaimer']['display']; ?></span></a></li>
                                <li><a href="<?= $menus['static_pages']['privacy']['loc']; ?>"><span><?= $menus['static_pages']['privacy']['display']; ?></span></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Links -->
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        <div class="footer-links">
                            <h3>Regions</h3>
                            <ul>
                                <?php
                                foreach ($menus['static_pages']['featured-regions']['sub-menu'] as $key => $page) {
                                    echo "<li><a href='$page[loc]'><span>$page[display]</span></a></li>";
                                }
                                ?>
                                <li><a href='<?= $menus['static_pages']['featured-regions']['loc']; ?>'><span><?= $menus['static_pages']['featured-regions']['display']; ?></span></a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Links -->
                    <div class="col-xl-2 col-lg-2 col-md-3">
                        <div class="footer-links">
                            <h3>Calendar</h3>
                            <ul>
                                <?php
                                foreach (get_date_list() as $year => $month_list) {
                                    foreach ($month_list as $month_number => $month_name) {
                                        echo "<li><a href='" . base_url("calendar/" . $year . "/" . $month_number) . "'><span>" . $month_name . "</span></a></li>";
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>

                    <!-- Search -->
                    <div class="col-xl-4 col-lg-4 col-md-12">
                        <h3><i class="icon-feather-search"></i> Race Search</h3>
                        <p>Use the form below to search for a race name or town in which the race is taking place.</p>
                        <?php
                        // search form
                        $attributes = ['class' => 'search, newsletter', 'id' => 'footer_search', 'method' => 'get'];
                        echo form_open(base_url('search'), $attributes);
                        $data = [
                            'name'  => 's',
                            'id'    => 'search_field',
                            'value' => $search_string,
                            'placeholder' => 'Search',
                        ];
                        echo form_input($data);
                        $data = [
                            'type'    => 'submit',
                            'content' => '<i class="icon-feather-arrow-right"></i>',
                        ];
                        echo form_button($data);;
                        echo form_close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Middle Section / End -->

        <!-- Footer Copyrights -->
        <div class="footer-bottom-section">
            <div class="container">
                <div class="row">
                    <div class="col-xl-12">
                        © <?= date("Y"); ?> <strong>RoadRunningZA</strong>. All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer Copyrights / End -->

    </div>
    <!-- Footer / End -->
<?php
}
?>

</div>
<!-- Wrapper / End -->


<!-- Scripts
================================================== -->
<script src="<?= base_url('assets/js/jquery-3.6.0.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/jquery-migrate-3.3.2.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/mmenu.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/tippy.all.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/simplebar.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-slider.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/bootstrap-select.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/snackbar.j'); ?>s"></script>
<script src="<?= base_url('assets/js/clipboard.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/counterup.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/magnific-popup.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/slick.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/custom.js'); ?>"></script>

<!-- auto focus on search -->
<script>
    let inputElem = document.querySelector("input#header_search_field");
    window.addEventListener('load', function(e) {
        inputElem.select();
    })
</script>

<!-- Alert modal -->
<?php
if (isset($flash_data)) {
?>
    <script>
        $(document).ready(function() {
            Snackbar.show({
                text: '<?= $flash_data['alert_msg']; ?>',
                pos: 'top-right',
                showAction: true,
                actionText: "Dismiss",
                duration: 6000,
                textColor: '#fff',
                backgroundColor: '#383838'
            });
        });
    </script>
<?php
}
?>

<!-- Google Autocomplete -->
<script>
    function initAutocomplete() {
        var options = {
            types: ['(cities)'],
            // componentRestrictions: {country: "us"}
        };

        var input = document.getElementById('autocomplete-input');
        var autocomplete = new google.maps.places.Autocomplete(input, options);
    }

    // Autocomplete adjustment for homepage
    if ($('.intro-banner-search-form')[0]) {
        setTimeout(function() {
            $(".pac-container").prependTo(".intro-search-field.with-autocomplete");
        }, 300);
    }
</script>

<?php
if (isset($scripts_to_load)) :
    foreach ($scripts_to_load as $row) :
        if (substr($row, 0, 4) == "http") {
            $js_link = $row;
        } else {
            $js_link = base_url('assets/js/' . $row);
        }
        echo "<script src='$js_link'></script>
            ";
    endforeach;
endif;

// FAVOURITE SCRIPTS
if (logged_in() && isset($edition_data)) {
    if ($favourite['is_favourite']) {
?>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#fav_but").toggleClass('bookmarked');
            });
        </script>
    <?php
    }
    ?>
    <script type="text/javascript">
        // Ajax post
        $(document).ready(function() {
            $("#fav_but").click(function() {
                $(this).toggleClass('bookmarked');
                jQuery.ajax({
                    type: "POST",
                    url: "<?php echo base_url('favourite/add_remove_fav'); ?>",
                    dataType: 'html',
                    data: {
                        user_id: <?= user()->id; ?>,
                        edition_id: <?= $edition_data['edition_id']; ?>
                    },
                });
            });
        });
    </script>
<?php
}
?>

<!-- Leaflet // Docs: https://leafletjs.com/ -->
<script src="<?= base_url('assets/js/leaflet.min.js'); ?>"></script>

<!-- Leaflet Maps Scripts (locations are stored in leaflet-hireo.js) -->
<script src="<?= base_url('assets/js/leaflet-markercluster.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/leaflet-gesture-handling.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/leaflet-hireo.js'); ?>"></script>

<!-- Leaflet Geocoder + Search Autocomplete // Docs: https://github.com/perliedman/leaflet-control-geocoder -->
<script src="<?= base_url('assets/js/leaflet-autocomplete.js'); ?>"></script>
<script src="<?= base_url('assets/js/leaflet-control-geocoder.js'); ?>"></script>



<script data-cfasync="false" type="text/javascript" id="clever-core">
    (function(document, window) {
        var a, c = document.createElement("script");

        c.id = "CleverCoreLoader57832";
        c.src = "//scripts.cleverwebserver.com/cad3fbe52f17ce161d687847c744ea9d.js";

        c.async = !0;
        c.type = "text/javascript";
        c.setAttribute("data-target", window.name);
        c.setAttribute("data-callback", "put-your-callback-macro-here");

        try {
            a = parent.document.getElementsByTagName("script")[0] || document.getElementsByTagName("script")[0];
        } catch (e) {
            a = !1;
        }

        a || (a = document.getElementsByTagName("head")[0] || document.getElementsByTagName("body")[0]);
        a.parentNode.insertBefore(c, a);
    })(document, window);
</script>
</body>

</html>

<?php
    // d(user());
    // d($_SESSION);
    // d(user()->getRoles());
