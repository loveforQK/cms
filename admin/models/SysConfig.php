<?php
namespace app\models;

use app\modules\admin\components\Template;
use yii\db\ActiveRecord;
use Yii;

/**
 * SysUser ActiveRecord model.
 *
 * Database fields:
 * @property string  $c_key
 * @property string  $c_val
 *
 */
class SysConfig extends ActiveRecord{
    public static $_instance = null;
    public static function tableName(){
        return 'sys_config';
    }

    public function rules(){
        return [];
    }

    public static function columns(){
        return ['c_key','c_val'];
    }

    public function attributeLabels() {
        return [];
    }

    public static function saveData($data){
        foreach($data as $k=>$v){
            Yii::$app->db->createCommand("INSERT INTO ".self::tableName()." (c_key,c_val) VALUES ('".$k."','".$v."') ON DUPLICATE KEY UPDATE c_val='".$v."'")->execute();
        }
        SysLog::add('更新站点配置',4,$data);
        return true;
    }

    public static function getValue($key){
        if(self::$_instance === null){
            $result = self::find()->select(self::columns())->asArray()->all();
            $list = [];
            foreach($result as $val){
                $list[$val['c_key']] = $val['c_val'];
            }
            self::$_instance = $list;
        }

        if(isset(self::$_instance[$key])){
            return self::$_instance[$key];
        }else{
            return '';
        }
    }
}
