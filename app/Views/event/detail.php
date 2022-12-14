<div class="container">
    <div class="row">

        <!-- Content -->
        <div class="col-xl-8 col-lg-8 content-right-offset">

            <div class="single-page-section">
                <?= view('event/detail/races'); ?>
            </div>

            <!-- INTRO -->
            <?= view('event/detail/intro'); ?>

            <!-- ENTRY -->
            <div class="single-page-section">
                <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                    <h4>Entry Detail</h4>
                </div>
                <?= view('event/detail/entries'); ?>
            </div>

            <!-- REGISTRATION -->
            <?php
            if ((!in_array(3, $edition_data['regtype_list'])) || (detail_field_strlen($edition_data['edition_reg_detail']))) {
            ?>
                <div class="single-page-section">
                    <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                        <h4>Registration / Number Collection</h4>
                    </div>
                    <?= view('event/detail/registration'); ?>
                </div>
            <?php
            }
            ?>

            <!-- RACE DAY INFO -->
            <div class="single-page-section">
                <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                    <h4>Race Day Information</h4>
                </div>
                <?= view('event/detail/race-day-info'); ?>
            </div>

            <!-- MAP -->
            <div class="single-page-section">
                <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                    <h4>Location Map</h4>
                </div>
                <?= view('event/detail/map'); ?>
            </div>

            <!-- ROUTE MAPS -->
            <div class="single-page-section">
                <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                    <h4>Route Maps</h4>
                </div>
                <?= view('event/detail/route-maps'); ?>
            </div>

            <!-- DOCUMENTS -->
            <?php
            if (!empty($file_list)) {
                if (sizeof($file_list) > 1) {
            ?>
                    <div class="single-page-section">
                        <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                            <h4>Documents</h4>
                        </div>

                        <?= view('event/detail/documents'); ?>
                    </div>
            <?php
                }
            }
            ?>
            <!-- CONTACT -->
            <div class="single-page-section">
                <div class="section-headline border-top margin-top-50 padding-top-40 margin-bottom-25">
                    <h4>Contact Race Organisers</h4>
                </div>
                <?= view('event/detail/contact'); ?>
            </div>
        </div>


        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">
                <?= view('event/side/summary'); ?>
                <?= view('event/side/bookmark'); ?>
                <?= view('event/side/tags'); ?>
            </div>
        </div>

    </div>
</div>