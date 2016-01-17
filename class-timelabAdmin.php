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
     * 1.0 Code tags
     */

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
     * 2.0 Code Values
     */

    const STYLE_ADMIN = "machineAdminStyleForm";

    /**
     * 2.1 Slugs
     */

    const SUBMENU_SUPER_MACHINE_FUNC_SLUG = "TimelabAdminMenu";

    const SUBMENU_ADD_MACHINE_FUNC_SLUG = "tml_add_machine";


    private static $_initiated = false;

    public static function init()
    {
        if (! self::$_initiated)
        {
            // add admin stylesheet
            wp_register_style( self::STYLE_ADMIN , plugins_url('tml-admin-style.css', __FILE__ ), false, '1.1.0' );
            wp_enqueue_script('jquery');

            // set initiated state
            self::$_initiated = true;
        }
    }

    /**
     * add administration menu, submenu and pages
     */
    public static function add_admin_part()
    {
        $menuSlug = self::add_admin_menu();
        self::add_admin_subMenus($menuSlug);
        add_action('admin_enqueue_scripts', array(__CLASS__, "add_admin_scripts"));
    }

    public static function get_admin_section_content()
    {
        wp_enqueue_style( self::STYLE_ADMIN );
        include_once(TIMELAB_PLUGIN_DIR . "views/MachineList.php");
    }

    /**
     * fonctionne comme une action de contrôleur
     */
    public function loadMachinesAddTemplate()
    {
        wp_enqueue_style( self::STYLE_ADMIN );

        // récupère le contenu de $_POST si un formulaire a été soumis
        var_dump($_POST);

        include_once( FORM_MANAGER_PATH . "MachineFormMana.php");

        $macMgr = new MachineFormManager($_POST);

            // valide l'input
        $machine = $macMgr->buildMachine();

            // si validation ok, effectue la mise à jour et récupère le résultat

            // Charge les éléments de retour utilisateur: la recherche de machine ou l'erreur

        //charge la vue avec les retours utilisateur
        include_once(TIMELAB_PLUGIN_DIR . "views/MachineForm.php");
    }

    /**
     * Add the top level menu
     * @return string $menu_slug Parent unique slug to use for sub-menus
     */
    private static function add_admin_menu()
    {
        $page_title = __( "Machines" , 'timelab'); // TODO: traduction
        $menu_title = __( "Machines" , 'timelab'); // TODO: traduction
        $capability = "manage_options";
        $menu_slug = self::SUBMENU_SUPER_MACHINE_FUNC_SLUG;
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
                self::SUBMENU_PAGE_TITLE => __( "Add or modify machines" , 'timelab' ),
                self::SUBMENU_MENU_TITLE => __( "Add or modify machines" , 'timelab' ),
                self::SUBMENU_CAPABILITY => "manage_options",
                self::SUBMENU_SLUG  => self::SUBMENU_ADD_MACHINE_FUNC_SLUG,
                self::SUBMENU_FUNCTION => array("TimelabAdmin", "loadMachinesAddTemplate") //TODO : implémenter la page pour ajouter/modifier une machine
            ),
            array(
                self::SUBMENU_PAGE_TITLE => __( "Manage machines calendars" , 'timelab' ),
                self::SUBMENU_MENU_TITLE => __( "Manage machines calendars" , 'timelab' ),
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


    public static function add_admin_scripts($hook)
    {
        //if($hook == self::SUBMENU_ADD_MACHINE_FUNC_SLUG)
        wp_enqueue_script('check_machine_name-AJAX', TIMELAB_PLUGIN_WEB_JS_URL . "tml-addMachine.js", array('jquery'), "1.0.0", true);

        $title_nonce = wp_create_nonce('MachineNameCheck223.');

        wp_localize_script('check_machine_name-AJAX', 'my_ajax_obj', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => $title_nonce,
        ));

    }

    /**
     *
     */
    public static function tml_machine_name_handler()
    {
        check_ajax_referer('MachineNameCheck223.');

        try{
            include_once( UTILS_CLASS . "CodeGenerator.php");
            if( $_POST['firstWordOnly'] === "on")
                $firstWordOnly = true;
            else
                $firstWordOnly=false;

            $codeGenerator = new CodeGenerator($_POST['title'], 0, 8, 4, $firstWordOnly);
            $code = $codeGenerator->generateCode();
            echo $code;

            //$machineF = new MachineFactory();

        }
        catch(LogicException $le)
        {
            echo $le->getMessage();
        }
        finally
        {
            wp_die();
        }

    }
}