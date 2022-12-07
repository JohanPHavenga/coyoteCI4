<?php
function fdateHuman($date)
{
    return date("D j M", strtotime($date));
}

function fDateHumanShort($date)
{
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

function fdateMonth($date)
{
    return date("F", strtotime($date));
}
function fdateYear($date)
{
    return date("Y", strtotime($date));
}

function fdateEntries($date)
{
    $post_text = '';
    $date_str = "l, j F";
    if (time_is_almost_midnight($date)) {
        $post_text = " @ midnight";
    } elseif (!time_is_midnight($date)) {
        $date_str .= " @ H\hi";
    }
    return date($date_str, strtotime($date)) . $post_text;
}

function fdateTitle($date)
{
    return date("D, d M Y", strtotime($date));
}

// -- HELPERS

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
            $color = 'danger';
            break;

        case $distance == 10:
            $color = 'warning';
            break;

        case $distance <= 21:
            $color = 'secondary';
            break;

        case $distance == 21.1:
            $color = 'success';
            break;

        case $distance <= 42:
            $color = 'info';
            break;

        case $distance == 42.2:
            $color = 'primary';
            break;

        default:
            $color = 'dark';
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
