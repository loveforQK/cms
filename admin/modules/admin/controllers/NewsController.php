<?php

namespace app\modules\admin\controllers;

use app\models\SysNews;
use app\modules\admin\components\Template;
use Yii;
use app\modules\admin\components\BaseController;
use app\models\SysLog;

class NewsController extends BaseController{
    //新闻列表
    public function actionIndex(){
        return $this->render('index');
    }

    //发布新闻
    public function actionPublish($id = 0,$type = null){
        $type = empty($type)?SysNews::TYPE_BUSINESS:$type;
        if($id == 0){
            $model = new SysNews();
            $model->type = $type;
        }else{
            $model = SysNews::getModel($id);
        }

        if(Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            $data['SysNews']['home'] = intval($data['SysNews']['home']);
            $data['SysNews']['status'] = intval($data['SysNews']['status']);
            if($model->load($data)){
                if($model->save()){
                    Yii::$app->session->setFlash(Yii::$app->params['list_success'],['保存成功']);
                    return $this->redirect('/admin/news/publish?type='.$model->type.'&id='.$model->id);
                }else{
                    Yii::$app->session->setFlash(Yii::$app->params['list_error'],$model->errors);
                }
            }
        }

        return $this->render('publish',['model'=>$model]);
    }

    //移除
    public function actionDel($id = null){
        Yii::$app->response->format = 'json';
        if(empty($id) || empty($type)){
            return ['code'=>0];
        }

        Yii::$app->db->createCommand()->delete(SysNews::tableName(),'id=:id',[':id'=>$id])->execute();
        SysLog::add('删除'.SysNews::$typelist[$type],2,['id'=>$id]);
        $template = new Template();
        $template->delpage('business_detail',$id);
        return ['code'=>1];
    }
}