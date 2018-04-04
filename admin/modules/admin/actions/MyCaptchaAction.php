<?php
namespace app\modules\admin\actions;

use Yii;
use yii\captcha\CaptchaAction;
use yii\web\Response;

class MyCaptchaAction extends CaptchaAction{
    public function run(){
        if (Yii::$app->request->getQueryParam(self::REFRESH_GET_VAR) !== null) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $code = $this->getVerifyCode(true);
            return [
                'url' => 'http://'.$_SERVER['HTTP_HOST'].'/admin/login/captcha?v='.uniqid(),
            ];
        } else {
            $this->setHttpHeaders();
            Yii::$app->response->format = Response::FORMAT_RAW;
            return $this->renderImage($this->getVerifyCode());
        }
    }
}