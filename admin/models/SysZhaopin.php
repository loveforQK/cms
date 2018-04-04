<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;
use app\modules\admin\components\Template;

/**
 * SysUser ActiveRecord model.
 *
 * Database fields:
 * @property integer $id
 * @property string  $title
 * @property string  $content
 * @property integer $sort
 *
 */
class SysZhaopin extends ActiveRecord{
    public static function tableName(){
        return 'sys_zhaopin';
    }

    public function rules(){
        return [
            [['title'],'required'],
            [['title'],'string','max'=>255],
            [['sort','content'],'safe'],
        ];
    }

    public static function columns(){
        return ['id','sort','title','content'];
    }

    public function attributeLabels() {
        return [
            'id' => '编号',
            'sort' => '顺序',
            'title' => '标题',
            'content' => '内容',
        ];
    }

    public function beforeSave($insert){
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if(!empty($changedAttributes)){
            $data = Yii::$app->request->post();
            unset($data['SysZhaopin']['content']);
            if($insert){
                SysLog::add('添加招聘',6,$data);
            }else{
                SysLog::add('更新招聘',6,$data);
            }
        }
        return true;
    }

    public static function getModel($id){
        return self::find()->select(self::columns())->where('id=:id',[':id'=>$id])->one();
    }
}
