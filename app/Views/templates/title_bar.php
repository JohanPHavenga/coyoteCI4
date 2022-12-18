<div id="titlebar" class="gradient">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <h2><?=$page_title;?></h2>
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