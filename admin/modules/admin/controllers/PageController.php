<?php

namespace app\modules\admin\controllers;

use app\models\search\SearchPage;
use app\models\SysPage;
use app\modules\admin\components\Template;
use Yii;
use app\modules\admin\components\BaseController;
use app\models\SysLog;

class PageController extends BaseController{
    //新闻列表
    public function actionIndex(){
        $model = new SearchPage();
        return $this->render('index',['model'=>$model]);
    }

    //发布新闻
    public function actionPublish($id = 0){
        if($id == 0){
            $model = new SysPage();
        }else{
            $model = SysPage::getModel($id);
        }

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post())){
                if($model->save()){
                    Yii::$app->session->setFlash(Yii::$app->params['list_success'],['保存成功']);
                    return $this->redirect('/admin/page/publish?id='.$model->id);
                }else{
                    Yii::$app->session->setFlash(Yii::$app->params['list_error'],$model->errors);
                }
            }
        }

        return $this->render('publish',['model'=>$model]);
    }

    //移除
    public function actionDel($id = null,$dirname = null){
        Yii::$app->response->format = 'json';
        if(empty($id) || empty($dirname)){
            return ['code'=>0];
        }

        Yii::$app->db->createCommand()->delete(SysPage::tableName(),'id=:id',[':id'=>$id])->execute();
        SysLog::add('删除自定义页面',3,['id'=>$id]);
        $template = new Template();
        $template->delpage('page',$dirname.'/index');
        return ['code'=>1];
    }
}