<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\components\BaseController;

class SiteController extends BaseController{
    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex(){
        return $this->render('index');
    }
}