<?php
/**
 * ddns
 */
namespace pizepei\simpleIot\service;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

class BasicDdnsService
{
    /**
     * @var array 配置
     */
    protected $config = [];

    /**
     * DdnsService constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        AlibabaCloud::accessKeyClient($this->config['accessKeyId'], $this->config['accessKeySecret'])
            ->regionId('cn-hangzhou') // replace regionId as you need
            ->asDefaultClient();
    }
    /**
     * @Author 皮泽培
     * @Created 2019/9/11 17:57
     * @title 取某个固定子域名的所有解析记录列表
     * @param array $SubDomain 子域名
     * @throws \Exception
     */
    public function DescribeSubDomainRecords($SubDomain)
    {
        return $this->action('DescribeSubDomainRecords',['SubDomain' =>$SubDomain,]);
    }
    /**
     * @Author 皮泽培
     * @Created 2019/9/11 17:57
     * @title 根据传入参数删除主机记录对应的解析记录。
     * @param string $DomainName 域名名称。
     * @param string $RR 域名名称。
     * @throws \Exception
     */
    public function DeleteSubDomainRecords($DomainName,$RR)
    {
        return $this->action('DeleteSubDomainRecords',['DomainName' =>$DomainName,'RR'=>$RR]);
    }
    /**
     * @Author 皮泽培
     * @Created 2019/9/11 17:57
     * @title 取某个固定子域名的所有解析记录列表
     * @param string $action 接口
     * @param array $query 参数
     * @throws \Exception
     */
    protected function action(string $action,$query)
    {
        $query['RegionId'] = $query['RegionId']??'default';
        try {
            $result = AlibabaCloud::rpc()
                ->product('Alidns')
                 ->scheme('https') // https | http
                ->version('2015-01-09')
                ->action($action)
                ->method('POST')
                ->host('alidns.aliyuncs.com')
                ->options([
                    'query' => $query,
                ])
                ->request();
            return $result->toArray();
        } catch (ClientException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            echo $e->getErrorMessage() . PHP_EOL;
        }

    }

    #  域名   记录
    #
}