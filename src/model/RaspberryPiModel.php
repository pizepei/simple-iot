<?php
/**
 * 树莓派 配置表
 */
namespace pizepei\simpleIot\model;


use pizepei\model\db\Model;

class RaspberryPiModel extends Model
{
    /**
     * 表结构
     * @var array
     */
    protected $structure = [
        'id'=>[
            'TYPE'=>'uuid','COMMENT'=>'主键uuid','DEFAULT'=>false,
        ],
        'name'=>[
            'TYPE'=>'varchar(128)', 'DEFAULT'=>'', 'COMMENT'=>'设备名称',
        ],
        'group_id'=>[
            'TYPE'=>'varchar(128)', 'DEFAULT'=>false, 'COMMENT'=>'自定义分类标签',
        ],
        'ddns_token'=>[
            'TYPE'=>'varchar(50)', 'DEFAULT'=>false, 'COMMENT'=>'令牌密钥','EXPLAIN'=>'',
        ],
        'ddns_domain_name'=>[
            'TYPE'=>'json', 'DEFAULT'=>false, 'COMMENT'=>'ddns域名',
        ],
        'address'=>[
            'TYPE'=>"varchar(600)", 'DEFAULT'=>'', 'COMMENT'=>'地址',
        ],
        'remark'=>[
            'TYPE'=>"varchar(500)", 'DEFAULT'=>'', 'COMMENT'=>'备注',
        ],
        'status'=>[
            'TYPE'=>"ENUM('1','2','3','4','5')", 'DEFAULT'=>'1', 'COMMENT'=>'1停用2、正常3、维护4、等待5、异常',
        ],
        'expand'=>[
            'TYPE'=>'json', 'DEFAULT'=>false, 'COMMENT'=>'拓展',
        ],
        'INDEX'=>[
            ['TYPE'=>'UNIQUE','FIELD'=>'name,group_id','NAME'=>'name,group_id','USING'=>'BTREE','COMMENT'=>'设备名称'],
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