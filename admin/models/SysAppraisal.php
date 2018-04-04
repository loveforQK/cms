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
class SysAppraisal extends ActiveRecord{
    public static function tableName(){
        return 'sys_appraisal';
    }

    public function rules(){
        return [
            [['title'],'required'],
            [['title','position','thumb'],'string','max'=>255],
            [['phone'],'string','max'=>50],
            [['sort','content','position','phone','thumb'],'safe'],
        ];
    }

    public static function columns(){
        return ['id','sort','title','content','position','phone','thumb'];
    }

    public function attributeLabels() {
        return [
            'id' => '编号',
            'sort' => '顺序',
            'title' => '姓名',
            'content' => '详细内容',
            'position' => '职位',
            'phone' => '联系电话',
            'thumb' => '头像',
        ];
    }

    public function beforeSave($insert){
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if(!empty($changedAttributes)){
            $data = Yii::$app->request->post();
            unset($data['SysAppraisal']['content']);
            if($insert){
                SysLog::add('添加鉴定人员',7,$data);
            }else{
                SysLog::add('更新鉴定人员',7,$data);
            }
        }
        return true;
    }

    public static function getModel($id){
        return self::find()->select(self::columns())->where('id=:id',[':id'=>$id])->one();
    }
}
