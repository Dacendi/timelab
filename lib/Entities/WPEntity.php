<?php
/**
 * Created by PhpStorm.
 * User: Sébastien BAZAUD (alias Dacendi)
 * Date: 30/12/2015
 * Time: 15:02
 * Project: timelab
 */

abstract class WPEntity {

    protected $tableName;
    protected $wp_prefix;
    protected $wpdb;

    protected $id;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->wp_prefix = $this->wpdb->prefix;
    }
}