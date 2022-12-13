<?php
if (!isset($page_title)) {
    $page_title = "RoadRunning.co.za - Run without being chased";
}
if (!isset($meta_description)) {
    $meta_description = "Listing Road Running races all over South Africa, presented in a modern, uniform manner";
}
if (!isset($meta_robots)) {
    $meta_robots = "index";
}
?>
<!doctype html>
<html lang="en">

<head>
    <title><?= $page_title; ?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="<?= $meta_description; ?>">
    <meta name="robots" content="<?= $meta_robots; ?>" />

    <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/favicon/apple-icon-152x152.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/favicon/favicon-16x16.png'); ?>">
    <link rel="manifest" href="<?= base_url('assets/favicon/manifest.json'); ?>">
    <link rel="mask-icon" href="<?= base_url('assets/favicon/safari-pinned-tab.svg'); ?>" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="RoadRunningZA">
    <meta name="application-name" content="RoadRunningZA">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/colors/blue.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/custom.css'); ?>">
</head>

<body>
    <!-- Wrapper -->
    <div id="wrapper">

        <!-- Header Container
        ================================================== -->
        <header id="header-container" class="fullwidth">
            <!-- Header -->
            <div id="header">
                <div class="container">

                    <!-- Left Side Content -->
                    <div class="left-side">

                        <!-- Logo -->
                        <div id="logo">
                            <a href="<?= base_url(); ?>"><img src="<?= base_url('assets/favicon/apple-icon-57x57.png'); ?>" alt=""></a>
                        </div>

                        <!-- Main Navigation -->
                        <nav id="navigation" class="light">
                            <ul id="responsive">
                                <?php
                                foreach ($menus['main_menu'] as $menu_item) {
                                    echo "<li><a href='" . $menu_item['loc'] . "'>" . $menu_item['display'] . "</a>";
                                    if (isset($menu_item['sub-menu'])) {
                                        echo "<ul class='dropdown-nav'>";
                                        foreach ($menu_item['sub-menu'] as $sub_menu_item) {
                                            echo "<li><a href='" . $sub_menu_item['loc'] . "'>" . $sub_menu_item['display'] . "</a>";
                                        }
                                        echo "</ul>";
                                    }
                                    echo "</li>";
                                }
                                // dd($event_menu);
                                if (isset($event_menu)) {
                                    echo "<li><a href='" . base_url('event/' . $slug) . "'>Race Menu</a>";
                                    echo "<ul class='dropdown-nav'>";
                                    foreach ($event_menu as $menu_item) {
                                        echo "<li><a href='" . $menu_item['loc'] . "'>" . $menu_item['display'] . "</a>";
                                        if (isset($menu_item['sub_menu'])) {
                                            echo "<ul class='dropdown-nav'>";
                                            foreach ($menu_item['sub_menu'] as $sub_menu_item) {
                                                echo "<li><a href='" . $sub_menu_item['loc'] . "'>" . $sub_menu_item['display'] . "</a>";
                                            }
                                            echo "</ul>";
                                        }
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                    echo "</li>";
                                }
                                ?>
                                <li class='show_on_tablets_and_below'><a href='<?= base_url('search'); ?>'>Search</a></li>
                            </ul>
                        </nav>
                        <div class="clearfix"></div>

                        <!-- Main Navigation / End -->

                    </div>
                    <!-- Left Side Content / End -->
                    <!-- Right Side Content / End -->
                    <div class="right-side">
                        <?php
                        // search form
                        $attributes = ['class' => 'search, inline-form', 'id' => 'header_search', 'method' => 'get', 'style' => 'float: left; margin: 15px 15px 0;'];
                        echo form_open(base_url('search'), $attributes);
                        $data = [
                            'name'  => 's',
                            'id'    => 'header_search_field',
                            'value' => $search_string,
                            'placeholder' => 'Search',
                            // 'autofocus' => '',
                        ];
                        echo form_input($data);
                        $data = [
                            'type'    => 'submit',
                            'content' => '<i class="icon-feather-arrow-right"></i>',
                        ];
                        // echo form_button($data);;
                        echo form_close();
                        ?>
                        <!-- User Menu -->
                        <div class="header-widget">

                            <!-- Messages -->
                            <div class="header-notifications user-menu">
                                <div class="header-notifications-trigger">
                                    <a href="#">
                                        <div class="user-avatar status-<?= $user_status; ?>"><img src="<?= $user_avatar; ?>" alt=""></div>
                                    </a>
                                </div>

                                <!-- Dropdown -->
                                <div class="header-notifications-dropdown">

                                    <?php
                                    if (logged_in()) {
                                    ?>
                                        <!-- User Status -->
                                        <div class="user-status">

                                            <!-- User Name / Avatar -->
                                            <div class="user-details">
                                                <div class="user-avatar status-online"><img src="<?= $user_avatar; ?>" alt=""></div>
                                                <div class="user-name">
                                                    <?= user()->name; ?><span><?= user()->surname; ?></span>
                                                </div>
                                            </div>

                                            <?php
                                            // province switch form
                                            $attributes = ['class' => 'province_switch margin-top-25', 'id' => 'site_version', 'method' => 'post'];
                                            echo form_open(base_url('province/switch'), $attributes);

                                            $js = "class='selectpicker with-border' onchange='this.form.submit()'";
                                            echo form_dropdown('province_switch', $province_options, $_SESSION['site_province'], $js);
                                            echo form_close();
                                            ?>
                                        </div>

                                        <ul class="user-menu-small-nav">
                                            <?php
                                            foreach ($menus['user_menu'] as $menu_item) {
                                                echo "<li><a href='" . $menu_item['loc'] . "'><i class='" . $menu_item['icon'] . "'></i> " . $menu_item['display'] . "</a>";
                                            }
                                            ?>
                                        </ul>
                                    <?php
                                    } else {
                                    ?>
                                        <!-- User Status -->
                                        <div class="user-status">

                                            <!-- User Name / Avatar -->
                                            <div class="user-details">
                                                <div class="user-avatar status-away"><img src="<?= $user_avatar; ?>" alt=""></div>
                                                <div class="user-name">
                                                    Not logged in <span>Anonymous</span>
                                                </div>
                                            </div>
                                        </div>
                                        <ul class="user-menu-small-nav">
                                            <li><a href="<?= base_url('login'); ?>"><i class="icon-material-outline-input"></i> Login</a></li>
                                        </ul>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                        </div>
                        <!-- User Menu / End -->

                        <!-- Mobile Navigation Button -->
                        <span class="mmenu-trigger">
                            <button class="hamburger hamburger--collapse" type="button">
                                <span class="hamburger-box">
                                    <span class="hamburger-inner"></span>
                                </span>
                            </button>
                        </span>

                    </div>
                    <!-- Right Side Content / End -->
                </div>
            </div>
            <!-- Header / End -->

        </header>
        <div class="clearfix"></div>
        <!-- Header Container / End -->