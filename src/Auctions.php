<?php
namespace YahooAPI;
require dirname(__FILE__)."/Request.php";

class Auctions extends Request{
    static private $host = 'http://auctions.yahooapis.jp';
    static private $SERVICE_ID = 'AuctionWebService';

    static private $SERVICE_VERSION = 'V2';

    function __construct($appid,$secret)
    {
        parent::__construct($appid,$secret);

        $this->service = self::$SERVICE_ID;
    }

    /**
     * @param $service
     * @param $version
     * @param $mode
     * @return string
     */
    protected function getRequestUrl($service,$version,$mode){
        $url = self::$host.'/'.$service.'/'.$version.'/'.$mode;
        return $url;
    }

    /**
     * @param $datatype
     * @param array $params
     * @return null
     */
    public function request($datatype,$params=array()){
        return $this->requestAPI($this->getRequestUrl(self::$SERVICE_ID,self::$SERVICE_VERSION,$datatype),$params,$this->output);
    }
}