<?php
require_once("config.php");
require('routeros_api.php');
require_once('app/CommandClass.php');
require_once('app/DeviceClass.php');
require('app/helpers.php');
require('app/DeviceWorker.php');

$API = new RouterosAPI();
$API->debug = false;

$deviceID="";
$influxData="";


$commands=loadCommands("commands.xml");
$devices=loadDevices("devices.xml");

foreach($devices as $devIP=>$device)
{
    $influxData="counters,client=".$CONF["ClientName"];

    $cmdResults=[];
    if($API->connect($device->IP,$device->User,$device->Secret)){
        $deviceID=getApiSingleValue($API,"/system/identity/getall","name");
        $influxData.=",host=$deviceID ";
        print("IP=$devIP DevciceID=$deviceID".PHP_EOL);

        $cmdResults=executeCommands($API,$commands);

        $API->disconnect();
    }

    $influxData .=implode($cmdResults,",");
    print($influxData.PHP_EOL);
    sendToServer($influxData,$CONF);
}


function sendToServer($influxData,$CONF)
{
    $influxdbUrl = $CONF["InfluxDB"]["Url"]."/write?db=".$CONF["InfluxDB"]["Database"];
    print("$influxdbUrl".PHP_EOL);
    
    $postResult=httpPost($influxdbUrl,$influxData);
    
    print("Post result:$postResult");
}


function executeCommands($api,$commands)
{
    $results=[];
    foreach($commands as $cmdID =>$cmd)
    {
        $result = getApiValue($api,$cmd->CommandText,$cmd->Arguments);
        print("Name=$cmdID Value=$result".PHP_EOL);        
        $results[]="$cmdID=$result";
    }

    return $results;
}

?>



