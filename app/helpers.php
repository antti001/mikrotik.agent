<?php
function getApiSingleValue($api,$command,$name)
{
    
    $api->write($command);
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

function getApiValue($api,$command,$arguments)
{
    $result=array();
    if(count($arguments)>0)
    {
        $result = $api->comm("$command",$arguments);
    }
    else
    {   
        $api->write($command,False);
        $rdr = $api->read(false);
        $result = $api->parseResponse($rdr);
    }
    return $result;
}


function httpPost($url, $data)
{
    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,"$data");
   
    //execute post
    $result = curl_exec($ch);
    return $result;
}

?>