<?php


namespace App\Helpers\Utils;


use GuzzleHttp\Client;

/**
 * Class Participles
 * @package App\Helpers\Utils
 */
class Participles
{
    private $url = "https://nlp.qq.com/public/wenzhi/api/common_api2469449716.php"; //腾讯分词试用版
    private $api = 11;
    private $referer = "https://nlp.qq.com/semantic.cgi";
    private $cookie = "pgv_pvi=6549808128; pgv_pvid=5377660690; pgv_si=s1196331008";

    /**
     * @var string
     */
    private $content;


    public function __construct($content = '')
    {
        $this->content = $content;
    }

    /**
     * @param string $content
     */
    public function setContent($content) {
        $this->content = $content;
    }


    /**
     * 运行
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function run(){
        $length = mb_strlen($this->content);
        $client = new Client();
        $param = [
            'verify' => false,
            'form_params' => [
                'api' => $this->api,
                'body_data' => json_encode(['content' => $this->content])
            ],
            'headers' => [
                'cookie' => $this->cookie,
                'referer' => $this->referer
            ]
        ];
        $result = $client->request("POST", $this->url, $param);
        if($result->getStatusCode() != 200) return false;
        $result = json_decode($result->getBody(), true);
        if(isset($result['ret_code']) && $result['ret_code'] == 0) {
            return $this->handle($result['keywords'][0]);
        }
    }

    /**
     * 数据处理
     * @param $arr
     */
    public function handle($arr){
        if(!count($arr)) {
            return [$this->content];
        }
        $result = [];
        foreach ($arr as $k => $v) {
            if($v['postag'] == 'v' || $v['postag'] == 'n' || $v['postag'] == 'vn' || $v['postag'] == "nx"){ //动词,名词,名动词 和英文
                $result[] = $v['word'];
            }
        }
        return $result;
    }
}