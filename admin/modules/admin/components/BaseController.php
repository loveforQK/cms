<?php

namespace app\modules\admin\components;

use Yii;
use yii\web\Controller;
use app\modules\admin\components\Access;
use yii\web\Response;
use app\modules\admin\components\Release;

class BaseController extends Controller{
    public $layout = 'main';
    /**
     * 行为控制
     */
    public function behaviors(){
        return [
            'access' => [
                'class' => Access::className(),
            ],
			 'mybehavior'=>[
                'class'=>Release::className()
            ]
        ];
    }

    /**
     * 初始化
     */
    public function beforeAction($action){
        return parent::beforeAction($action);
    }
}