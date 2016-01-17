<?php
/**
 * Package: timelab
 * Licence: GPL v2 or Later
 * Creator: dacendi
 * Date: 27/12/15
 */

class TimelabUser {

    public function getRole ()
    {
        return wp_get_current_user()->get('');
    }
}