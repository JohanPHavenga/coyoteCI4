<!-- Titlebar
================================================== -->
<div class="single-page-header" data-background-image="<?= $header_map_url ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="single-page-header-inner">
                    <div class="left-side">
                        <div class="header-image"><a href="single-company-profile.html"><img src="<?= $edition_data['logo_url']; ?>" alt=""></a></div>
                        <div class="header-details">
                            <h3><?= $edition_data['edition_name']; ?></h3>
                            <h5><?= $edition_data['town_name']; ?></h5>
                            <ul>
                                <li><a href="single-company-profile.html"><i class="icon-material-outline-location-on"></i> <?=$edition_data['edition_address_end'];?></a></li>
                                <li>
                                    <!-- <div class="star-rating" data-rating="4.9"></div> -->
                                    <p><mark class="color">Fees TBC</mark></p>
                                </li>                                
                                <li>
                                    <div class="badge-with-title verified">Verified</div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="right-side">
                        <div class="salary-box">
                            <div class="salary-type">Event Date</div>
                            <div class="salary-amount"><?= $event_date_range; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p>
            <!-- <img src="https://www.mapquestapi.com/staticmap/v5/map?key=4hQ9sTygIAgGPerQAj8Pp7eDop0bRckH&center=CapeTown,ZA&size=@2x" /> -->
        </p>
    </div>
</div>

<div class="container">
    <div class="row">

        <!-- Content -->
        <div class="col-xl-8 col-lg-8 content-right-offset">

            <div class="single-page-section">
                <h3 class="margin-bottom-25">Intro</h3>
                <?php
                if (strlen($edition_data['edition_intro_detail'] > 10)) {
                    echo $edition_data['edition_intro_detail'];
                }
                ?>
            </div>

            <div class="single-page-section">
                <h3 class="margin-bottom-30">Location</h3>
                <div id="single-job-map-container">
                    <div id="singleListingMap" data-latitude="51.507717" data-longitude="-0.131095" data-map-icon="im im-icon-Hamburger"></div>
                    <a href="#" id="streetView">Street View</a>
                </div>
            </div>

            <div class="single-page-section">
                <h3 class="margin-bottom-25">Similar Jobs</h3>

                <!-- Listings Container -->
                <div class="listings-container grid-layout">

                    <!-- Job Listing -->
                    <a href="#" class="job-listing">

                        <!-- Job Listing Details -->
                        <div class="job-listing-details">
                            <!-- Logo -->
                            <div class="job-listing-company-logo">
                                <img src="images/company-logo-02.png" alt="">
                            </div>

                            <!-- Details -->
                            <div class="job-listing-description">
                                <h4 class="job-listing-company">Coffee</h4>
                                <h3 class="job-listing-title">Barista and Cashier</h3>
                            </div>
                        </div>

                        <!-- Job Listing Footer -->
                        <div class="job-listing-footer">
                            <ul>
                                <li><i class="icon-material-outline-location-on"></i> San Francisco</li>
                                <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                <li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
                                <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                            </ul>
                        </div>
                    </a>

                    <!-- Job Listing -->
                    <a href="#" class="job-listing">

                        <!-- Job Listing Details -->
                        <div class="job-listing-details">
                            <!-- Logo -->
                            <div class="job-listing-company-logo">
                                <img src="images/company-logo-03.png" alt="">
                            </div>

                            <!-- Details -->
                            <div class="job-listing-description">
                                <h4 class="job-listing-company">King <span class="verified-badge" title="Verified Employer" data-tippy-placement="top"></span></h4>
                                <h3 class="job-listing-title">Restaurant Manager</h3>
                            </div>
                        </div>

                        <!-- Job Listing Footer -->
                        <div class="job-listing-footer">
                            <ul>
                                <li><i class="icon-material-outline-location-on"></i> San Francisco</li>
                                <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                <li><i class="icon-material-outline-account-balance-wallet"></i> $35000-$38000</li>
                                <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                            </ul>
                        </div>
                    </a>
                </div>
                <!-- Listings Container / End -->

            </div>
        </div>


        <!-- Sidebar -->
        <div class="col-xl-4 col-lg-4">
            <div class="sidebar-container">

                <a href="#small-dialog" class="apply-now-button popup-with-zoom-anim">Apply Now <i class="icon-material-outline-arrow-right-alt"></i></a>

                <!-- Sidebar Widget -->
                <div class="sidebar-widget">
                    <div class="job-overview">
                        <div class="job-overview-headline">Job Summary</div>
                        <div class="job-overview-inner">
                            <ul>
                                <li>
                                    <i class="icon-material-outline-location-on"></i>
                                    <span>Location</span>
                                    <h5>London, United Kingdom</h5>
                                </li>
                                <li>
                                    <i class="icon-material-outline-business-center"></i>
                                    <span>Job Type</span>
                                    <h5>Full Time</h5>
                                </li>
                                <li>
                                    <i class="icon-material-outline-local-atm"></i>
                                    <span>Salary</span>
                                    <h5>$35k - $38k</h5>
                                </li>
                                <li>
                                    <i class="icon-material-outline-access-time"></i>
                                    <span>Date Posted</span>
                                    <h5>2 days ago</h5>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

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