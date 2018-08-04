<?php
require_once("configuration.php");
require('routeros_api.php');
require('helpers.php');

$API = new RouterosAPI();
$API->debug = false;

$deviceID="";
$influxData="";
$xml=simplexml_load_file("commands.xml") or die ("Xml file not found");

$results = array();

if($API->connect('10.0.1.1','username','password')){

    $deviceID=getApiSingleValue($API,"/system/identity/getall","name");
    print("DevciceID = $deviceID".PHP_EOL);


    $influxData="counter,client=".$CONF["ClientName"].",host=$deviceID ";
    $arResults=array();
    // foreach($xml->xpath('group[@id="dhcpd"]/item') as $item)
    foreach($xml->xpath('commands/item') as $item)
    {
        $itemID=$item["id"];
        $itemType=$item["type"];
        $cmdText=$item["command"];
        
//        $itemText = str_replace(array("\n","\r"), '', $itemText);
 //       $itemText = trim($itemText);

        $cmdArguments=array();
        foreach($item->add as $elem)
        {
            $key = $elem["key"];
            $val = $elem["value"];
            $cmdArguments["$key"] = $val;
        }    
        $result = getApiValue($API,$cmdText,$cmdArguments);
//        print("Name=$itemID Value=$result".PHP_EOL);        
        $arResults[]="$itemID=$result";
    }
    $influxData .=implode($arResults,",");
    print($influxData.PHP_EOL);
}

$influxdbUrl = $CONF["InfluxDB"]["Url"]."/write?db=".$CONF["InfluxDB"]["Database"];
print("$influxdbUrl".PHP_EOL);

$postResult=httpPost($influxdbUrl,$influxData);

print("Post result:$postResult");

?>



