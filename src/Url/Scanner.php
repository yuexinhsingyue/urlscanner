<?php
/**
 * Created by PhpStorm.
 * User: yuexin
 * Date: 2019/7/16
 * Time: 17:10
 */
namespace Hsingyue\Urlscanner\Url;

use GuzzleHttp\Client;
use League\Csv\Exception;

class Scanner
{
    protected $urls;

    protected $httpClient;

    public function __construct(array $urls)
    {
        $this->urls = $urls;
        $this->httpClient = new Client();
    }

    /**
     * 获取指定URL请求的HTTP状态码
     *
     * @param $url
     * @return int
     */
    public function getStatusCodeForUrl($url)
    {
        $httpResponse = $this->httpClient->get($url);
        return $httpResponse->getStatusCode();
    }

    /**
     * 获取死链
     *
     * @return array
     */
    public function getInvalidUrls()
    {
        $invalidUrls = [];
        foreach ($this->urls as $url) {
            try{
                $this->getStatusCodeForUrl($url);
            } catch(\Exception $e) {
                $statusCode = 500;
            }

            if ($statusCode >= 400) {
                array_push($invalidUrls, ['url' => $url, 'status' => $statusCode]);
            }
        }
        return $invalidUrls;
    }
}
