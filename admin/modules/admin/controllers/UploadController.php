<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\components\BaseController;

class UploadController extends BaseController{
    public function actions(){
        return [
            'index' => [
                'class' => 'yii\ueditor\UEditorAction',
            ],
        ];
    }
}