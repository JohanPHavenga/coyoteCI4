<div class="container">
    <div class="row">

        <div class="col-xl-9 col-lg-8 content-right-offset">
            <?php
            if (isset($list)) {
                echo $list;
            } else {
            ?>
                <div class="margin-top-15">
                    <div class="notification warning closeable">
                        <?= $notice; ?>
                        <a class="close"></a>
                    </div>
                </div>
            <?php
            }
            ?>

        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="sidebar-container margin-top-15">


            </div>
        </div>

    </div>
</div>
