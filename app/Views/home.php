<!-- Intro Banner
================================================== -->
<div class="intro-banner dark-overlay big-padding">

    <!-- Transparent Header Spacer -->
    <div class="transparent-header-spacer"></div>

    <div class="container">

        <!-- Intro Headline -->
        <div class="row">
            <div class="col-md-12">
                <div class="banner-headline-alt">
                    <h3>Run like nobody is chasing you</h3>
                    <span>Find joy in running</span>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="row">
            <div class="col-md-12">
                <div class="intro-banner-search-form margin-top-95">
                    <?php
                    // search form
                    $attributes = ['class' => 'inline-form-home', 'id' => 'home_search', 'method' => 'get'];
                    echo form_open(base_url('search'), $attributes);
                    ?>
                    <!-- Search Field -->
                    <div class="intro-search-field">
                        <label for="intro-keywords" class="field-title ripple-effect">Race Search</label>
                        <input id="intro-keywords" type="text" placeholder="Search" name="s">
                    </div>

                    <!-- Search Field -->
                    <div class="intro-search-field">
                        <select class="selectpicker default" multiple data-selected-text-format="count" data-size="7" title="All Distances" name="distance[]">
                            <?php
                            foreach ($race_distance_list as $distance => $name) {
                                $sel = '';
                                if (isset($_GET['distance'])) {
                                    if (in_array($distance, $_GET['distance'])) {
                                        $sel = "selected";
                                    } else {
                                        $sel = '';
                                    }
                                }
                                echo "<option value='$distance' $sel>$name</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Button -->
                    <div class="intro-search-button">
                        <button class="button ripple-effect" type="submit">Search</button>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="row">
            <div class="col-md-12">
                <ul class="intro-stats margin-top-45 hide-under-992px">
                    <li>
                        <strong class="counter"><?= $all_editions_count; ?></strong>
                        <span>Events Loaded</span>
                    </li>
                    <li>
                        <strong class="counter"><?= $all_races_count; ?></strong>
                        <span>Races Loaded</span>
                    </li>
                    <li>
                        <strong class="counter"><?= $all_races_with_results_count; ?></strong>
                        <span>Result sets Loaded</span>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Video Container -->
    <div class="video-container" data-background-image="<?= base_url('assets/images/banner/run_13.webp'); ?>">
        <!-- <video loop autoplay muted>
            <source src="images/home-video-background.mp4" type="video/mp4">
        </video> -->
    </div>

</div>

<!-- Content
================================================== -->
<!-- Features Jobs -->
<div class="section padding-top-65 padding-bottom-75">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">

                <!-- Section Headline -->
                <div class="section-headline margin-top-0 margin-bottom-35">
                    <h3>Featured Races</h3>
                    <a href="<?= base_url('calendar'); ?>" class="headline-link">Browse All Races</a>
                </div>

                <?= $featured_list; ?>               

            </div>
        </div>
    </div>
</div>
<!-- Featured Jobs / End -->


<!-- Photo Section -->
<div class="photo-section" data-background-image="<?= base_url('assets/images/banner/run_07.webp'); ?>">

    <!-- Infobox -->
    <div class="text-content white-font">
        <div class="container">

            <div class="row">
                <div class="col-lg-6 col-md-8 col-sm-12">
                    <h2>Finding the right informaiton <br> about running events in South Africa.</h2>
                    <p>Our mission is to list running events in a standard way, on a modern multi-platform capable website. Giving you, the user, an easy way to find and compare races and allow for easy entry via the various 3rd party entry portals.</p>
                    <a href="pages-pricing-plans.html" class="button button-sliding-icon ripple-effect big margin-top-20">Race Calendar <i class="icon-material-outline-arrow-right-alt"></i></a>
                </div>
            </div>

        </div>
    </div>

    <!-- Infobox / End -->

</div>
<!-- Photo Section / End -->


<!-- Recent Blog Posts -->
<div class="section padding-top-65 padding-bottom-50">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">

                <!-- Section Headline -->
                <div class="section-headline margin-top-0 margin-bottom-45">
                    <h3>From The Blog</h3>
                    <a href="pages-blog.html" class="headline-link">View Blog</a>
                </div>

                <div class="row">
                    <!-- Blog Post Item -->
                    <div class="col-xl-4">
                        <a href="pages-blog-post.html" class="blog-compact-item-container">
                            <div class="blog-compact-item">
                                <img src="images/blog-01a.jpg" alt="">
                                <span class="blog-item-tag">Tips</span>
                                <div class="blog-compact-item-content">
                                    <ul class="blog-post-tags">
                                        <li>22 July 2019</li>
                                    </ul>
                                    <h3>16 Ridiculously Easy Ways to Find & Keep a Remote Job</h3>
                                    <p>Distinctively reengineer revolutionary meta-services and premium architectures intuitive opportunities.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Blog post Item / End -->

                    <!-- Blog Post Item -->
                    <div class="col-xl-4">
                        <a href="pages-blog-post.html" class="blog-compact-item-container">
                            <div class="blog-compact-item">
                                <img src="images/blog-02a.jpg" alt="">
                                <span class="blog-item-tag">Recruiting</span>
                                <div class="blog-compact-item-content">
                                    <ul class="blog-post-tags">
                                        <li>29 June 2019</li>
                                    </ul>
                                    <h3>How to "Woo" a Recruiter and Land Your Dream Job</h3>
                                    <p>Appropriately empower dynamic leadership skills after business portals. Globally myocardinate interactive.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Blog post Item / End -->

                    <!-- Blog Post Item -->
                    <div class="col-xl-4">
                        <a href="pages-blog-post.html" class="blog-compact-item-container">
                            <div class="blog-compact-item">
                                <img src="images/blog-03a.jpg" alt="">
                                <span class="blog-item-tag">Marketing</span>
                                <div class="blog-compact-item-content">
                                    <ul class="blog-post-tags">
                                        <li>10 June 2019</li>
                                    </ul>
                                    <h3>11 Tips to Help You Get New Clients Through Cold Calling</h3>
                                    <p>Compellingly embrace empowered e-business after user friendly intellectual capital. Interactively front-end.</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Blog post Item / End -->
                </div>


            </div>
        </div>
    </div>
</div>
<!-- Recent Blog Posts / End -->

<div class="section border-top padding-top-45 padding-bottom-45">
    <!-- Logo Carousel -->
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <!-- Carousel -->
                <div class="col-md-12">
                    <div class="logo-carousel">

                        <div class="carousel-item">
                            <a href="http://acmelogos.com/" target="_blank" title="http://acmelogos.com/"><img src="images/logo-carousel-01.png" alt=""></a>
                        </div>

                        <div class="carousel-item">
                            <a href="http://acmelogos.com/" target="_blank" title="http://acmelogos.com/"><img src="images/logo-carousel-02.png" alt=""></a>
                        </div>

                        <div class="carousel-item">
                            <a href="http://acmelogos.com/" target="_blank" title="http://acmelogos.com/"><img src="images/logo-carousel-03.png" alt=""></a>
                        </div>

                        <div class="carousel-item">
                            <a href="http://acmelogos.com/" target="_blank" title="http://acmelogos.com/"><img src="images/logo-carousel-04.png" alt=""></a>
                        </div>

                        <div class="carousel-item">
                            <a href="http://acmelogos.com/" target="_blank" title="http://acmelogos.com/"><img src="images/logo-carousel-05.png" alt=""></a>
                        </div>

                        <div class="carousel-item">
                            <a href="http://acmelogos.com/" target="_blank" title="http://acmelogos.com/"><img src="images/logo-carousel-06.png" alt=""></a>
                        </div>

                    </div>
                </div>
                <!-- Carousel / End -->
            </div>
        </div>
    </div>
</div>


<?php
echo "<p>There are " . $all_editions_count . " editions in the database<p>";

echo "<p>Featured races:</p><ul>";

foreach ($featured as $edition) {
    echo "<li><a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a></li>";
}

echo "</ul>";

echo "<p>The last 5 updated were:</p><ul>";

foreach ($last_updated as $edition) {
    echo "<li><a href='" . base_url('event/' . $edition['edition_slug']) . "'>" . $edition['edition_name'] . "</a></li>";
}

echo "</ul>";
