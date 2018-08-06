<?php

class CommandClass{
    public $ID="";
    public $Type="";
    public $CommandText="";
    public $Parameters=[];

    public $ResultTagName=""; //if API retuns mutlidimensional array (ethernet stats). Then one lement from array mus go for tag as identifier. Mostly ethernet name
    public $ResultsKeys=[]; // from API result array, what elelemnts we send to influx
}

function loadCommands($xmlFile)
{
    $results=[];
    print("Loading commands...");
    $xml=simplexml_load_file($xmlFile) or die ("Xml file not found");

    foreach($xml->xpath('commands/item') as $item)
    {
        $cmd = new CommandClass;
        $cmd->ID=$item["id"];
        $cmd->Type=$item["type"];
        $cmd->CommandText=$item["command"];

        foreach($item->parameter as $elem)
        {
            $key = $elem["key"];
            $val = $elem["value"];
            $cmd->Parameters["$key"] = $val;
        }    
        if($item->result != NULL)
        {
            print("\tFOUND Command->result".PHP_EOL);
            $cmd->ResultTagName=$item->result["tag"];
            $cmd->ResultKeys=explode(",",$item->result["keys"]);   
        }

        $results["".$cmd->ID.""]=$cmd;
    }
    print(" ".count($results).PHP_EOL);
    return $results;
}

?>