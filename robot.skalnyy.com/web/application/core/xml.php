<?php

class Xml {
    
    public static function array_to_xml($data, &$xml_data ) {
        foreach( $data as $key => $value ) {
            $keyname = is_numeric( $key ) ? "item".$key : $key;
            if(is_array($value)) {
                if(is_numeric($key)){
                    self::array_to_xml($value, $xml_data);
                } else {
                    if(array_key_exists('name1', $value))
                        $subnode = $xml_data->addChild($key, $value['name1']);
                    else
                        $subnode = $xml_data->addChild($key);
                    self::array_to_xml($value, $subnode);
                }
            } else {
                preg_match("#\[([a-z0-9-_]+)\]#i", $keyname, $attr1);
                if( count($attr1) ){
                    $xml_data->addAttribute($attr1[1], htmlspecialchars("$value"));
                }else if($keyname == 'name1') {
                    
                }else{
                    $xml_data->addChild($keyname, htmlspecialchars("$value"));
                }
                
            }
        }
    }
    
    public static function newXml() {
        $result = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><yml_catalog/>');
        return $result;
    }
}