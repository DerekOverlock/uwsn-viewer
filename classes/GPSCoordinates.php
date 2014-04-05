<?php

class GPSCoordinates {
    const EARTH_RADIUS = 6378.137; // meters
    private $lat;
    private $lon;
    private $alt;

    public function __construct($lat, $lon, $alt) {
        $this->lat = $lat * pi()/180;
        $this->lon = $lon * pi()/180;
        $this->alt = $alt;
    }

    public function getX() {
        return (self::EARTH_RADIUS+$this->alt) * cos($this->lat) * cos($this->lon);
    }

    public function getY() {
        return (self::EARTH_RADIUS+$this->alt) * cos($this->lat) * sin($this->lon);
    }

    public function getZ() {
        return (self::EARTH_RADIUS+$this->alt) * sin($this->lat);
    }

    public function toString() {
        return $this->getX() . ", " . $this->getY() . ", " . $this->getZ();
    }
}