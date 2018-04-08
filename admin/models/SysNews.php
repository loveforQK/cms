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
 * @property integer $status
 * @property string  $thumb
 * @property string  $info
 * @property string  $content
 * @property string  $pubtime
 *
 */
class SysNews extends ActiveRecord{
    const TYPE_BUSINESS = 1;
    const TYPE_INDUSTRY = 2;
    const STATUS_DISABLED = 0;
    const STATUS_ENABLED = 1;
    public static $statuslist = [
        self::STATUS_ENABLED=>'发布',
        self::STATUS_DISABLED=>'草稿',
    ];

    public static function tableName(){
        return 'sys_news';
    }

    public function rules(){
        return [
            [['title'],'required'],
            [['title'],'string','max'=>255],
            [['info'],'string','max'=>500],
            [['thumb','status','content','pubtime','home'],'safe'],
        ];
    }

    public static function columns(){
        return ['id','title','status','thumb','info','content','pubtime','home'];
    }

    public function attributeLabels() {
        return [
            'id' => '编号',
            'title' => '标题',
            'status' => '状态',
            'thumb' => '缩略图',
            'info' => '摘要',
            'content' => '详情',
            'pubtime' => '发布时间',
        ];
    }

    public function beforeSave($insert){
        if($insert){
            $this->pubtime = date('Y-m-d H:i:s');
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if(!empty($changedAttributes)){
            $data = Yii::$app->request->post();
            unset($data['SysNews']['content']);
            if($insert){
                SysLog::add('添加文章',3,$data);
            }else{
                SysLog::add('更新文章',3,$data);
            }
        }

        return true;
    }

    public static function getModel($id){
        return self::find()->select(self::columns())->where('id=:id',[':id'=>$id])->one();
    }
}
