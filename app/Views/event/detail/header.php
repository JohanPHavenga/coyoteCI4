<!-- Titlebar
================================================== -->
<div class="single-page-header" data-background-image="<?= $header_map_url ?>">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="single-page-header-inner">
                    <div class="left-side">
                        <div class="header-image"><img src="<?= $edition_data['logo_url']; ?>" alt=""></div>
                        <div class="header-details">
                            <h3><?= $edition_data['edition_name']; ?></h3>
                            <h5><?= $edition_data['town_name']; ?></h5>
                            <ul>
                                <li><a href="https://www.google.com/maps/search/?api=1&query=<?= $edition_data['edition_gps']; ?>"><i class="icon-material-outline-location-on"></i> <?=$edition_data['edition_address_end'];?></a></li>
                                <li>
                                    <!-- <div class="star-rating" data-rating="4.9"></div> -->
                                    <p><mark class="color"><?=$race_fee_range;?></mark></p>
                                </li>                                
                                <li>
                                    <div class="badge-with-title <?=$status_msg['state'];?>"><?=$status_msg['short_msg'];?></div>
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