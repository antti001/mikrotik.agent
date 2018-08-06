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


$commands=loadCommands(__DIR__.DIRECTORY_SEPARATOR."commands.xml");
$devices=loadDevices(__DIR__.DIRECTORY_SEPARATOR."devices.xml");

foreach($devices as $devIP=>$device)
{
    $influxData="client=".$CONF["ClientName"];

    $cmdResults=[];
    if($API->connect($device->IP,$device->User,$device->Secret)){
        $deviceID=getApiSingleValue($API,"/system/identity/getall","name");
        $influxData.=",host=$deviceID";
        print("IP=$devIP DevciceID=$deviceID".PHP_EOL);

        $cmdResults=executeCommands($API,$influxData,$commands);

        $API->disconnect();
    }

    $influxData .=implode($cmdResults,",");
    foreach($cmdResults as $cmdResult)
    {
       
        print("RESULT LINE : $cmdResult".PHP_EOL);
        //teha nii, et saadetaks ühe POST käsuga
        sendToServer($cmdResult,$CONF);

    }
//    print($influxData.PHP_EOL);
  //  sendToServer($influxData,$CONF);
}


function sendToServer($influxData,$CONF)
{
    $influxdbUrl = $CONF["InfluxDB"]["Url"]."/write?db=".$CONF["InfluxDB"]["Database"];
    print("$influxdbUrl".PHP_EOL);
    
    $postResult=httpPost($influxdbUrl,$influxData);
    
    print("Post result:$postResult");
}


function executeCommands($api,$prefix,$commands)
{
    $results=[];
    $singeValues=[];
    foreach($commands as $cmdID =>$cmd)
    {
        $result = getApiValue($api,$cmd->CommandText,$cmd->Parameters);
        if(is_array($result))
        {
            foreach($result as $subResultID =>$subResult)
            {
               if(is_array($subResult))
               {
                 $sIDTag=$cmd->ResultTagName."=".$subResult["$cmd->ResultTagName"];
                 $sDataValuePart = parseResultArray($subResult,$cmd->ResultKeys);
                 $results[] = $cmdID.",".$prefix.",$sIDTag ".$sDataValuePart;
               }

            }
        }else{
            $results[] = $cmdID.",".$prefix." value=$result";
        }
    }
    return $results;
}


function parseResultArray($ar,$arResultKeys)
{
    $sResult="";
    $arValuePairs=[];
    foreach($ar as $key =>$value)
    {

        if(in_array($key,$arResultKeys))
        {
         //   print("\t\tprocessResult.  $key=$value");        
            $arValuePairs[]="$key=$value";
        }
    }
    return implode(",",$arValuePairs);
}

?>



