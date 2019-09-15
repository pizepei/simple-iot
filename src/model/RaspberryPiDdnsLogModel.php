<?php
/**
 * DDNS 日志
 */

namespace pizepei\simpleIot\model;


use pizepei\model\db\Model;

class RaspberryPiDdnsLogModel extends Model
{
    /**
     * 表结构
     * @var array
     */
    protected $structure = [
        'id'=>[
            'TYPE'=>'uuid','COMMENT'=>'主键uuid','DEFAULT'=>false,
        ],
        'ip'=>[
            'TYPE'=>'varchar(128)', 'DEFAULT'=>'', 'COMMENT'=>'当前ip',
        ],
        'ago_ip'=>[
            'TYPE'=>'varchar(128)', 'DEFAULT'=>'', 'COMMENT'=>'变更请的ip','EXPLAIN'=>'',
        ],
        'ddns_domain_name'=>[
            'TYPE'=>'varchar(150)', 'DEFAULT'=>false, 'COMMENT'=>'ddns域名',
        ],
        'pi_uuid'=>[
            'TYPE'=>"uuid", 'DEFAULT'=>'', 'COMMENT'=>'树莓派id',
        ],
        'remark'=>[
            'TYPE'=>"varchar(500)", 'DEFAULT'=>'', 'COMMENT'=>'备注',
        ],
        'expand'=>[
            'TYPE'=>'json', 'DEFAULT'=>false, 'COMMENT'=>'拓展',
        ],
        'INDEX'=>[
            ['TYPE'=>'INDEX','FIELD'=>'pi_uuid','NAME'=>'pi_uuid','USING'=>'BTREE','COMMENT'=>'树莓派ID'],
        ],
        'PRIMARY'=>'id',//主键
    ];
    /**
     * @var string 表备注（不可包含@版本号关键字）
     */
    protected $table_comment = '树莓派配置表';
    /**
     * @var int 表版本（用来记录表结构版本）在表备注后面@$table_version
     */
    protected $table_version = 0;
    /**
     * @var array 表结构变更日志 版本号=>['表结构修改内容sql','表结构修改内容sql']
     */
    protected $table_structure_log = [
    ];
}