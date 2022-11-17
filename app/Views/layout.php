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
    <?= $this->renderSection('pageStyles') ?>
</head>

<body>
    <header>
        <nav>
            <ul>
                <li><a href="<?= base_url(); ?>">Home</a></li>
                <li><a href="<?= base_url('about'); ?>">About</a></li>
                <li><a href="<?= base_url('contact'); ?>">Contact</a></li>
                <?php
                if (logged_in()) {
                    echo "<li><a href='" . base_url('logout') . "'>Logout</a></li>";
                } else {
                    echo "<li><a href='" . base_url('login') . "'>Login</a></li>";
                }
                ?>
            </ul>
        </nav>
    </header>

    <main role="main" class="container">
        <?= $this->renderSection('main') ?>
    </main><!-- /.container -->

    <p>&nbsp;</p>
    <footer>
        Coyote &copy; <?= date("Y"); ?>
    </footer>
    <?= $this->renderSection('pageScripts') ?>
</body>

</html>