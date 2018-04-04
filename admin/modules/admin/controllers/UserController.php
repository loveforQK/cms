<?php

namespace app\modules\admin\controllers;

use app\models\SysUser;
use Yii;
use app\modules\admin\components\BaseController;
use app\models\SysLog;

class UserController extends BaseController{
    public function actionIndex(){
        return $this->render('index');
    }

    //用户信息编辑
    public function actionSave($id = 0){
        if(empty($id)){
            $model = new SysUser();
        }else{
            $model = SysUser::getModel($id);
        }

        if(Yii::$app->request->isPost){
            $data = Yii::$app->request->post();
            if(!empty($data['SysUser']['pwd'])){
                $data['SysUser']['pwd'] = Yii::$app->security->generatePasswordHash($data['SysUser']['pwd'],10);
            }else{
                $data['SysUser']['pwd'] = $model->pwd;
            }
            if($model->load($data)){
                if(!$model->save()){
                    Yii::$app->session->setFlash('list_error',$model->getFirstErrors());
                }
            }
        }

        return $this->redirect('/admin/user');
    }

    //移除
    public function actionDel($id = null){
        Yii::$app->response->format = 'json';
        if(empty($id)){
            return ['code'=>0];
        }

        Yii::$app->db->createCommand()->delete(SysUser::tableName(),'id=:id',[':id'=>$id])->execute();
        Yii::$app->cache->delete(SysUser::cacheKey.$id);
        SysLog::add('删除管理员',6,['id'=>$id]);
        return ['code'=>1];
    }
}