<div id="titlebar" class="gradient">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <h2><?= $page_title; ?></h2>
                <?php
                if (isset($_GET['s'])) {
                    echo "<h4>for <mark class='color'>" . $_GET['s'] . "</mark></h4>";
                }
                if (isset($_GET['distance'])) {
                    echo "<h4>distance ";
                    foreach ($_GET['distance'] as $dist) {
                        echo " <mark class='color' style='background-color: ".race_color(intval($dist))."'>" . fraceDistance($dist) . "</mark>";
                    }
                    echo "</h4>";
                }
                if (isset($_GET['location'])) {
                    if (!empty($_GET['location'])) {
                        echo "<h4>location <mark class='color'>" . $_GET['location'] . "</mark></h4>";
                    }
                }
                ?>
                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="">
                    <ul>
                        <?php
                        foreach ($bc_arr as $text => $url) {
                            if ($text == array_key_last($bc_arr)) {
                                echo "<li>$text</li>";
                            } else {
                                echo "<li><a href='$url'>$text</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>