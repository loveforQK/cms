<?php
namespace app\models;

use yii\db\ActiveRecord;
use Yii;

/**
 * SysUser ActiveRecord model.
 *
 * Database fields:
 * @property integer $id
 * @property integer $type
 * @property string  $url
 * @property integer $size
 * @property integer $mtime

 */
class SysAttachment extends ActiveRecord{
    const TYPE_IMAGE = 1;
    const TYPE_VIDEO = 2;
    const TYPE_DOC = 3;

    public static $statuslist = [
        self::TYPE_IMAGE=>'图片',
        self::TYPE_VIDEO=>'视频',
        self::TYPE_DOC=>'文档',
    ];

    public static function tableName(){
        return 'sys_attachment';
    }

    public function rules(){
        return [
            [['url'],'required'],
            [['url'],'string','max'=>255],
        ];
    }

    public static function columns(){
        return ['id','type','url','size','mtime'];
    }

    public function attributeLabels() {
        return [
            'id' => '编号',
            'type' => '类型',
            'url' => '附件地址',
            'size' => '大小',
            'mtime' => '上传时间',
        ];
    }

    public static function add($url,$size,$type){
        Yii::$app->db->createCommand()->insert(self::tableName(),['type'=>$type,'url'=>$url,'size'=>$size,'mtime'=>time()])->execute();
        return true;
    }

    public static function page($type,$start,$size){
        $ouput = ['list'=>[],'total'=>0];
        $ouput['total'] = self::find()->select('1')->where('type=:type',[':type'=>$type])->count();
        if($ouput['total'] == 0){
            return $ouput;
        }
        $ouput['list'] = self::find()->select('url,mtime')->where('type=:type',[':type'=>$type])->orderBy('id desc')->limit($size)->offset($start)->asArray()->all();
        return $ouput;
    }
}
