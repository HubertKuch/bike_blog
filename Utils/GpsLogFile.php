<?php

namespace Hubert\BikeBlog\Utils;

use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Configuration\GpsFilesConfiguration;

#[Resource]
class GpsLogFile {

    #[Autowired]
    private GpsFilesConfiguration $configuration;

    public function __construct() {
    }


    /**
     * @param string $content Contents of .log gps file
     * @return array[] Returns a nested array of longitudes and latitudes like [[50.123, 50.21344], [50.12243, 50.2124]]
     * */
    public function parseToArray(string $content): array {
        $lines = explode(PHP_EOL, $content);
        $lines = array_filter($lines, fn($line) => str_starts_with($line, "\$GPGGA"));

        return array(...array_map(fn($line) => self::parseLine($line), $lines));
    }

    /**
     * @return float[] Return [latitude, longitude]
     * */
    private function parseLine(string $line): array {
        $elements = explode(",", $line);
        $latitude = $elements[2];
        $longitude = $elements[4];

        $latitudeDegrees = substr($latitude, 0, 2);
        $longitudeDegrees = substr($longitude, 0, 2);

        $latitudeMinutesAndSeconds = str_replace("0.", "", substr($latitude, 2) / 60);
        $longitudeMinutesAndSeconds = str_replace("0.", "", substr($longitude, 2) / 60);

        $isNorth = $elements[3] === "N";
        $isWest = $elements[5] === "W";

        $precision = $this->configuration->getLongitudeAndLatitudePrecision();

        $latitude = round((float)"$latitudeDegrees.$latitudeMinutesAndSeconds", $precision);
        $longitude = round((float)"$longitudeDegrees.$longitudeMinutesAndSeconds", $precision);

        return [$isNorth ? $latitude : -$latitude, $isWest ? -$longitude : $longitude];
    }

}