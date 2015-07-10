<?php
namespace YahooAPI;

require_once(dirname(__FILE__)."/ApiError.php");

abstract class Request{
    protected $version;
    protected $service;
    protected $response;
    /**
     * @var ApiError
     */
    protected $error;

    protected $appid;
    protected $secret;

    static $OUTPUT_XML = 'xml';
    static $OUTPUT_PHP = 'php';
    static $OUTPUT_JSON = 'json';

    function __construct($appid,$secret)
    {
        $this->appid = $appid;
        $this->service = $secret;
        $this->output = self::$OUTPUT_XML;
        $this->version = 2;
    }

    /**
     * @param $message
     * @return bool
     */
    protected function error($message){
        return trigger_error(sprintf('%s : '.$message,get_class($this)), E_USER_ERROR);
    }

    /**
     * @param $url
     * @param $params
     * @param string $output
     * @return null
     */
    public function requestAPI($url,$params,$output='xml'){
        $query = array_merge(array(
            'output' => $output,
        ),$params);
        $request_url = $url.'?'.http_build_query($query);

        if(in_array($query['output'],array(self::$OUTPUT_PHP,self::$OUTPUT_JSON))){
            $this->error('output param php or json deprecated');
        }
        $ch = curl_init($request_url);
        curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT      => "Yahoo AppID: ".$this->appid
        ));
        $result = curl_exec($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        return $this->bindResponse($result,$query['output'],$info);
    }

    /**
     * @param $result
     * @param $output
     * @param null $info
     * @return null
     */
    protected function bindResponse($result,$output,$info=null){
        $this->response = null;
        $this->error = null;
        if($result){
            $response = null;
            if($output == self::$OUTPUT_XML){
                $response = (new \SimpleXMLElement($result,LIBXML_NOCDATA));
                if($response){
                    // SimpleXMLElement to stdClass
                    $response = json_decode(json_encode($response));
                }
            }else if($output == self::$OUTPUT_PHP){
                $response = $this->toObject(unserialize($result));
            }else if($output == self::$OUTPUT_JSON){
                $response = json_decode($result);
            }

            if(!$response) {
                $this->error = new ApiError(array(
                    'message' => 'Yahoo Api Pers Error'
                ));
            }else if($error = $this->getErrorResponse($response,$output)){
                if(isset($info['http_code'])){
                    $error->setStatusCode($info['http_code']);
                }
                $this->error = $error;
            }else{
                $this->response = $response;
            }
        }else{
            $this->error = new ApiError(array(
                'message' => 'Yahoo Api Connect Error'
            ));
        }
        return ($this->response) ? $this->response : null;
    }

    protected function toObject($obj){
        if($obj){
            if(is_array($obj)){
                $p = array();
                foreach($obj as $k => $v){
                    $p[$k] = $this->toObject($v);
                }
                $obj = (object)$p;
            }
        }
        return $obj;
    }

    /**
     * @return ApiError|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $response
     * @param $output
     * @return ApiError|null
     */
    private function getErrorResponse($response,$output){
        if($response){
            if($output == self::$OUTPUT_XML){
                if(isset($response->Message)){
                    return new ApiError(array(
                        'message' => (string)$response->Message,
                        'code' => isset($response->Code) ? (string)$response->Code : null,
                    ));
                }
            }else if($output == self::$OUTPUT_PHP){
                if(isset($response->Error)){
                    return new ApiError(array(
                        'message' => $response->Error->Message,
                        'code' => isset($response->Error->Code) ? $response->Error->Code : null,
                    ));
                }
            }else if($output == self::$OUTPUT_JSON){
            }
        }
        return null;
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