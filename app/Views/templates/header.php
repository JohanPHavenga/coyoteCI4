<html>

<head>
    <meta charset="utf-8" />
    <title>Coyote CI4</title>

    <link rel="apple-touch-icon" sizes="152x152" href="<?= base_url('assets/favicon/apple-touch-icon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/favicon/favicon-16x16.png'); ?>">
    <link rel="manifest" href="<?= base_url('assets/favicon/manifest.json'); ?>">
    <link rel="mask-icon" href="<?= base_url('assets/favicon/safari-pinned-tab.svg'); ?>" color="#5bbad5">
    <meta name="apple-mobile-web-app-title" content="RoadRunningZA">
    <meta name="application-name" content="RoadRunningZA">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <header>
        <nav>
            <ul>
                <?php
                // main menu
                // foreach ($menus['main_menu'] as $menu_item) {
                //     echo "<li><a href='" . $menu_item['loc'] . "'>" . $menu_item['display'] . "</a>";
                //     if (isset($menu_item['sub-menu'])) {
                //         echo "<ul>";
                //         foreach ($menu_item['sub-menu'] as $sub_menu_item) {
                //             echo "<li><a href='" . $sub_menu_item['loc'] . "'>" . $sub_menu_item['display'] . "</a>";
                //         }
                //         echo "</ul>";
                //     }
                //     echo "</li>";
                // }
                // if (logged_in()) {
                //     echo "<li><a href='" . base_url('logout') . "'>Logout</a></li>";
                // } else {
                //     echo "<li><a href='" . base_url('login') . "'>Login</a></li>";
                // }
                ?>
            </ul>
        </nav>
    </header>
    <?php
    
        // d($menus['main_menu']);