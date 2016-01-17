<?php
/**
 * Created by PhpStorm.
 * User: dacendi
 * Date: 26/12/15
 * Time: 17:44
 */

class Timelab {

    const tml_version_name = "tml_db_version";

    private static $is_initiated = false;

    public static function init()
    {
        if ( ! self::$is_initiated )
        {
            if (is_admin())
            {
                require_once('class-timelabAdmin.php');
                add_action( 'admin_menu', "TimelabAdmin::init");
                add_action( 'admin_menu', "TimelabAdmin::add_admin_part");
            }
            self::$is_initiated = true;
        }
    }

    /**
     * Attached to activate_{ plugin_basename( __FILES__ ) } by register_activation_hook()
     * @static
     */
    public static function plugin_activation() {
        if ( version_compare( $GLOBALS['wp_version'], TIMELAB_MINIMUM_WP_VERSION, '<' ) ) {
            $message = sprintf(esc_html__('TimeLab %s requires WordPress %s or higher.', 'timelab'), TIMELAB_VERSION, TIMELAB_MINIMUM_WP_VERSION) . sprintf(__(' Please <a href="%1$s">upgrade WordPress</a> to a current version.', 'timelab'), 'https://codex.wordpress.org/Upgrading_WordPress');
            self::bail_on_activation( $message );
        }

        if ( get_option(self::tml_version_name) === "" or version_compare( TIMELAB_DB_VERSION, get_option(self::tml_version_name), '>' ))
            self::install_tables();
    }

    public static function plugin_deactivation()
    {

    }

    /**
     * manage plugin activation if activation fails
     * @param string $message message to display
     * @param bool $deactivate default: deactivate the plugin if activation fails.
     */
    private static function bail_on_activation( $message, $deactivate = true ) {
        ?>
        <!doctype html>
        <html>
        <head>
            <meta charset="<?php bloginfo( 'charset' ); ?>">
            <style>
                * {
                    text-align: center;
                    margin: 0;
                    padding: 0;
                    font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
                }
                p {
                    margin-top: 1em;
                    font-size: 18px;
                }
            </style>
        <body>
        <p><?php echo esc_html( $message ); ?></p>
        </body>
        </html>
        <?php
        if ( $deactivate ) {
            $plugins = get_option( 'active_plugins' );
            $timelab = plugin_basename( TIMELAB_PLUGIN_DIR . 'timelab.php' );
            $update  = false;
            foreach ( $plugins as $i => $plugin ) {
                if ( $plugin === $timelab ) {
                    $plugins[$i] = false;
                    $update = true;
                }
            }

            if ( $update ) {
                update_option( 'active_plugins', array_filter( $plugins ) );
            }
        }
        exit;
    }

    /**
     * Install the pluging tables
     * @version 0.1
     */
    private static function install_tables()
    {
        // call of wp db object
        global $wpdb;

        // db settings
        $charset_collate = $wpdb->get_charset_collate();

        // names of tables
        $machineTableName = $wpdb->prefix . "machine";
        $providerTableName = $wpdb->prefix . "machine_provider";
        $machineCalendarTableName = $wpdb->prefix . "machine_calendar";

        // create queries
        // machine
        $sqlMachine = "CREATE TABLE $machineTableName (
                title       varchar(255),
                id			smallint(10) unsigned auto_increment not null primary key,
	            code		char(8) not null unique,
	            description	MEDIUMTEXT,
	            picture		varchar(255),
	            id_mark	    smallint(10) unsigned not null,
	            serial		varchar(255),
	            start_date	datetime,
	            end_date	datetime,
	            comments	varchar(255),
	            more_info	varchar(255),
	            id_type_machine	smallint(10) unsigned not null
	            ) $charset_collate;";

        // machine providers
        $sqlMachineProvider = "CREATE TABLE $providerTableName (
                id			        smallint(10) unsigned auto_increment not null primary key,
	            short_tag	        varchar(50) not null,
	            long_tag	        varchar(255),
	            address_first_line  varchar(100),
	            address_sec_line    varchar(100),
	            address_num         varchar(10),
	            address_street_name varchar(100),
	            address_postal_code varchar(10),
	            address_city        varchar(100),
	            address_state       varchar(100),
	            address_country     varchar(100),
	            website_url         varchar(255),
	            contact_first       varchar(100),
	            contact_first_phone varchar(20),
	            contact_first_mail  varchar(100),
	            contact_sec         varchar(100),
	            contact_sec_phone   varchar(20),
	            contact_sec_mail    varchar(100)
	            ) $charset_collate;";

        // machine calendar
        $sqlMachineCalendar = "CREATE TABLE $machineCalendarTableName (
                id			                  smallint(10) unsigned auto_increment not null primary key,
	            code		                  char(11) not null unique,
	            id_machine                     smallint(10) unsigned not null,
	            booking_status                smallint(2) unsigned,
	            booking_recurrence_start_date datetime,
	            booking_recurrence_end_date   datetime,
	            booking_recurrence_period     timestamp,
	            booking_recurrence_duration   timestamp,
	            booking_comment               varchar(255),
	            booking_creation_date         datetime NOT NULL,
	            booking_payment_date          datetime,
	            id_user                       bigint(20) unsigned not null
	            ) $charset_collate;";

        // process queries
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sqlMachine );
        dbDelta( $sqlMachineProvider );
        dbDelta( $sqlMachineCalendar );

        // add the db_version to wordpress
        add_option( self::tml_version_name , TIMELAB_DB_VERSION );
    }
}