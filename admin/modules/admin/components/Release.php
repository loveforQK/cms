<?php
namespace app\modules\admin\components;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

class Release extends Behavior{
    public function events(){
        return [
            Controller::EVENT_AFTER_ACTION=>'close'
        ];
    }

    public function close($event){
        if(Yii::$app->db->isActive){
            Yii::$app->db->close();
        }
    }
}