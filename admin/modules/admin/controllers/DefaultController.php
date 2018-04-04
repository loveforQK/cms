<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\components\BaseController;
use app\modules\admin\components\Template;

class DefaultController extends BaseController{
    private $tempModel = null;

    public function actionIndex(){
        return $this->render('index');
    }

    public function actionStart(){
        set_time_limit(0);
        Yii::$app->response->format = 'json';

        $type = Yii::$app->request->get('type');
        $this->tempModel = new Template();
        switch($type){
            case 'init':
                return $this->_init();
            case 'news_detail':
                return $this->_news_detail();
            case 'page':
                return $this->_page_detail();
            case 'news_list':
                return $this->_news_list();
            case 'home':
                return $this->_home();
            default:
                return ['code'=>0,'msg'=>''];
        }
    }

    //初始化
    private function _init(){
        if($this->tempModel->isGo()){
            return ['code'=>0,'msg'=>'5分钟内请勿重复发布'];
        }

        return ['code'=>1,'msg'=>'<p class="text-success">开始生成...</p>'];
    }

    //初始化
    private function _news_detail(){
        $this->tempModel->build_news_detail();
        if(empty($this->tempModel->error)){
            return ['code'=>1,'msg'=>'<p class="text-success">所有动态详细页面生成成功</p>'];
        }else{
            return ['code'=>0,'msg'=>'<p class="text-danger">'.$this->tempModel->error.'</p>'];
        }
    }

    //初始化
    private function _page_detail(){
        $this->tempModel->build_page();
        if(empty($this->tempModel->error)){
            return ['code'=>1,'msg'=>'<p class="text-success">自定义页面生成成功</p>'];
        }else{
            return ['code'=>0,'msg'=>'<p class="text-danger">'.$this->tempModel->error.'</p>'];
        }
    }

    //初始化
    private function _news_list(){
        $this->tempModel->build_news_list();
        if(empty($this->tempModel->error)){
            return ['code'=>1,'msg'=>'<p class="text-success">动态列表页面生成成功</p>'];
        }else{
            return ['code'=>0,'msg'=>'<p class="text-danger">'.$this->tempModel->error.'</p>'];
        }
    }

    //初始化
    private function _home(){
        $this->tempModel->build();
        if(!empty($this->tempModel->error)){
            return ['code'=>0,'msg'=>'<p class="text-danger">'.$this->tempModel->error.'</p>'];
        }

        $this->tempModel->sitemap();
        if(!empty($this->tempModel->error)){
            return ['code'=>0,'msg'=>'<p class="text-danger">'.$this->tempModel->error.'</p>'];
        }
        return ['code'=>1,'msg'=>'<p class="text-success">首页以及站点地图生成成功</p>'];
    }
}