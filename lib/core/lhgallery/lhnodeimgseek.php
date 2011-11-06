<?php

class erLhcoreClassNodeImgSeek 
{ 
    protected $_url; 
    protected $_port; 

    public function __construct( $url, $port = '' ) 
    { 
        $this->_url       = (string)$url;
        $this->_port       = (int)$port;
    } 
    
    public function execute( $params ) 
    { 
        return json_decode(file_get_contents($this->_url.':'.$this->_port.'/?'.self::formatUrl($params)));
    } 
    
    public static function formatUrl($params)
    {
        $parts = array();
        foreach ($params as $key => $value)
        {
            $parts[] = $key.'='.$value;
        }
        
        return implode('&',$parts);
    }
}