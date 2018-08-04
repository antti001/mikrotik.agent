<?php

class DeviceClass{
    public $IP="";
    public $User="";
    public $Secret="";
    public $Groups=[];
}


function loadDevices($xmlFile)
{
    $results=[];
    print("Loading devices...");
    $xml=simplexml_load_file($xmlFile) or die ("Xml file not found");

    foreach($xml->xpath('devices/item') as $item)
    {
        $cls = new DeviceClass;
        $cls->IP=$item["ip"];
        $cls->User=$item["user"];
        $cls->Secret=$item["secret"];
        $cls->Groups=explode(",",$item["secret"]);

        $results["".$cls->IP.""]=$cls;
    }
    print(" ".count($results).PHP_EOL);
    return $results;
}

?>