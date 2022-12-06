<?php
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