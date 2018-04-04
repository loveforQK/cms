<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * SysUser ActiveRecord model.
 *
 * Database fields:
 * @property integer $id
 * @property string  $title
 * @property string  $dirname
 * @property string  $content
 * @property integer $menu1
 * @property integer $menu2
 *
 */
class SysPage extends ActiveRecord{
    public static function tableName(){
        return 'sys_page';
    }

    public function rules(){
        return [
            [['title','dirname'],'required'],
            [['title'],'string','max'=>255],
            [['dirname'],'string','max'=>50],
            [['dirname'],'unique'],
            [['content','menu1','menu2'],'safe'],
        ];
    }

    public static function columns(){
        return ['id','title','dirname','content','menu1','menu2'];
    }

    public function attributeLabels() {
        return [
            'id' => '编号',
            'title' => '标题',
            'dirname' => '路径',
            'content' => '内容',
            'menu1' => '一级菜单',
            'menu2' => '二级菜单',
        ];
    }

    public function beforeSave($insert){
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        return true;
    }

    public static function getModel($id){
        return self::find()->select(self::columns())->where('id=:id',[':id'=>$id])->one();
    }

    //获取一级菜单列表
    public static function getMenu1List(){
        return self::find()->select(self::columns())->where('menu1 > 0')->orderBy('menu1 asc')->all();
    }

    //获取二级菜单列表
    public static function getMenu2List(){
        return self::find()->select(self::columns())->where('menu2 > 0')->orderBy('menu2 asc')->all();
    }
}
