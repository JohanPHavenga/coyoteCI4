<?php
function my_encrypt($string)
{
    if (is_int($string)) {
        return urlencode(base64_encode($string + 7936181));
    } else {
        return urlencode(base64_encode($string . "7936181"));
    }
}

function my_decrypt($decrypt)
{
    $string = base64_decode(urldecode($decrypt));
    if (is_int($string)) {
        return $string - 7936181;
    } else {
        return substr($string, 0, -7);
    }
}
