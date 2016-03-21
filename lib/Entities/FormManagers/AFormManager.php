<?php
/**
 * Package: timelab
 * Licence: GPL v2 or Later
 * Creator: dacendi
 * Date: 14/01/16
 */

abstract class AFormManager {

    const AF_MGR_MAPPING = "mapping";
    const AF_MGR_FIELD_CHECK = "fieldCheck";
    const AF_MGR_MAPPING_VALUE = "value";
    const AF_MGR_MAPPING_ENT_PROP = "property";
    const AF_MGR_FIELD_CHECK_EXP = "expected";
    const AF_MGR_FIELD_CHECK_ARGS = "args";

    /**
     * used to said that function arg is the data received
     */
    const INPUT_DATA = "AF_MGR_FIELD_CHECK_DATA_TAG";

    protected $mapping = [ self::AF_MGR_MAPPING => [], self::AF_MGR_FIELD_CHECK => []];

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
    protected function addRule($formId, callable $fctName, array $fctArgs = null, $valueExpected = null)
    {
        //add the rule with assotiated data into a new array under self::AF_MGR_FIELD_CHECK/$formid subtree.
        $this->mapping[self::AF_MGR_FIELD_CHECK][$formId][] = array(
            $fctName => array(
                self::AF_MGR_FIELD_CHECK_ARGS => $fctArgs,
                self::AF_MGR_FIELD_CHECK_EXP => $valueExpected
            )
        );
    }


    protected function checkFields()
    {
        echo "<br/>TODO: debug des règles<br/>";
        var_dump($this->mapping[self::AF_MGR_FIELD_CHECK]);

        // recup data
        foreach($this->mapping[self::AF_MGR_FIELD_CHECK] as $formId)
        {
            foreach($formId as $rulePosition => $rule)
            {
                // formId
                // get function name
                // get args
                // get value expected, casted if needed
                var_dump($rule);
            }
        }
        $this->isValid = true;
//todo: add security implementation
    }

    public function getValidation()
    {
        return $this->isValid;
    }

    /**
     * Perform validation calling methods for
     * @return bool validation status
     */
    public function validate()
    {
        try {
            $this->checkFields();
        }
        catch (Exception $e)
        {
            $this->isValid = false;
            echo $e->getCode() . " " . $e->getMessage();
        }
        return $this->getValidation();
    }

}