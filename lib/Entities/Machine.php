<?php
/**
 * Created by PhpStorm.
 * User: Sébastien BAZAUD (alias Dacendi)
 * Date: 30/12/2015
 * Time: 14:59
 * Project: timelab
 */

include_once "WPEntity.php";

class Machine extends WPEntity
{
    const machine_table_name = "machine";

    /**
     * @var string Title of machine - VARCHAR 255
     */
    private $title;

    /**
     * @var string Code identifier for classification - CHAR 10 - NOT NULL
     */
    private $code ;

    /**
     * @var string Description of machine - MEDIUMTEXT
     */
    private $description ;

    /**
     * @var string Url of machine picture - VARCHAR 255
     */
    private $picture ;

    /**
     * @var int Mark Id - SMALLINT 10 - NOT NULL
     */
    private $idMark ;

    /**
     * @var string machine serial number - VARCHAR 255
     */
    private $serial ;

    /**
     * @var string first datetime which machine is available to users. --  with database default date formatted text
     */
    private $startDate ;

    /**
     * @var string Last datetime which machine is available to users. --  with database default date formatted text
     */
    private $endDate ;

    /**
     * @var string Comment for machine. Displayed to users - VARCHAR 255
     */
    private $comments ;

    /**
     * @var string Link to more information about machine - VARCHAR 255
     */
    private $moreInfo ;

    /**
     * @var int ID for Type of machine relation - SMALLINT 10 - NOT NULL
     */
    private $machineType;

    public function __construct()
    {
        parent::__construct();
        $this->tableName = $this->wp_prefix . self::machine_table_name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param string $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return int
     */
    public function getIdMark()
    {
        return $this->idMark;
    }

    /**
     * @param int $idMark
     */
    public function setIdMark($idMark)
    {
        $this->idMark = $idMark;
    }

    /**
     * @return string
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param string $serial
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param string $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param string $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param string $comments
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return string
     */
    public function getMoreInfo()
    {
        return $this->moreInfo;
    }

    /**
     * @param string $moreInfo
     */
    public function setMoreInfo($moreInfo)
    {
        $this->moreInfo = $moreInfo;
    }

    /**
     * @return int
     */
    public function getMachineType()
    {
        return $this->machineType;
    }

    /**
     * @param int $machineType
     */
    public function setMachineType($machineType)
    {
        $this->machineType = $machineType;
    }

    /**
     * @return string title of machine
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title title of machine
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }




}