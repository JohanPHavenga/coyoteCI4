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



                <!-- Sidebar Widget -->
                <div class="sidebar-widget">
                    <h3>Bookmark or Share</h3>

                    <!-- Bookmark Button -->
                    <button class="bookmark-button margin-bottom-25">
                        <span class="bookmark-icon"></span>
                        <span class="bookmark-text">Bookmark</span>
                        <span class="bookmarked-text">Bookmarked</span>
                    </button>

                    <!-- Copy URL -->
                    <div class="copy-url">
                        <input id="copy-url" type="text" value="" class="with-border">
                        <button class="copy-url-button ripple-effect" data-clipboard-target="#copy-url" title="Copy to Clipboard" data-tippy-placement="top"><i class="icon-material-outline-file-copy"></i></button>
                    </div>

                    <!-- Share Buttons -->
                    <div class="share-buttons margin-top-25">
                        <div class="share-buttons-trigger"><i class="icon-feather-share-2"></i></div>
                        <div class="share-buttons-content">
                            <span>Interesting? <strong>Share It!</strong></span>
                            <ul class="share-buttons-icons">
                                <li><a href="#" data-button-color="#3b5998" title="Share on Facebook" data-tippy-placement="top"><i class="icon-brand-facebook-f"></i></a></li>
                                <li><a href="#" data-button-color="#1da1f2" title="Share on Twitter" data-tippy-placement="top"><i class="icon-brand-twitter"></i></a></li>
                                <li><a href="#" data-button-color="#dd4b39" title="Share on Google Plus" data-tippy-placement="top"><i class="icon-brand-google-plus-g"></i></a></li>
                                <li><a href="#" data-button-color="#0077b5" title="Share on LinkedIn" data-tippy-placement="top"><i class="icon-brand-linkedin-in"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<?php
d($edition_data);
d($race_list);
d($favourite);
?>
<h4>Favourite Button</h4>
<?php
$data = [
    'class'    => "btn " . $favourite['btn_class'] . " btn-xs",
    'id'      => $favourite['id'],
    'type'    => 'submit',
    'content' => 'Favourite',
];
echo form_button($data);;
?>

<h4>Subscribe form</h4>
<?php
$field_data = [
    'name'  => 'email_sub',
    'id'    => 'email_sub',
];
if (logged_in()) {
    $field_data['value'] = set_value('email_sub', user()->email, true);
    $sub_form_url = base_url('user/subscribe/edition');
    $method = 'post';
} else {
    $sub_form_url = base_url('login');
    $method = 'get';
}

$attributes = ['id' => 'subscribe_form', 'method' => $method];
echo form_open($sub_form_url, $attributes);
echo form_input($field_data);

$field_data = [
    'name'  => 'edition_id_sub',
    'id'    => 'edition_id_sub',
    'value' => $edition_data['edition_id'],
    'type' => 'hidden'
];
echo form_input($field_data);

$button_data = [
    'id'      => 'subscribe',
    'type'    => 'submit',
    'content' => 'Add to mailing list',
];
echo form_button($button_data);;
echo form_close();
?>

<!-- Apply for a job popup
================================================== -->
<div id="small-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

    <!--Tabs -->
    <div class="sign-in-form">

        <ul class="popup-tabs-nav">
            <li><a href="#tab1">Apply Now</a></li>
            <li><a href="#tab2">Second</a></li>
        </ul>

        <div class="popup-tabs-container">

            <!-- Tab -->
            <div class="popup-tab-content" id="tab1">

                <!-- Welcome Text -->
                <div class="welcome-text">
                    <h3>Attach File With CV</h3>
                </div>

                <!-- Form -->
                <form method="post" id="apply-now-form">

                    <div class="input-with-icon-left">
                        <i class="icon-material-outline-account-circle"></i>
                        <input type="text" class="input-text with-border" name="name" id="name" placeholder="First and Last Name" required />
                    </div>

                    <div class="input-with-icon-left">
                        <i class="icon-material-baseline-mail-outline"></i>
                        <input type="text" class="input-text with-border" name="emailaddress" id="emailaddress" placeholder="Email Address" required />
                    </div>

                    <div class="uploadButton">
                        <input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload-cv" />
                        <label class="uploadButton-button ripple-effect" for="upload-cv">Select File</label>
                        <span class="uploadButton-file-name">Upload your CV / resume relevant file. <br> Max. file size: 50 MB.</span>
                    </div>

                </form>

                <!-- Button -->
                <button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit" form="apply-now-form">Apply Now <i class="icon-material-outline-arrow-right-alt"></i></button>

            </div>

            <!-- Tab -->
            <div class="popup-tab-content" id="tab2">

                <!-- Welcome Text -->
                <div class="welcome-text">
                    <h3>Attach File With CV</h3>
                </div>

                <!-- Form -->
                <form method="post" id="apply-now-form">

                    <div class="input-with-icon-left">
                        <i class="icon-material-outline-account-circle"></i>
                        <input type="text" class="input-text with-border" name="name" id="name" placeholder="First and Last Name" required />
                    </div>

                    <div class="input-with-icon-left">
                        <i class="icon-material-baseline-mail-outline"></i>
                        <input type="text" class="input-text with-border" name="emailaddress" id="emailaddress" placeholder="Email Address" required />
                    </div>

                    <div class="uploadButton">
                        <input class="uploadButton-input" type="file" accept="image/*, application/pdf" id="upload-cv" />
                        <label class="uploadButton-button ripple-effect" for="upload-cv">Select File</label>
                        <span class="uploadButton-file-name">Upload your CV / resume relevant file. <br> Max. file size: 50 MB.</span>
                    </div>

                </form>

                <!-- Button -->
                <button class="button margin-top-35 full-width button-sliding-icon ripple-effect" type="submit" form="apply-now-form">Apply Now <i class="icon-material-outline-arrow-right-alt"></i></button>

            </div>

        </div>
    </div>
</div>
<!-- Apply for a job popup / End -->