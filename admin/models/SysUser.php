<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use Yii;

/**
 * SysUser ActiveRecord model.
 *
 * Database fields:
 * @property integer $id
 * @property string  $username
 * @property string  $pwd
 * @property string  $email
 *
 */
class SysUser extends ActiveRecord implements IdentityInterface{
    const cacheKey = 'SysUser';

    public static function tableName(){
        return 'sys_user';
    }

    public function rules(){
        return [
            [['username','nickname','email'],'required'],
            [['pwd'],'safe'],
        ];
    }

    public static function columns(){
        return ['id','username','nickname','email'];
    }

    public function beforeSave($insert){
        parent::beforeSave($insert);
        return true;
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if(!empty($changedAttributes)){
            Yii::$app->cache->delete(self::cacheKey.$this->id);

            if($insert){
                SysLog::add('添加管理员',5,Yii::$app->request->post());
            }else{
                SysLog::add('更新管理员',5,Yii::$app->request->post());
            }
        }
    }

    public function attributeLabels() {
        return [
            'id' => '编号',
            'username' => '用户名',
            'nickname' => '姓名',
            'pwd' => '密码',
            'email'=>'邮箱',
        ];
    }

    public static function findIdentity($id){
        return self::getModel($id);
    }

    public static function findIdentityByAccessToken($token, $type = null){
        return null;
    }

    public function getId(){
        return $this->id;
    }

    public function getAuthKey(){
        return null;
    }

    public function validateAuthKey($authKey){
        return true;
    }

    /**
     * 获取用户信息
     */
    public static function getModel($id){
        $model = Yii::$app->cache->get(self::cacheKey.$id);
        if(!empty($model)){
            return $model;
        }

        $model = self::findOne($id);
        if(!empty($model)){
            Yii::$app->cache->set(self::cacheKey.$id,$model);
        }

        return $model;
    }

    /**
     * 账户校验登录
     */
    public static function login($account,$pwd){
        $model = self::find()->where('username=:username',[':username'=>$account])->one();
        if(empty($model)){
            return false;
        }

        if(!Yii::$app->security->validatePassword($pwd,$model->pwd)){
            return false;
        }

        return Yii::$app->user->login($model);
    }
}
