<?php
namespace YahooAPI;

abstract class Request{
    protected $version;
    protected $service;

    protected $appid;
    protected $secret;

    static $OUTPUT_XML = 'xml';
    static $OUTPUT_PHP = 'php';
    static $OUTPUT_JSON = 'json';

    function __construct($appid,$secret)
    {
        $this->appid = $appid;
        $this->service = $secret;
        $this->output = self::$OUTPUT_PHP;
        $this->version = 2;
    }

    /**
     * @param $url
     * @param $params
     * @param string $output
     * @return null
     */
    public function requestAPI($url,$params,$output='xml'){
        $response = null;
        $query = array_merge(array(
            'output' => $output,
        ),$params);
        $request_url = $url.'?'.http_build_query($query);

        $ch = curl_init($request_url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => "Yahoo AppID: ".$this->appid
        ));
        $result = curl_exec($ch);
        curl_close($ch);

        if($result){
            if($output == self::$OUTPUT_XML){
                $response = new SimpleXMLElement($result);
            }else if($output == self::$OUTPUT_PHP){
                $response = unserialize($result);
            }else if($output == self::$OUTPUT_JSON){
                $response = json_decode($result);
            }
        }
        return $response;
    }

    /**
     * @param $service
     * @param $version
     * @param $mode
     * @param $output
     * @return string
     */
    abstract protected function getRequestUrl($service,$version,$mode);
}