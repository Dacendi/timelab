<?php
/**
 * Package: timelab
 * Licence: GPL v2 or Later
 * Creator: dacendi
 * Date: 14/01/16
 */

include_once( FORM_MANAGER_PATH . "FormCheckingException.php");

abstract class AFormManager {

    const AF_MGR_MAPPING = "mapping";
    const AF_MGR_FIELD_CHECK = "fieldCheck";
    const AF_MGR_MAPPING_VALUE = "value";
    const AF_MGR_MAPPING_ENT_PROP = "property";
    const AF_MGR_FIELD_CHECK_NAME = "name";
    const AF_MGR_FIELD_CHECK_EXP = "expected";
    const AF_MGR_FIELD_CHECK_ARGS = "args";

    /**
     * constants for checking mode
     * static: alert generated
     * flow: exclude data
     */
    const AF_MGR_FIELD_CHECK_MOD = 'checkingMode';
    const AF_MGR_FIELD_CHECK_MOD_FLOW = 'flow';
    const AF_MGR_FIELD_CHECK_MOD_STATIC = 'static';


    /**
     * used to said that function arg is the data received
     */
    const INPUT_DATA = "AF_MGR_FIELD_CHECK_DATA_TAG";

    protected $mapping = [ self::AF_MGR_MAPPING => [], self::AF_MGR_FIELD_CHECK => []];

    private $alerts;

    protected $objectInstance;

    protected $isValid = false;

    protected $token;

    public function __construct()
    {

    }

    public function persist()
    {

    }

    /**
     * add a field mapping between HTML Form and entity data
     * @param string $formId html input identifier
     * @param string $entProp name of entity property. Must be declared with setter and getter for $objectInstance entity
     * @param mixed $value value for entity property (value associated with $formId key in $_POST...)
     * // todo : add possibility to transform data between form and entity
     */
    protected function map($formId, $entProp, $value=null)
    {
        // create mapping instance
        $this->mapping[self::AF_MGR_MAPPING][$formId] = array(
            self::AF_MGR_MAPPING_ENT_PROP => $entProp,
            self::AF_MGR_MAPPING_VALUE => $value
        );
        // create field check instance
        $this->mapping[self::AF_MGR_FIELD_CHECK][$formId] = array();
    }

    /**
     * build $objectInstance with mapping data
     * @throws ReflectionException When setters or getters not defined into entity
     */
    protected function buildObject()
    {
        // set data for all properties of object
        foreach ($this->mapping[self::AF_MGR_MAPPING] as $formId => $entityIf)
        {
            // build setter name
            $fctName = "set" . ucfirst($entityIf[self::AF_MGR_MAPPING_ENT_PROP]);
            if ( method_exists($this->objectInstance, $fctName))
                $this->objectInstance->{$fctName}($entityIf[self::AF_MGR_MAPPING_VALUE]);
            else {// throw reflection exception if setter isn't known,
                throw new ReflectionException("You have to check your mapping (property name) or your entity ! The setter $fctName doesn't exists for entity ". __CLASS__ );
            }
        }
    }

    /**
     * Add a rule in list for field validation.
     * Rule is defined by a callable function with optional args used to compare with a value or die.
     * @param string $formId Unique identifier of form used to collect data and rules
     * @param callable $fctName Function or method that return a value or raise exception.
     * @param array $fctArgs optional arguments passed to callable function $fctName
     * @param null $valueExpected In case of function, the value that will be returned for success. Specify cast operation within parenthesis if needed. Function will try that
     */
    protected function addRule($formId, $fctName, array $fctArgs = null, $valueExpected = null, $checkingMode = AFormManager::AF_MGR_FIELD_CHECK_MOD_FLOW)
    {
        // check if arg 2 is a string for a callable or isset or empty
        if(!is_string($fctName) || (! is_callable($fctName) && $fctName != 'isset' && $fctName != 'empty'))
            throw new BadFunctionCallException("Parameter 2 must be a string defining a callable or isset, empty");


        //add the rule with assotiated data into a new array under self::AF_MGR_FIELD_CHECK/$formid subtree.
        $this->mapping[self::AF_MGR_FIELD_CHECK][$formId][] = array(
                self::AF_MGR_FIELD_CHECK_NAME => $fctName,
                self::AF_MGR_FIELD_CHECK_ARGS => $fctArgs,
                self::AF_MGR_FIELD_CHECK_EXP => $valueExpected,
                self::AF_MGR_FIELD_CHECK_MOD => $checkingMode
        );
    }

    /**
     * Perform rules checking defined with AFormManager::addRule()
     * @return array list of KO rules-field
     */
    protected function checkFields()
    {
        // define local variables
        // fail checks array
        $failedChecks = array();

        // read the mapping array with a 3D loop... and try to execute each rules
        foreach($this->mapping[self::AF_MGR_FIELD_CHECK] as $fieldName => $fieldFormId)
        {
            foreach($fieldFormId as $rulePosition => $rule)
            {
                // replace const self::AF_MGR_FIELD_CHECK_ARGS by data if found
                foreach ($rule[self::AF_MGR_FIELD_CHECK_ARGS] as $argPos => $argValue)
                {
                    if ($argValue == self::INPUT_DATA )
                        $rule[self::AF_MGR_FIELD_CHECK_ARGS][$argPos] = $this->getMappingValue($fieldName);
                }// end loop 3

               // try to run the test
                try
                {
                    // allow use of isset and empty out of call_user_func
                    if ($rule[self::AF_MGR_FIELD_CHECK_NAME]=='isset')
                        $result = isset($rule[self::AF_MGR_FIELD_CHECK_ARGS][0]);
                    elseif ($rule[self::AF_MGR_FIELD_CHECK_NAME]=='empty')
                        $result = empty($rule[self::AF_MGR_FIELD_CHECK_ARGS][0]);
                    else
                        $result = call_user_func_array($rule[self::AF_MGR_FIELD_CHECK_NAME], $rule[self::AF_MGR_FIELD_CHECK_ARGS]);

                    // try to cast de result type to
                    if ((gettype($result) == gettype($rule[self::AF_MGR_FIELD_CHECK_EXP]) ) && $result != $rule[self::AF_MGR_FIELD_CHECK_EXP])
                    {
                        $exp = new FormCheckingException("Error when trying to check the rule \"".$rule[self::AF_MGR_FIELD_CHECK_NAME]. "\" on field \"". $fieldName.'". Expected: '. (string) $rule[self::AF_MGR_FIELD_CHECK_EXP] . " got :". (string) $result, -1);
                        throw $exp;
                    }

                }
                catch (Exception $e)
                {
                    // store rule exception and go to next
                    $failedChecks[] = $e;
                }

            }// end loop 2
        }//end loop 1

        // returns an array of all caught exceptions
        return $failedChecks;
    }

    /**
     * Retrieve the loaded data
     * @param string $fieldFormId form identifier
     * @return mixed value loaded
     */
    protected function getMappingValue($fieldFormId)
    {
        return $this->mapping[self::AF_MGR_MAPPING][$fieldFormId][self::AF_MGR_MAPPING_VALUE];
    }



    public function validate(&$errorTable = null)
    {
        // do checkings if form is not valid and complete error array if not valid
        $this->performValidation();
        if (isset($errorTable))
            $errorTable = $this->alerts;
        return $this->isValid;
    }


    /**
     * perform validation and load assiated properties: $alert and $isValid
     */
    private function performValidation()
    {
        // perform validation and catch errors in alert property
        $this->alerts = $this->checkFields();
        $this->isValid = empty($this->alerts); // if alerts is empty: it's OK !
    }

    /**
     * @return array $alerts Array of Exceptions generated during checking step
     */
    public function getAlerts()
    {
        return $this->alerts;
    }

}