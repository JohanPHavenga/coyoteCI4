<?php
$e_attendance_mode = "OfflineEventAttendanceMode";
$loc_type = "Place";
switch ($edition_data['edition_status']) {
    case 3:
        $s_name = "EventCancelled";
        break;
    case 9:
        $s_name = "EventPostponed";
        break;
    case 17:
        $s_name = "EventMovedOnline";
        $e_attendance_mode = "OnlineEventAttendanceMode";
        $loc_type = "VirtualLocation";
        break;
    default:
        $s_name = "EventScheduled";
        break;
}

$id = $edition_data['edition_id'];
$start_date = fdateShort($edition_data['edition_date']);
//$start_date=fdateShort($edition_data['date_list'][1][$id]['date_start']); // top one TBR
$end_date = fdateShort($date_list[1][0]['date_end']);
$today_date = date("Y-m-d") . 'T' . "00:00:00+02:00";

// Online entries
if (isset($date_list[3])) {
    $valid_from_date = fdateStructured($date_list[3][0]['date_start']);
    $valid_to_date = fdateStructured($date_list[3][0]['date_end']);
    $avail = '"availability": "https://schema.org/InStock"';
    // as dit nog moet oop maak
    // hierdie veroorsaak 'n error
    // if (strtotime($date_list[3][0]['date_start']) > time()) {
    //     $avail = '"validFrom": "' . fdateStructured($date_list[3][0]['date_start']) . '"';
    // }
    // reeds toegemaak
    if (strtotime($date_list[3][0]['date_end']) < time()) {
        $avail = '"availability": "https://schema.org/SoldOut"';
    }
} else {
    // past
    if (strtotime($date_list[1][0]['date_end']) < time()) {
        $avail = '"availability": "https://schema.org/SoldOut"';
        $valid_from_date = $start_date . 'T' . "08:00:00+02:00";
        $valid_to_date = $end_date . 'T' . "08:00:00+02:00";
    } else {
        $avail = '"availability": "https://schema.org/InStock",';
        $valid_from_date = fdateStructured(date("Y-m-d"));
        $valid_to_date = $end_date . 'T' . "08:00:00+02:00";
    }
}

//wts($edition_data, 1);
//wts($date_list[3]);
//wts($url_list,1);


if ($edition_data['edition_intro_detail']) {
    $description = strip_tags($edition_data['edition_intro_detail']);
} else {
    $description = "Annual " . $edition_data['event_name'] . " road running event in " . $edition_data['town_name'] . ", " . $edition_data['province_name'] . ".";
}
if (isset($file_list[1])) {
    $img_url = base_url("file/edition/" . $edition_data['edition_slug']) . "/logo/" . $file_list[1][0]['file_name'];
} else {
    $img_url = '';
}
?>

<script type="application/ld+json">
    {
    "@context": "http://schema.org",
    "@type": "SportsEvent",
    "name": "<?= $edition_data['edition_name']; ?>",
    "eventStatus": "https://schema.org/<?= $s_name; ?>",
    "startDate": "<?= $start_date; ?>",
    "endDate": "<?= $end_date; ?>",
    "eventAttendanceMode" : "<?= $e_attendance_mode; ?>",
    "location": { 
    "@type": "<?= $loc_type; ?>",    
    <?php if ($edition_data['edition_status'] != 17) { ?>
        "address": { 
        "@type": "PostalAddress",
        "streetAddress": "<?= $edition_data['edition_address_end']; ?>",
        "addressLocality": "<?= $edition_data['town_name']; ?>",
        "addressRegion": "WC",
        "addressCountry": "ZA"
        }
    <?php } else { ?>
        "url" : "<?= base_url("event" . $edition_data['edition_slug']); ?>"
    <?php } ?>
    },
    <?php if ($edition_data['club_id'] != 8) { ?>
        "organizer": { 
        "@type" : "Organization",
        "name" : "<?= $edition_data['club_name']; ?>"
        <?php if (isset($edition_data['club_url_list'][0])) { ?>
            ,"url" : "<?= $edition_data['club_url_list'][0]['url_name']; ?>"
        <?php } ?>
        },
    <?php } ?>
    "description": "<?= htmlspecialchars($description); ?>",    
    "image": "<?= $img_url; ?>",     
    "offers": [

    <?php
    foreach ($race_list as $race) {
        // race name
        if (!empty($race['race_name'])) {
            $rn = $race['race_name'];
        } else {
            $rn = fraceDistance($race['race_distance']) . " " . $race['racetype_name'];
        }
        // race fee
        if ($race['race_fee_flat'] > 0) {
            $price = $race['race_fee_flat'];
        } elseif ($race['race_fee_senior_licenced'] > 0) {
            $price = $race['race_fee_senior_licenced'];
        } else {
            $price = 0;
        }
        // get date
        if ($race['race_date'] > 0) {
            $race_start_date = date("Y-m-d", strtotime($race['race_date']));
        } else {
            $race_start_date = $start_date;
        }
        ?>
        {
        "@type": "Offer",
        "description": "<?= $rn; ?>"
        <?php if ($price > 0) { ?>
            ,"price": "<?= $price; ?>",
            "priceCurrency": "ZAR",
            <?php
            if ((isset($date_list[3])) && (isset($url_list[5]))) {
                $url = $url_list[5][0]['url_name'];
                ?>
                "url": "<?= $url; ?>",
                "validFrom": "<?= $valid_from_date; ?>",
                "validThrough": "<?= $valid_to_date; ?>",
                <?= $avail; ?>
            <?php } else {
                ?>
                "availability": "http://schema.org/InStoreOnly"
                <?php
            }
        }
        if ($race === end($race_list)) {
            echo "}";
        } else {
            echo "},";
        }
    } // end foreach
    ?>

    ]

    }
</script>
