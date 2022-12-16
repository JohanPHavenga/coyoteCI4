<?php
function fdateHuman($date)
{
    if ($date == "TBC") {
        return $date;
    }
    return date("D j M", strtotime($date));
}

function fDateHumanShort($date)
{
    if ($date == "TBC") {
        return $date;
    }
    return date("d M", strtotime($date));
}

function fdateShort($date = null)
{
    if (empty($date)) {
        return date("Y-m-d");
    } else {
        return date("Y-m-d", strtotime($date));
    }
}
function fdateHumanFull($date, $show_dotw = false, $show_time = false)
{
    if ($date == "TBC") {
        return $date;
    }
    $date_str = "j F Y";
    if ($show_dotw) {
        $date_str = "l, " . $date_str;
    }
    if ($show_time) {
        if (!time_is_midnight($date)) {
            $date_str .= " H:i";
        }
    }
    return date($date_str, strtotime($date));
}

function fdateMonth($date)
{
    if ($date == "TBC") {
        return $date;
    }
    return date("F", strtotime($date));
}

function fdateYear($date)
{
    if ($date == "TBC") {
        return $date;
    }
    return date("Y", strtotime($date));
}

function fdateEntries($date)
{
    if ($date == "TBC") {
        return $date;
    }
    $post_text = '';
    $date_str = "l, j F";
    if (time_is_almost_midnight($date)) {
        $post_text = " @ midnight";
    } elseif (!time_is_midnight($date)) {
        $date_str .= " @ H\hi";
    }
    return date($date_str, strtotime($date)) . $post_text;
}

function ftimeSort($time, $show_sec = false)
{
    if ($show_sec) {
        return date("H:i:s", strtotime($time));
    } else {
        return date("H:i", strtotime($time));
    }
}

function ftimeMil($time)
{
    return date("H\hi", strtotime($time));
}

function fdateToCal($timestamp)
{
    return date('Ymd\THis', $timestamp);
}

function fdateStructured($timestamp)
{
    return date('Y-m-d\TH:i:s' . '+02:00', strtotime($timestamp));
}

function fdateTitle($date)
{
    if ($date == "TBC") {
        return $date;
    }
    return date("D, d M Y", strtotime($date));
}

function fdisplayCurrency($amount, $des = 0)
{
    return "R" . number_format($amount, $des, '.', '');
}

function fraceDistance($distance, $small = false)
{
    if ($distance == 1.6) {
        $dist = "1";
        $denom = "mile";
    } else {
        $dist = floatval($distance);
        $denom = "km";
    }

    if ($small) {
        return $dist . "<small>" . $denom . "</small>";
    } else {
        return $dist . $denom;
    }
}

function int_phone($phone)
{
    $phone = trim($phone);
    $phone = str_replace(" ", "", $phone);
    $phone = str_replace("-", "", $phone);
    return preg_replace('/^(?:\+?27|0)?/', '+27', $phone);
}


function fphone($phone)
{
    $phone = int_phone($phone);
    $int_code = substr($phone, 0, 3);
    $code = substr($phone, 3, 2);
    $first_3 = substr($phone, 5, 3);
    $last_4 = substr($phone, 8, 4);
    $new_phone = $int_code . " (0)" . $code . " " . $first_3 . " " . $last_4;
    return $new_phone;
}

// -- HELPERS
function detail_field_strlen($field)
{
    if ($field) {
        if (strlen($field) > 10) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function convert_seconds($seconds)
{
    $dt1 = new DateTime("@0");
    $dt2 = new DateTime("@$seconds");
    //    return $dt1->diff($dt2)->format('%a days, %h hours, %i minutes and %s seconds');
    return $dt1->diff($dt2)->format('%a');
}

function move_to_top(&$array, $key)
{
    $temp = array($key => $array[$key]);
    unset($array[$key]);
    $array = $temp + $array;
}

function move_to_bottom(&$array, $key)
{
    $value = $array[$key];
    unset($array[$key]);
    $array[$key] = $value;
}

function time_is_midnight($date)
{
    $time = date("H:i", strtotime($date));
    if ($time == "00:00") {
        return true;
    } else {
        return false;
    }
}

function time_is_almost_midnight($date)
{
    $time = date("H:i", strtotime($date));
    if (($time == "23:55") || ($time == "23:59")) {
        return true;
    } else {
        return false;
    }
}

function race_color($distance)
{
    switch (true) {
        case $distance <= 9:
            $color = '#CE041C';
            break;

        case $distance == 10:
            $color = '#ffb20e';
            break;

        case $distance <= 21:
            $color = '#5A6268';
            break;

        case $distance == 21.1:
            $color = '#81c868';
            break;

        case $distance <= 42:
            $color = '#53b0f8';
            break;

        case $distance == 42.2:
            $color = '#2250fc';
            break;

        default:
            $color = '#000';
            break;
    }
    return $color;
}

function get_date_list()
{
    $dates_to_fetch = [
        "1 month ago",
        "today",
        "+1 month",
        "+2 month",
        "+3 month",
        "+4 month",
        //            "+5 month",
    ];
    foreach ($dates_to_fetch as $strtotime) {
        $date_list[date("Y", strtotime($strtotime))][date("m", strtotime($strtotime))] = date("F Y", strtotime($strtotime));
    }
    return $date_list;
}

function time_to_sec($time)
{
    if ($time) {
        $sec = 0;
        foreach (array_reverse(explode(':', $time)) as $k => $v) {
            $sec += pow(60, $k) * $v;
        }
        return $sec;
    } else {
        return false;
    }
}

function is_image($path)
{
    $extension = pathinfo($path, PATHINFO_EXTENSION);

    $extensions = array('jpeg', 'jpg', 'png', 'gif', 'webp');
    if (in_array($extension, $extensions)) {
        return true;
    } else {
        return false;
    }
}
