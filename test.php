<?php
require('routeros_api.php');
require_once('config.php');

$API = new RouterosAPI();
$API->debug = false;

$xml=simplexml_load_file("commands.xml") or die ("Xml file not found");

print("".PHP_EOL);

if($API->connect($TEST["host"],$TEST["user"],$TEST["secret"]))
{

 //   $ar = $API->comm("/ip/dhcp-server/lease/print",array("count-only"=>""));
    $identity = getSingleValue($API,"/system/identity/getall","name");

//   $API->write("/system/identity/getall");
//   $READ = $API->read(false);
 //  $ar = $API->parseResponse($READ);
 //  $identity=$ar[0]["name"];
   print("Device name= $identity".PHP_EOL);

//   $ar = $API->comm('/interface/monitor-traffic',array("interface"=>"ether1","once"=>""));
   //$ar = $API->comm('/interface/monitor-traffic',array("interface"=>"[f]","once"=>""));
 // $ar = $API->comm('/interface/monitor-traffic/getall',array("once"=>""));

//   $ar = $API->comm('/interface',array("monitor-traffic"=>"","[f]"=>"","once"=>""));
print("Execute");
//$ar = $API->comm('/interface/ethernet/monitor',array("numbers"=>"0,1,2,3","once"=>""));

//$ar = $API->comm('/interface/ethernet/print',array("stats"=>""));
//shows one array
//$ar = $API->comm('/interface/monitor-traffic',array("once"=>""));



//$ar = $API->comm('/interface/monitor-traffic',array("[f]"=>"","once"=>""));
$API->write('/interface/monitor-traffic',false);
$API->write('[find type=ether]');
//$API->write('=[find type=ether]=',false);
 $API->write('=once=');
 $READ = $API->read(false);
 $ar = $API->parseResponse($READ);

//$ar = $API->comm('/interface/monitor-traffic/[find type=ether]',array("once"=>""));
// print_r($ar);
$ar = $API->comm('/interface/monitor-traffic',array("once"=>""));
print_r($ar);


 return;



//    $API->write('/interface/getall');
    $API->write('/interface/monitor-traffic',false);
//    $API->write('=interface=ether1',false);
    $API->write('=[find type=ether]=',false);

//    $API->write('=as-value=',false);
    $API->write('=once=');


    /*    $API->write("/ip/dhcp-server/lease/print",false);
    $API->write("=count-only=");
    */
    $READ = $API->read(false);
   $ar = $API->parseResponse($READ);
    print_r($ar);
 //   $API->disconnect();
}

function getSingleValue($api,$parameter,$name)
{
    
    $api->write($parameter);
    $rdr = $api->read(false);
    $resultArray = $api->parseResponse($rdr);
    if(count($resultArray)==1)
     return $resultArray[0][$name];
     else{
        print("getSingleValue. Error.");
        print_r($resultArray);

     }
     return $NUL;
}

?>



