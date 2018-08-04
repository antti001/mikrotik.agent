<?php
require('routeros_api.php');

$API = new RouterosAPI();
$API->debug = false;

$xml=simplexml_load_file("commands.xml") or die ("Xml file not found");

print("".PHP_EOL);

if($API->connect('10.0.1.1','username','password')){

 //   $ar = $API->comm("/ip/dhcp-server/lease/print",array("count-only"=>""));

    $identity = getSingleValue($API,"/system/identity/getall","name");

//   $API->write("/system/identity/getall");
//   $READ = $API->read(false);
 //  $ar = $API->parseResponse($READ);
 //  $identity=$ar[0]["name"];
   print("Device name= $identity".PHP_EOL);

   $API->write('/interface/getall');
/*    $API->write("/ip/dhcp-server/lease/print",false);
    $API->write("=count-only=");
    */
//    $READ = $API->read(false);
  //  $ar = $API->parseResponse($READ);
  //  print_r($ar);
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



