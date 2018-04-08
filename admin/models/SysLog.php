<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * SysUser ActiveRecord model.
 *
 * Database fields:
 * @property string  $name
 * @property integer $type
 * @property string  $nickname
 * @property integer $user_id
 * @property string  $data
 * @property string  $ip
 * @property string  $addtime

 */
class SysLog extends ActiveRecord{
    public static $typelist = [
        1=>'登录',
        2=>'动态',
        5=>'管理员',
    ];

    public static function tableName(){
        return 'sys_log';
    }

    public function rules(){
        return [];
    }

    public static function columns(){
        return ['name','type','nickname','user_id','data','ip','addtime'];
    }

    public function attributeLabels() {
        return [
            'name' => '标题',
            'type' => '类型',
            'nickname' => '管理员',
            'user_id' => '管理员编号',
            'data' => '数据片段',
            'ip' => 'IP',
            'addtime' => '执行时间',
        ];
    }

    public static function add($name,$type,$data = null){
        if($data === null){
            $data = Yii::$app->request->post();
        }
        if(isset($data['_csrf'])){
            unset($data['_csrf']);
        }
        $savedata = [
            'name'=>$name,
            'type'=>$type,
            'nickname'=>Yii::$app->user->getIdentity()->nickname,
            'user_id'=>Yii::$app->user->id,
            'data'=>json_encode($data),
            'ip'=>Yii::$app->request->getUserIP(),
            'addtime'=>date('Y-m-d H:i:s')
        ];
        Yii::$app->db->createCommand()->insert(self::tableName(),$savedata)->execute();
        return true;
    }
}
