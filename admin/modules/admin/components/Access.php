<?php
/**
 * Created by PhpStorm.
 * User: xull
 * Date: 2016/3/3
 * Time: 15:53
 */
namespace app\modules\admin\components;

use Yii;
use yii\base\ActionFilter;

class Access extends ActionFilter{
    public function beforeAction($action){
        if(Yii::$app->user->isGuest){
            Yii::$app->user->loginRequired();
            return false;
        }

        return true;
    }
}