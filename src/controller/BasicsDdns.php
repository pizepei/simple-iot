<?php
/**
 * Class Ddns
 * 动态域名服务
 */
namespace pizepei\simpleIot\controller;

use pizepei\staging\Controller;
use pizepei\staging\Request;

class BasicsDdns extends Controller
{
    /**
     * @param \pizepei\staging\Request $Request [json]
     *      path [object] 路径参数
     *          verify [string] 获取的微信域名切割参数
     * @return array [json]
     * @title  微信域名验证
     * @explain 微信配置时需要使用文件验证此方法可自动验证
     * @router get test
     * @throws \Exception
     */
    public function test(Request $Request)
    {
        return dirname(__FILE__);
    }

}