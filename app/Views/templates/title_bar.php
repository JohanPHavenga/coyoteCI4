<!-- Titlebar
 ================================================== -->
<div id="titlebar" class="gradient">
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h2><?=$title;?></h2>

                <!-- Breadcrumbs -->
                <nav id="breadcrumbs" class="dark">
                    <ul>
                        <?php
                            foreach ($bc_arr as $bc_title=>$bc_url) {
                                echo "<li><a href='$bc_url'>$bc_title</a></li>";
                            }
                        ?>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>