<?php
/**
 * Class Ddns
 * 动态域名服务
 */
namespace pizepei\simpleIot\controller;

use pizepei\simpleIot\service\BasicDdnsService;
use pizepei\staging\Controller;
use pizepei\staging\Request;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;


class BasicsDdns extends Controller
{
    /**
     * @param \pizepei\staging\Request $Request [json]
     *      path [object] 路径参数
     *          token [string required]
     *          token [string required]
     * @return array [json]
     *      data [raw]
     * @title  微信域名验证
     * @explain 微信配置时需要使用文件验证此方法可自动验证
     * @router get test
     * @throws \Exception
     */
    public function test(Request $Request)
    {
        $DdnsService = new BasicDdnsService(\Config::SMS['Aliyun']);
        return $this->succeed($DdnsService->DescribeSubDomainRecords('oauth.heil.top'));
    }
    /**
     * @param \pizepei\staging\Request $Request [json]
     *      path [object] 路径参数
     *          signature [string required]
     *          time [string required]
     * @return array [json]
     * @title  微信域名验证
     * @explain 微信配置时需要使用文件验证此方法可自动验证
     * @router get raspberry-pi/heart-beat/:signature[string]/:time[int]
     * @throws \Exception
     */
    public function raspberryPiHeartBeat(Request $Request)
    {
        $s = 'shgdjdui7324478fdskjfsdhif9657-09127387243546fgdg';
        $data = $Request->path();
        $data['md5'] =  md5($s.$Request->path('time'));
//        file_put_contents('test/'.date('H:i:s').'.txt',json_encode($data));
        return md5('5555');
    }
}