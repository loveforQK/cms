<?php

namespace app\modules\admin\controllers;

use app\models\SysAppraisal;
use app\modules\admin\components\Template;
use Yii;
use app\modules\admin\components\BaseController;
use app\models\SysLog;

class AppraisalController extends BaseController{
    //新闻列表
    public function actionIndex($type = null){
        return $this->render('index');
    }

    //发布新闻
    public function actionSave($id = 0){
        if(empty($id)){
            $model = new SysAppraisal();
        }else{
            $model = SysAppraisal::getModel($id);
        }

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post())){
                if($model->save()){
                    Yii::$app->session->setFlash(Yii::$app->params['list_success'],['保存成功']);
                    return $this->redirect('/admin/appraisal/save?id='.$model->id);
                }else{
                    Yii::$app->session->setFlash(Yii::$app->params['list_error'],$model->errors);
                }
            }
        }

        return $this->render('save',['model'=>$model]);
    }

    //移除
    public function actionDel($id = null){
        Yii::$app->response->format = 'json';
        if(empty($id)){
            return ['code'=>0];
        }

        Yii::$app->db->createCommand()->delete(SysAppraisal::tableName(),'id=:id',[':id'=>$id])->execute();
        SysLog::add('删除招聘',6,['id'=>$id]);
        return ['code'=>1];
    }
}