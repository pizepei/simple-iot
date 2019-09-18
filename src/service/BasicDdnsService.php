<?php
/**
 * ddns
 */
namespace pizepei\simpleIot\service;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use pizepei\simpleIot\model\RaspberryPiDdnsLogModel;
use pizepei\simpleIot\model\RaspberryPiModel;
use pizepei\terminalInfo\TerminalInfo;

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
     * @title 请求
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

    /**
     * @param string $DomainName 域名名称。
     * @param string $RR  主机记录  www
     * @param string $Type 解析记录类型
     * @param string $Value 记录值。
     * @param int $TTL 解析生效时间，默认为600秒（10分钟）
     * @return array
     * @throws \Exception
     */
    public function AddDomainRecord(string $DomainName,string $RR,string $Value,$Type='A',int $TTL=600)
    {
        return $this->action('AddDomainRecord',[
            'DomainName' =>$DomainName,
            'RR'=>$RR,
            'Type'=>$Type,
            'Value'=>$Value,
            'TTL'=>$TTL,
        ]);
    }

    /**
     * @param $path
     * @return string
     * @throws \Exception
     */
    public  function setRaspberryPiDdns($path)
    {
        $data = RaspberryPiModel::table()->where(['id'=>$path['uuid']])->fetch();
        if (md5($path['uuid'].$data['ddns_token']. $path['time']) !== $path['signature']){
            return ['error'=>'验证失败'];
        }
        if (empty($data['ddns_domain_name'])){
            return [0];
        }
        # 循环树莓派配置
        foreach ($data['ddns_domain_name'] as $key=>$value){
            if (is_array($value) && !empty($value)){
                # 当树莓派失败有ddns域名配置时
                foreach ($value as $k=>$v){
                    # 读取ddns配置日志
                    $Log = RaspberryPiDdnsLogModel::table()->where([
                        'pi_uuid'=>$path['uuid'],
                        'ddns_domain_name'=>$k.'.'.$key
                    ])->order('creation_time','desc')->fetch();
                    # 这里不再使用gethostbyname函数判断是否需要更新域名解析：因为域名解析是有生效延迟的在解析没有生效前使用gethostbyname函数获取的ip一直是ago_ip
                    # 这里使用设备ip+域名+order desc 查询出最新的日志来判断是否需要更新解析

                    if (empty($Log)){
                        # 先删除记录
                        $res['DeleteSub'][] = $this->DeleteSubDomainRecords($key,$k);
                        # 添加记录
                        $res['Delete'][] = $this->AddDomainRecord($key,$k,TerminalInfo::get_ip());
                        RaspberryPiDdnsLogModel::table()->add([
                            'pi_uuid'=>$path['uuid'],
                            'ip'=>TerminalInfo::get_ip(),
                            'ago_ip'=>TerminalInfo::get_ip(),
                            'ddns_domain_name'=>$k.'.'.$key,
                            'expand'=>['res'=>$res],
                        ]);
                    }else{
                        # 有记录
                        if ($Log['ip'] !==TerminalInfo::get_ip()){
                            # 先删除记录
                            $res['DeleteSub'][] = $this->DeleteSubDomainRecords($key,$k);
                            # 添加记录
                            $res['Delete'][] = $this->AddDomainRecord($key,$k,TerminalInfo::get_ip());
                            RaspberryPiDdnsLogModel::table()->add([
                                'pi_uuid'=>$path['uuid'],
                                'ip'=>TerminalInfo::get_ip(),
                                'ago_ip'=>$Log['ip'],
                                'ddns_domain_name'=>$k.'.'.$key,
                                'expand'=>['res'=>$res],
                            ]);
                        }
                    }
                }
            }
        }
        return $res??[0];
    }

}