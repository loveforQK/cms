<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\components\BaseController;

class LogController extends BaseController{
    public function actionIndex(){
        return $this->render('index');
    }
}