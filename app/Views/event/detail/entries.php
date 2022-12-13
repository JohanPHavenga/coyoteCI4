    <?php
    if (
        (detail_field_strlen($edition_data['edition_entry_detail'])) || 
        (!empty($edition_data['entrytype_list'])) && (!in_array(5, $edition_data['entrytype_list']))
    ) {
        if (
            isset($edition_data['entrytype_list'][4]) && // entrytype for online entries
            isset($url_list[5]) && // there is a url loaded for online entries
            isset($date_list[3][0]['date_start']) && // online entries open date exists
            (strtotime($date_list[3][0]['date_start']) < time())    // online entries date has passed
        ) {
            // check if entries has closed
            if (strtotime($date_list[3][0]['date_end']) < time()) {
                $btn_state = "gray";
                $btn_text = "Online entries closed";
                $icon = "icon-feather-minus-circle";
            } else {
                $btn_state = "";
                $btn_text = "Enter online";
                $icon = "icon-material-outline-arrow-right-alt";
            }
    ?>
            <p class="margin-bottom-20">
                <a href="<?= $url_list[5][0]['url_name']; ?>" class="button ripple-effect <?= $btn_state; ?>"><?= $btn_text; ?>
                    <i class="<?= $icon; ?>"></i></a>
            </p>
        <?php
        }
        ?>
        <ul>
            <?php
            // Online entries
            // check vir online entries
            if (isset($edition_data['entrytype_list'][4])) {
                // if open date is not equal to the race date
                if (strtotime($date_list[3][0]['date_start']) != strtotime($edition_data['edition_date'])) {
                    if (strtotime($date_list[3][0]['date_start']) > time()) {
                        echo "<li>Online entries will open on <b style='color: red;'>" . fdateEntries($date_list[3][0]['date_start'], true) . " </b>";
                    }
                } else {
                    echo "<li>Online entries will <b>open soon</b>";
                }
                // check vir closing date
                if (strtotime($date_list[3][0]['date_end']) != strtotime($edition_data['edition_date'])) {
                    $d = 's';
                    // check if already closed
                    if (strtotime($date_list[3][0]['date_end']) < time()) {
                        $d = "d";
                    }
                    echo "<li>Online entries close$d on <b style='color: red;'>" . fdateEntries($date_list[3][0]['date_end']) . "</b></li>";
                }
            } else {
                echo "<li><mark>No online entries available</mark></li>";
            }

            // OTD entries
            if (isset($edition_data['entrytype_list'][1])) {
                // check if time has been set
                if (strtotime($date_list[6][0]['date_start']) != strtotime($edition_data['edition_date'])) {
                    echo "<li>Entries will be taken <mark><b>on the day</b></mark> from <b>" .
                        ftimeMil($date_list[6][0]['date_start']);
                    if (!time_is_midnight($date_list[6][0]['date_end'])) {
                        echo " - " . ftimeMil($date_list[6][0]['date_end']);
                    }
                    echo "</b></li>";
                }
            } else {
                echo "<li><b><mark class='color'>No entries available on race day</mark></b>";
                if ($edition_data['edition_entry_funrun_otd']) {
                    echo " expect for the Fun Run";
                }
                echo "</li>";
            }

            // Manual entries
            if (isset($edition_data['entrytype_list'][2])) {
                // check if values are set
                if (strtotime($date_list[5][0]['date_end']) != strtotime($edition_data['edition_date'])) {
                    if (!empty($date_list[5][0]['venue_name'])) {
                        echo "<li>Pre-Entries can also be completed at " . $date_list[5][0]['venue_name'] . "</li>";
                    }
                    echo "<li>Closing date for manual pre-entries is <u>" . fdateEntries($date_list[5][0]['date_end'], true, true) . "</u></li>";
                }
            }

            // PRE entries
            if (isset($edition_data['entrytype_list'][3])) {
                // check if date is set, if closetime is not midnight and if venue is set
                if (
                    (strtotime($date_list[4][0]['date_start']) != strtotime($edition_data['edition_date'])) &&
                    (!time_is_midnight($date_list[4][0]['date_end'])) &&
                    (!empty($date_list[4][0]['venue_name']))
                ) {
                    echo "<li><b>Entries will be taken on:</b><ul>";
                    foreach ($date_list[4] as $date) {
                        echo "<li>" . fdateEntries($date['date_start'], true, true) . "-" . ftimeMil($date['date_end']) . " @ " . $date['venue_name'] . "</li>";
                    }
                    echo "</ul></li>";
                }
            }

            // ENTRY LIMIT
            if (!empty($edition_data['edition_entry_limit'])) {
                echo "<li><strong>NOTE</strong> that the entry limit for this event is <u>" . $edition_data['edition_entry_limit'] . " entrants</u></li>";
            }

            // ADMIN FEES
            if (!empty($edition_data['edition_admin_fee'])) {
                echo "<li>An admin fee of <strong>" . $edition_data['edition_admin_fee'] . "</strong> will be levied for " . $edition_data['edition_admin_option'] . " entries</li>";
            }

            // Entries Non-refundable
            if ($edition_data['edition_entry_nonrefund']) {
                echo "<li>Entry fees are non-refundable</li>";
            }

            // BULK entries
            if ($edition_data['edition_entry_bulk']) {
                echo "<li>For bulk entries (5+) please contact the organisers: "
                    . "<a href='mailto:" . $edition_data['user_email'] . "?subject=Bulk entries for " . $edition_data['edition_name'] . "' class='link'>"
                    . $edition_data['user_email'] . "</a></li>";
            }

            // Subsitutions
            if ($edition_data['edition_entry_nosubstitution']) {
                echo "<li><strong>No substitutions</strong>";
                if (strtotime($date_list[7][0]['date_end']) < strtotime($edition_data['edition_date'])) {
                    echo " after " . fdateEntries($date_list[7][0]['date_end'], true, true);
                }
                echo "</li>";
            }

            // Up/Downgrades
            if ($edition_data['edition_entry_nodowngrade']) {
                echo "<li><strong>No up- or downgrades will be entertained</strong>";
                if (strtotime($date_list[8][0]['date_end']) < strtotime($edition_data['edition_date'])) {
                    echo " after " . fdateEntries($date_list[8][0]['date_end'], true, true);
                }
                echo "</li>";
            }

            if (isset($edition_data['race_summary']['fees_per_race'])) {
                echo "<li><b>Race entry fees:</b>";
                echo "<ul>";
                foreach ($edition_data['race_summary']['fees_per_race'] as $race_fee) {
                    echo "<li>";
                    echo $race_fee['name'] . ": ";
                    if (array_key_exists("race_fee_flat", $race_fee['fees'])) {
                        echo "R<b>" . floatval($race_fee['fees']['race_fee_flat']) . "</b>";
                    } else {
                        echo "R<b>" . floatval($race_fee['fees']['race_fee_senior_licenced']) . "</b> - R<b>" . floatval($race_fee['fees']['race_fee_senior_unlicenced']) . "</b>";
                    }
                    echo "</li>";
                }
                echo "</ul></li>";
            }

            ?>
        </ul>
        <?php
        // always show what is in the box
        if (detail_field_strlen($edition_data['edition_entry_detail']) > 10) {
            echo $edition_data['edition_entry_detail'];
        }
    } else {
        ?>
        <div class="notification warning closeable">
            <p><b>No details</b> regarding the entries for this race has been published yet</p>
            <a class="close"></a>
        </div>
        <p>Want to get notified once entries open? Add yourself to the <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/subscribe"); ?>">mailing list</a> for the event.</p>
    <?php
    }
    if ((isset($tshirt['edition'])) || ($edition_data['edition_tshirt_amount'] > 0)) {
    ?>
        <div class="row margin-bottom-20">
            <div class="col-lg-12">
                <h4>Race T-Shirt</h4>
                <?php
                // TSHIRT
                if ($edition_data['edition_tshirt_amount'] > 0) {
                    echo "<ul>";
                    if (!empty($edition_data['edition_tshirt_text'])) {
                        echo "<li>T-Shirt <strong>R" . $edition_data['edition_tshirt_amount'] . "</strong>: " . $edition_data['edition_tshirt_text'] . "</li>";
                    } else {
                        echo "<li>An event <strong>T-Shirt</strong> is available for purchase as part of the entry process for <strong>R" . $edition_data['edition_tshirt_amount'] . "</strong></li>";
                    }
                    echo "</ul>";
                } elseif (!empty($edition_data['edition_tshirt_text'])) {
                    echo "<ul><li><b>T-Shirt</b>:  " . $edition_data['edition_tshirt_text'] . "</li></ul>";
                }
                ?>
            </div>
        </div>

    <?php
    }
    ?>

    <div class="row margin-bottom-20">
        <div class="col-lg-12 attachments-container">
            <?php
            if (isset($flyer['edition'])) {
            ?>
                <a href="<?= $flyer['edition']['url']; ?>" class="attachment-box ripple-effect">
                    <span><?= $flyer['edition']['text']; ?></span><i><?= strtoupper($flyer['edition']['ext']); ?></i></a>

            <?php
            }
            if (isset($entry_form['edition'])) {
            ?>
                <a href="<?= $entry_form['edition']['url']; ?>" class="attachment-box ripple-effect">
                    <span><?= $entry_form['edition']['text']; ?></span><i><?= strtoupper($entry_form['edition']['ext']); ?></i></a>

            <?php
            }
            if (isset($tshirt['edition'])) {
            ?>
                <a href="<?= $tshirt['edition']['url']; ?>" class="attachment-box ripple-effect">
                    <span><?= $tshirt['edition']['text']; ?></span><i><?= strtoupper($tshirt['edition']['ext']); ?></i></a>
            <?php
            }
            ?>
        </div>
    </div>