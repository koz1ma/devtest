<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    private $path = '../storage/app/';
    //private $path = '/app/storage/app/';
    private $officeLocation = [53.3340285, -6.2535495];
    public function getAffiliates($filename = 'affiliates.txt'){
        $affiliates = [];
        //open file
        try {
            $file = fopen($this->path.$filename, "r");
        }
        catch (Exception $e) {
            echo "File not found on ".getcwd( ).".";
        }

        //read file line by line and convert from json to array
        while (($line = fgets($file)) !== false) {
            $row = (array)json_decode($line);
            $affiliates[$row['affiliate_id']] = $row;
        }
        fclose($file);
        // sort array by id ASC
        ksort($affiliates); 
        // call function to search nearbyAffiliates
        return $this->getNearbyAffiliates($affiliates); 
    }

    private function getNearbyAffiliates($affiliates, $distance = '100'){
        $nearby = [];
        foreach($affiliates as $a){
            // calculate distance between each affiliate and our office
            if($this->gcd([$a['latitude'],$a['longitude']],$this->officeLocation)<$distance){
                $result['affiliate_id'] = $a['affiliate_id'];
                $result['name'] = $a['name'];
                $nearby[] = $result;
            }
        }
        return json_encode($nearby);
    }
    
    //this function returns the distance in Km
    //point structure = [lat,lon]
    private function gcd($point1, $point2){
        if(!isset($point1) || !isset($point2)){
            throw new Exception("Missing parameters.");
        }
        //Great-circle distance formula
        $theta = $point1[1] - $point2[1];
        $dist = sin(deg2rad($point1[0])) * sin(deg2rad($point2[0])) +  cos(deg2rad($point1[0])) * cos(deg2rad($point2[0])) * cos(deg2rad($theta));

        //this is to avoid to cause NaN errors when rounding $dist get bigger than 1 or less than -1 
        $dist = acos(min(max($dist,-1.0),1.0)); 
        $dist = rad2deg($dist);
        //distance in Km
        //number of minutes in a degree:60
        //number of statute miles in a nautical mile:1.1515 - One nautical mile is the length of one minute of latitude
        //number of kilometers in a mile: 1.609344
        $distance = ($dist * 60 * 1.1515)* 1.609344;
        return $distance;
    }
}
