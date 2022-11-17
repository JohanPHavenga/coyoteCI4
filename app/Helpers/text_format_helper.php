<?php
    function fDateHumanShort ($date) {
        return date("d M", strtotime($date));
    }