<?php

namespace app\modules\admin\controllers;

use app\models\SysConfig;
use Yii;
use app\modules\admin\components\BaseController;

class ConfigController extends BaseController{
    public function actionIndex(){
        return $this->render('index');
    }

    public function actionSave(){
        Yii::$app->response->format = 'json';
        $data = Yii::$app->request->post();
        unset($data['_csrf']);
        SysConfig::saveData($data);
        return ['code'=>1,'msg'=>'更新成功'];
    }

    public function actionResource(){
        $this->layout = false;
        return $this->render('resource');
    }
}