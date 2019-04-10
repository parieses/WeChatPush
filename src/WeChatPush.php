<?php
/**
 * Created by PhpStorm.
 * User: Mr.亮先生.Mr.亮先生
 * Date: 2019/4/10
 * Time: 10:29
 */
namespace  WeChatPush;
class WeChatPush{
    protected $type;
    protected $config = [];
    protected $data=[];
    protected $access_token;
    protected $sendUrl;

    /**
     * WeChatPush constructor.
     * @param $type 1公众号2小程序
     * @param array $config
     * @param array $data
     */
    public function __construct($type,array $config)
    {
        $this->type = $type;
        $this->config = $config;
        self::initAccess_token();
        self::getUrl();
    }

    /**
     * @param array $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    public function Push(){
        return self::https_curl_json($this->sendUrl,$this->data,'json');
    }
    public function initAccess_token(){
        if ($this->type==1){
            //wx3ea1b642d30148bc
            //7492106fccf113d5fe007c058d8a4e60
            $url ='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->config['appid'].'&secret='.$this->config['secret'];
        }else{
            //wx2ad7a428e00990ca
            //bdab7201012660b23a0ab7b00b0c4cca
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->config['appid'].'&secret=".$this->config['secret'];
        }
        $ac = file_get_contents($url);
        $wxt = json_decode($ac,true);
        $this->access_token = $wxt['access_token'];
    }
    public function getUrl(){
        if ($this->type ==1){
            $this->sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $this->access_token;
        }else{
            $this->sendUrl = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token='. $this->access_token;
        }
    }
    public function getAccess_token(){
        return $this->access_token;
    }
    public static function https_curl_json($url,$data,$type){
        if($type=='json'){//json $_POST=json_decode(file_get_contents('php://input'), TRUE);
            $headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
            $data=json_encode($data);
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl);
        return $output;
    }



}