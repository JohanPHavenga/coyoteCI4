<!-- Spacer -->
<div class="margin-top-50"></div>
<!-- Spacer / End-->
<div class="container">
    <div class="row">

        <div class="col-xl-9 col-lg-8 content-right-offset">
            <h2 class="page-title">Select a Province</h2>

            <div class="listings-container compact-list-layout margin-top-55">
                <?php
                foreach ($province_list as $province_id => $province) {
                ?>
                    <a href="<?= base_url('province/' . $province['province_slug']); ?>" class="job-listing">

                        <!-- Job Listing Details -->
                        <div class="job-listing-details">
                            <!-- Details -->
                            <div class="job-listing-description">
                                <h3 class="job-listing-title"><?= $province['province_name']; ?>
                            </div>
                        </div>
                    </a>
                <?php
                }
                ?>
            </div>

            <div class="col-xl-3 col-lg-4">
                <div class="sidebar-container margin-top-35">


                </div>
            </div>

        </div>
    </div>
</div>