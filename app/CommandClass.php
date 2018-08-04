<?php

class CommandClass{
    public $ID="";
    public $Type="";
    public $CommandText="";
    public $Parameters=[];
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

        foreach($item->add as $elem)
        {
            $key = $elem["key"];
            $val = $elem["value"];
            $cmd->Arguments["$key"] = $val;
        }    
        $results["".$cmd->ID.""]=$cmd;
    }
    print(" ".count($results).PHP_EOL);
    return $results;
}

?>