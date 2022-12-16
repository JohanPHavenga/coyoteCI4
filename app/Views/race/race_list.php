<!-- Spacer -->
<div class="margin-top-50"></div>
<!-- Spacer / End-->
<div class="container">
    <div class="row">

        <div class="col-xl-9 col-lg-8 content-right-offset">
            <h2 class="page-title"><?= $title; ?></h2>
            <div class="margin-top-15">

                <div class="sort-by">
                    <span>Sort by:</span>
                    <select class="selectpicker hide-tick">
                        <option>Date</option>
                        <option>Distance</option>
                    </select>
                </div>
            </div>

            <div class="listings-container compact-list-layout margin-top-55">


                <?php
                foreach ($edition_list as $edition_id => $edition) {
                    echo "<li><a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . fDateHumanShort($edition['edition_date']) . " - " . $edition['edition_name'] . "</a></li>";
                ?>
                    <!-- Job Listing -->
                    <a href="<?=base_url('event/' . $edition['edition_slug']);?>" class="job-listing">

                        <!-- Job Listing Details -->
                        <div class="job-listing-details">

                            <!-- Logo -->
                            <div class="job-listing-company-logo">
                                <img src="images/company-logo-01.png" alt="">
                            </div>

                            <!-- Details -->
                            <div class="job-listing-description">
                                <h3 class="job-listing-title">Bilingual Event Support Specialist</h3>

                                <!-- Job Listing Footer -->
                                <div class="job-listing-footer">
                                    <ul>
                                        <li><i class="icon-material-outline-business"></i> Hexagon <div class="verified-badge" title="Verified Employer" data-tippy-placement="top"></div>
                                        </li>
                                        <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                        <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                        <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                    </ul>
                                </div>
                            </div>

                            <!-- Bookmark -->
                            <span class="bookmark-icon"></span>
                        </div>
                    </a>
                <?php
                }
                ?>

                <!-- Job Listing -->
                <a href="single-job-page.html" class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <div class="job-listing-company-logo">
                            <img src="images/company-logo-05.png" alt="">
                        </div>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title">Competition Law Officer</h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-business"></i> Laxo</li>
                                    <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                    <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                    <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark -->
                        <span class="bookmark-icon"></span>
                    </div>
                </a>
                <!-- Job Listing -->
                <a href="single-job-page.html" class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <div class="job-listing-company-logo">
                            <img src="images/company-logo-02.png" alt="">
                        </div>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title">Barista and Cashier</h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-business"></i> Coffee</li>
                                    <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                    <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                    <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark -->
                        <span class="bookmark-icon"></span>
                    </div>
                </a>


                <!-- Job Listing -->
                <a href="single-job-page.html" class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <div class="job-listing-company-logo">
                            <img src="images/company-logo-03.png" alt="">
                        </div>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title">Restaurant General Manager</h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-business"></i> King <div class="verified-badge" title="Verified Employer" data-tippy-placement="top"></div>
                                    </li>
                                    <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                    <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                    <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark -->
                        <span class="bookmark-icon"></span>
                    </div>
                </a>

                <!-- Job Listing -->
                <a href="single-job-page.html" class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <div class="job-listing-company-logo">
                            <img src="images/company-logo-05.png" alt="">
                        </div>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title">International Marketing Coordinator</h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-business"></i> Skyist</li>
                                    <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                    <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                    <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark -->
                        <span class="bookmark-icon"></span>
                    </div>
                </a>

                <!-- Job Listing -->
                <a href="single-job-page.html" class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <div class="job-listing-company-logo">
                            <img src="images/company-logo-04.png" alt="">
                        </div>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title">Administrative Assistant</h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-business"></i> Mates</li>
                                    <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                    <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                    <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark -->
                        <span class="bookmark-icon"></span>
                    </div>
                </a>


                <!-- Job Listing -->
                <a href="single-job-page.html" class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <div class="job-listing-company-logo">
                            <img src="images/company-logo-05.png" alt="">
                        </div>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title">Construction Labourers</h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-business"></i> Podous</li>
                                    <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                    <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                    <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark -->
                        <span class="bookmark-icon"></span>
                    </div>
                </a>

                <!-- Job Listing -->
                <a href="single-job-page.html" class="job-listing">

                    <!-- Job Listing Details -->
                    <div class="job-listing-details">

                        <!-- Logo -->
                        <div class="job-listing-company-logo">
                            <img src="images/company-logo-06.png" alt="">
                        </div>

                        <!-- Details -->
                        <div class="job-listing-description">
                            <h3 class="job-listing-title">Human Resources Consultant</h3>

                            <!-- Job Listing Footer -->
                            <div class="job-listing-footer">
                                <ul>
                                    <li><i class="icon-material-outline-business"></i> Trideo</li>
                                    <li><i class="icon-material-outline-location-on"></i> San Francissco</li>
                                    <li><i class="icon-material-outline-business-center"></i> Full Time</li>
                                    <li><i class="icon-material-outline-access-time"></i> 2 days ago</li>
                                </ul>
                            </div>
                        </div>

                        <!-- Bookmark -->
                        <span class="bookmark-icon"></span>
                    </div>
                </a>
            </div>


            <!-- Pagination -->
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12">
                    <!-- Pagination -->
                    <div class="pagination-container margin-top-60 margin-bottom-60">
                        <nav class="pagination">
                            <ul>
                                <li class="pagination-arrow"><a href="#"><i class="icon-material-outline-keyboard-arrow-left"></i></a></li>
                                <li><a href="#">1</a></li>
                                <li><a href="#" class="current-page">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li class="pagination-arrow"><a href="#"><i class="icon-material-outline-keyboard-arrow-right"></i></a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!-- Pagination / End -->

        </div>

        <div class="col-xl-3 col-lg-4">
            <div class="sidebar-container">

                <!-- Location -->
                <div class="sidebar-widget">
                    <h3>Location</h3>
                    <div class="input-with-icon">
                        <div id="autocomplete-container">
                            <input id="autocomplete-input" type="text" placeholder="Location">
                        </div>
                        <i class="icon-material-outline-location-on"></i>
                    </div>
                </div>

                <!-- Keywords -->
                <div class="sidebar-widget">
                    <h3>Keywords</h3>
                    <div class="keywords-container">
                        <div class="keyword-input-container">
                            <input type="text" class="keyword-input" placeholder="e.g. job title" />
                            <button class="keyword-input-button ripple-effect"><i class="icon-material-outline-add"></i></button>
                        </div>
                        <div class="keywords-list">
                            <!-- keywords go here -->
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <!-- Category -->
                <div class="sidebar-widget">
                    <h3>Category</h3>
                    <select class="selectpicker" multiple data-selected-text-format="count" data-size="7" title="All Categories">
                        <option>Accounting and Finance</option>
                        <option>Clerical & Data Entry</option>
                        <option>Counseling</option>
                        <option>Court Administration</option>
                        <option>Human Resources</option>
                        <option>Investigative</option>
                        <option>IT and Computers</option>
                        <option>Law Enforcement</option>
                        <option>Management</option>
                        <option>Miscellaneous</option>
                        <option>Public Relations</option>
                    </select>
                </div>

                <!-- Job Types -->
                <div class="sidebar-widget">
                    <h3>Job Type</h3>

                    <div class="switches-list">
                        <div class="switch-container">
                            <label class="switch"><input type="checkbox"><span class="switch-button"></span> Freelance</label>
                        </div>

                        <div class="switch-container">
                            <label class="switch"><input type="checkbox"><span class="switch-button"></span> Full Time</label>
                        </div>

                        <div class="switch-container">
                            <label class="switch"><input type="checkbox"><span class="switch-button"></span> Part Time</label>
                        </div>

                        <div class="switch-container">
                            <label class="switch"><input type="checkbox"><span class="switch-button"></span> Internship</label>
                        </div>
                        <div class="switch-container">
                            <label class="switch"><input type="checkbox"><span class="switch-button"></span> Temporary</label>
                        </div>
                    </div>

                </div>

                <!-- Salary -->
                <div class="sidebar-widget">
                    <h3>Salary</h3>
                    <div class="margin-top-55"></div>

                    <!-- Range Slider -->
                    <input class="range-slider" type="text" value="" data-slider-currency="$" data-slider-min="1500" data-slider-max="15000" data-slider-step="100" data-slider-value="[1500,15000]" />
                </div>

                <!-- Tags -->
                <div class="sidebar-widget">
                    <h3>Tags</h3>

                    <div class="tags-container">
                        <div class="tag">
                            <input type="checkbox" id="tag1" />
                            <label for="tag1">front-end dev</label>
                        </div>
                        <div class="tag">
                            <input type="checkbox" id="tag2" />
                            <label for="tag2">angular</label>
                        </div>
                        <div class="tag">
                            <input type="checkbox" id="tag3" />
                            <label for="tag3">react</label>
                        </div>
                        <div class="tag">
                            <input type="checkbox" id="tag4" />
                            <label for="tag4">vue js</label>
                        </div>
                        <div class="tag">
                            <input type="checkbox" id="tag5" />
                            <label for="tag5">web apps</label>
                        </div>
                        <div class="tag">
                            <input type="checkbox" id="tag6" />
                            <label for="tag6">design</label>
                        </div>
                        <div class="tag">
                            <input type="checkbox" id="tag7" />
                            <label for="tag7">wordpress</label>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>

        <div class="col-xl-12">

            <?php
            if (!isset($edition_list)) {
                echo "<p>No races found</p>";
            } elseif (empty($edition_list)) {
                echo "<p>No races to show</p>";
            }
            ?>
            <ul>
                <?php
                foreach ($edition_list as $edition_id => $edition) {
                    echo "<li><a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . fDateHumanShort($edition['edition_date']) . " - " . $edition['edition_name'] . "</a></li>";
                }
                ?>
            </ul>
        </div>

    </div>
</div>
<?php
// d($edition_list);
