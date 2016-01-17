<?php
/**
 * Package: timelab
 * Licence: GPL v2 or Later
 * Creator: dacendi
 * Date: 14/01/16
 */

include_once( FORM_MANAGER_PATH . "AFormManager.php");
include_once( ENTITIES_PATH ."Machine.php");

class MachineFormManager extends AFormManager {

    /**
     * Constants used to define HTML form ID and retrieve data from post
     */
    const MAC_FOR_NAME = 'addmodifymachine';
    const MAC_FOR_ID = 'machine';
    const MAC_FOR_TITLE = "machinetitle";
    const MAC_FOR_COD = "machineid";
    const MAC_FOR_FIRST_DATE = "machinefirstdate";
    const MAC_FOR_DESC = "machinedesc";

    public function __construct($post)
    {
        $this->buildMapping($post);
    }

    private function buildMapping($post)
    {
        // map form fields and values associated when $_POST is set for entity with entity properties
        $this->map(self::MAC_FOR_TITLE, "title", $post[self::MAC_FOR_TITLE]);
        $this->map(self::MAC_FOR_COD, "code", $post[self::MAC_FOR_COD]);
        $this->map(self::MAC_FOR_FIRST_DATE, "startDate", $post[self::MAC_FOR_FIRST_DATE]);
        $this->map(self::MAC_FOR_DESC, "description", $post[self::MAC_FOR_DESC]);

        // add rules for validation
        $this->addRule(self::MAC_FOR_TITLE, 'is_string');
        $this->addRule(self::MAC_FOR_TITLE, 'sanitize_text_field');

        var_dump($this->mapping);
    }

    public function buildMachine()
    {
        // construct new machine
        $this->objectInstance = new Machine();

        // load post data into machine instance & return. This instance is used in forms to retrieve data from database or after post submit
        $this->buildObject();
        return $this->objectInstance;
    }

    public function checkFields()
    {

    }

    private function buildChecking()
    {

    }

}