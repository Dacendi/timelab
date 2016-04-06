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
    const MAC_FOR_DESC = "machinedesc";
    const MAC_FOR_PIC = "machinepic";
    const MAC_FOR_SERIAL = "machineserial";
    const MAC_FOR_FIRST_DATE = "machinefirstdate";
    const MAC_FOR_LAST_DATE = "machinelastdate";
    const MAC_FOR_COMMENTS = "machinecomments";

    public function __construct($post)
    {
        $this->buildMapping($post);
    }

    private function buildMapping($post)
    {
        // Transformation steps on $_POST
        //TODO: sanitize and transform input data

        // map form fields and values associated with transformed data
        $this->map(self::MAC_FOR_TITLE, "title", $post[self::MAC_FOR_TITLE]);
        $this->map(self::MAC_FOR_COD, "code", $post[self::MAC_FOR_COD]);
        $this->map(self::MAC_FOR_DESC, "description", $post[self::MAC_FOR_DESC]);
        $this->map(self::MAC_FOR_PIC, "picture", $post[self::MAC_FOR_PIC]);
        $this->map(self::MAC_FOR_SERIAL, "serial", $post[self::MAC_FOR_SERIAL]);
        $this->map(self::MAC_FOR_FIRST_DATE, "startDate", $post[self::MAC_FOR_FIRST_DATE]);
        $this->map(self::MAC_FOR_LAST_DATE, "endDate", $post[self::MAC_FOR_LAST_DATE]);
        $this->map(self::MAC_FOR_COMMENTS, "comments", $post[self::MAC_FOR_COMMENTS]);

        // add rules for validation AFTER transformation of data (sanitization, replace...)
        //TODO: add rules for all fields
        $this->addRule(self::MAC_FOR_TITLE, 'isset', array(self::INPUT_DATA), true);
        $this->addRule(self::MAC_FOR_TITLE, 'is_string', array(self::INPUT_DATA), true);
        //$this->addRule(self::MAC_FOR_TITLE, 'sanitize_text_field', array(self::INPUT_DATA));
        //$this->addRule(self::MAC_FOR_TITLE, 'preg_replace', array('/[^a-z]/i', "", self::INPUT_DATA));
        //$this->addRule(self::MAC_FOR_COMMENTS, 'strlen', array(self::INPUT_DATA), '>100');

    }


    /**
     * Try to build a machine with pre-loaded data and build an error array if validation fails
     * @param $errorArray array the errors when the validation fails
     * @return Machine Empty Machine if validation fails, loaded Machine elsewhere
     * @throws ReflectionException
     */
    public function buildMachine(array &$errorArray)
    {
        // construct new machine
        $this->objectInstance = new Machine();

        // check input
        if ($this->validate($errorArray))
        {
            // load post data into machine instance & return. This instance is used in forms to retrieve data from database or after post submit
            $this->buildObject();
        }
        return $this->objectInstance;
    }


}