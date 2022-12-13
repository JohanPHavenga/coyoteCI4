    <?php
    if ((empty($route_maps)) && (empty($route_profile))) {
        $mailing_list_notice = "<p>If you would like to be notified once route maps are loaded, "
            . "please add yourself to the "
            . "<a href='" . base_url('event/' . $slug . '/subscribe') . "' title='Add yourself to the mailing list'>mailing list</a> for this race.</p>";
        if (!$in_past) {
            $msg = "<p><b>No route maps</b> has been made available for this race yet.</p>";
        } else {
            $msg = "<p>No route maps were made available for this race.</p>";
        }
    ?>
        <div class="row m-b-40">
            <div class="col-lg-12">
                <div class="notification warning closeable">
                    <?= $msg; ?>
                    <a class="close"></a>
                </div>
                <?php
                if (!$in_past) {
                    echo $mailing_list_notice;
                }
                ?>
            </div>
        </div>
    <?php

        // if there is route maps
    } else {
    ?>
        <div class="row margin-bottom-40">
            <div class="col-lg-12">
                <div class="notification success closeable">
                    <b>Route maps has been loaded!</b>
                    <a class="close"></a>
                </div>
                <?php

                if (isset($route_maps['edition'])) {
                ?>
                    <div class="row">
                        <div class="col-md-12 attachments-container">
                            <?php
                            if (is_image($route_maps['edition']['url'])) {
                            ?>
                                <h5>Event route map</h5>
                                <p>
                                    <img style="max-width: 100%;" src="<?= $route_maps['edition']['url']; ?>" title="<?= $edition_data['edition_name']; ?> Route Map" />
                                </p>
                            <?php
                            } else {
                                echo "<a href='" . $route_maps['edition']['url'] . "' class='attachment-box ripple-effect'>
                                            <span>" . $route_maps['edition']['text'] . "</span><i>" . strtoupper($route_maps['edition']['ext']) . "</i></a>";
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                if (isset($route_maps['race'])) {
                ?>
                    <div class="row">
                        <div class="col-md-12 attachments-container">
                            <?php
                            foreach ($route_maps['race'] as $race_map) {
                                if (is_image($race_map['url'])) {
                            ?>
                                    <h5><?= $race_map['text']; ?></h5>
                                    <p style="margin-bottom: 10px;">
                                        <img style="max-width: 100%;" src="<?= $race_map['url']; ?>" title="<?= $race_map['text']; ?>" style="border: 1px solid #333;" />
                                    </p>
                            <?php
                                } else {
                                    echo "<a href='" . $race_map['url'] . "' class='attachment-box ripple-effect'>
                                            <span>" . $race_map['text'] . "</span><i>" . $race_map['ext'] . "</i></a>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }

                // ROUTE PROFILE
                if (isset($route_profile['edition'])) {
                ?>
                    <div class="row">
                        <div class="col-md-12 attachments-container">
                            <?php
                            if (is_image($route_profile['edition']['url'])) {
                            ?>
                                <h4>Route Profile</h4>
                                <p>
                                    <img style="max-width: 100%;" src="<?= $route_profile['edition']['url']; ?>" title="<?= $edition_data['edition_name']; ?> Route Profile" />
                                </p>
                            <?php
                            } else {
                                echo "<a href='" . $route_profile['edition']['url'] . "' class='attachment-box ripple-effect'>
                                    <span>" . $route_profile['edition']['text'] . "</span><i>" . $route_profile['edition']['ext'] . "</i></a>";
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                if (isset($route_profile['race'])) {
                ?>
                    <div class="row">
                        <div class="col-md-12 attachments-container">
                            <?php
                            foreach ($route_profile['race'] as $race_map) {
                                if (is_image($race_map['url'])) {
                            ?>
                                    <h4><?= $race_map['text']; ?></h4>
                                    <p style="margin-bottom: 10px;">
                                        <img style="max-width: 100%;" src="<?= $race_map['url']; ?>" title="<?= $race_map['text']; ?>" style="border: 1px solid #333;" />
                                    </p>
                            <?php
                                } else {
                                    echo "<a href='" . $race_map['url'] . "' class='attachment-box ripple-effect'>
                                            <span>" . $race_map['text'] . "</span><i>" . $race_map['ext'] . "</i></a>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    <?php
    }
    ?>