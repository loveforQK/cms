<?php
namespace app\widgets;
use yii\base\Widget;
use Yii;

class ShowErrors extends Widget{
    public $key_error = null;
    public $key_success = null;
    public function init(){
        if($this->key_error === null){
            $this->key_error = Yii::$app->params['list_error'];
        }
        if($this->key_success === null){
            $this->key_success = Yii::$app->params['list_success'];
        }
        parent::init();
    }

    public function run(){
        $errors = Yii::$app->session->getFlash($this->key_error,[],true);
        $success = Yii::$app->session->getFlash($this->key_success,[],true);
        $html = '';
        if(!empty($errors)){
            foreach($errors as $children){
                foreach($children as $val){
                    $html .= $this->_html_error($val);
                }
            }
            return $html;
        }

        if(!empty($success)){
            foreach($success as $children){
                $html .= $this->_html_success($children);
            }
            return $html;
        }


        return $html;
    }

    private function _html_error($msg){
        return  <<<EOT
<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{$msg}</div>
EOT;
    }

    private function _html_success($msg){
        return  <<<EOT
<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>{$msg}</div>
EOT;
    }
}