<?php

namespace Mare06xa\Beez\Classes;


class Location
{
    protected $locationType;
    protected $locationID;
    protected $registerID;

    /*
     * Location types
     */
    const MOVABLE_OBJECT    = "A";
    const FIXED_ADDRESS     = "B";
    const ELECTRONIC_DEVICE = "C";

    public function __construct($locationType = self::FIXED_ADDRESS, $locationID, $registerID)
    {
        $this->locationType = $locationType;
        $this->locationID   = $locationID;
        $this->registerID   = $registerID;
    }

    public function locationType($locationType)
    {
        $this->locationType = $locationType;

        return $this;
    }

    public function locationID($locationID)
    {
        $this->locationID = $locationID;

        return $this;
    }

    public function registerID($registerID)
    {
        $this->registerID = $registerID;

        return $this;
    }

    public function toString()
    {
        return
            "type="        . $this->locationType .
            "location_id=" . $this->locationID .
            "register_id=" . $this->registerID;
    }
}