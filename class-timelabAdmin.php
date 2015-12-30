<?php
/**
 * Created by PhpStorm.
 * User: Sébastien
 * Date: 30/12/2015
 * Time: 12:37
 */

/**
 * Class TimelabAdmin Master class for administration of timelab plugin
 */
class TimelabAdmin {

    /**
     * tag for submenu page title displayed to user
     */
    const SUBMENU_PAGE_TITLE = 'page_title';

    /**
     * tag for submenu menu title
     */
    const SUBMENU_MENU_TITLE = 'menu_title';

    /**
     * tag for submenu capabilities
     */
    const SUBMENU_CAPABILITY = 'capability';

    /**
     * tag for submenu slug
     */
    const SUBMENU_SLUG = 'menu_slug';

    /**
     * tag for submenu callback function
     */
    const SUBMENU_FUNCTION = 'function';

    /**
     * add administration menu, submenu and pages
     */
    public static function add_admin_part()
    {
        $menuSlug = self::add_admin_menu();
        self::add_admin_subMenus($menuSlug);
    }

    public static function get_admin_section_content()
    {
        echo "Page d'administration des machines"; // TODO: traduction
    }

    /**
     * Add the top level menu
     * @return string $menu_slug Parent unique slug to use for sub-menus
     */
    private static function add_admin_menu()
    {
        $page_title = "Administrate engines"; // TODO: traduction
        $menu_title = "Engines";// TODO: traduction
        $capability = "manage_options";
        $menu_slug = "TimelabAdminMenu";
        $function = array("TimelabAdmin", "get_admin_section_content");
        $icon_url = "dashicons-admin-plugins";
        $position = "21";

        add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );

        return $menu_slug;
    }

    /**
     * @param string $parent_slug the parent slug for sub-menus attachment
     */
    private static function add_admin_subMenus($parent_slug)
    {
        // sub-menu data
        $subMenusArray = array(
            array(
                self::SUBMENU_PAGE_TITLE => "Add or modify engines",// TODO: traduction
                self::SUBMENU_MENU_TITLE => "Add or modify engines",// TODO: traduction
                self::SUBMENU_CAPABILITY => "manage_options",
                self::SUBMENU_SLUG  => "tml_add_engine",
                self::SUBMENU_FUNCTION => array("TimelabAdmin", "get_admin_section_content") //TODO : implémenter la page pour ajouter/modifier une machine
            ),
            array(
                self::SUBMENU_PAGE_TITLE => "Manage engines calendars",// TODO: traduction
                self::SUBMENU_MENU_TITLE => "Manage engines calendars",// TODO: traduction
                self::SUBMENU_CAPABILITY => "manage_options",
                self::SUBMENU_SLUG  => "tml_manage_calendars",
                self::SUBMENU_FUNCTION => array("TimelabAdmin", "get_admin_section_content") // TODO: implémenter la page pour gérer le calendrier de la machine
            ),
        );

        // add each sub-menus to the parent slug...
        foreach ($subMenusArray as $submenu)
            add_submenu_page(
                $parent_slug,
                $submenu[self::SUBMENU_PAGE_TITLE],
                $submenu[self::SUBMENU_MENU_TITLE],
                $submenu[self::SUBMENU_CAPABILITY],
                $submenu[self::SUBMENU_SLUG],
                $submenu[self::SUBMENU_FUNCTION]
            );
    }
}