<?php
namespace YahooAPI;

class ApiError{
    private $message;
    private $status_code;
    private $code;

    function __construct($params)
    {
        if(is_array($params)){
            foreach($params as $key => $val){
                if($key == 'message') {
                    $this->message = $val;
                }else if($key == 'status_code'){
                    $this->status_code = $val;
                }else if($key == 'code'){
                    $this->code = $val;
                }else{
                    $this->error(sprintf('%s construct params unsupport %s',get_class($this),$key));
                }
            }
        }else if(is_string($params)){
            $this->message = $params;
        }else{
            $this->error(sprintf('%s construct params string or array',get_class($this)));
        }
    }

    /**
     * @param $message
     * @return bool
     */
    protected function error($message){
        return trigger_error(sprintf('%s : '.$message,get_class($this)), E_USER_ERROR);
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param mixed $status_code
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

}