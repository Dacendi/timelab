<?php
/**
 * Package: timelab
 * Licence: GPL v2 or Later
 * Creator: dacendi
 * Date: 07/01/16
 */

class EntityManager {

    private $db;

    public function __construct()
    {
        global $wpdb;
        $this->db = $wpdb;
    }

}