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

    protected $mapping = [ self::AF_MGR_MAPPING => [], self::AF_MGR_FIELD_CHECK => []];

    protected $objectInstance;

    protected $isValidated = false;

    /**
     * add a field mapping between HTML Form and entity data
     * @param string $formId html input identifier
     * @param string $entProp name of entity property. Must be declared with setter and getter for $objectInstance entity
     * @param mixed $value value for entity property (value associated with $formId key in $_POST...)
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
        foreach ($this->mapping[self::AF_MGR_MAPPING] as $formId => $entityIf)
        {
            $fctName = "set" . ucfirst($entityIf[self::AF_MGR_MAPPING_ENT_PROP]);
            if ( method_exists($this->objectInstance, $fctName))
                $this->objectInstance->{$fctName}($entityIf[self::AF_MGR_MAPPING_VALUE]);
            else {
                throw new ReflectionException("Function $fctName doesn't exists for entity ". __CLASS__ );
            }
        }
    }

    protected function addRule($formId, $fctName)
    {
        if (is_callable($fctName))
            $this->mapping[self::AF_MGR_FIELD_CHECK][$formId][] = $fctName;
        else
            throw new ReflectionException($fctName . " is not callable of defined. Check validity of rule.");
    }

}