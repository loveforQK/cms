<?php
namespace app\modules\admin\controllers;

use app\models\SysLog;
use app\models\SysUser;
use yii\web\Controller;
use Yii;
use geetest\GeetestLib;
use app\modules\admin\components\Tools;

class LoginController extends Controller{
    public $layout = 'login_main';

    public function actions(){
        return [
            'captcha'=>[
                'class'=>'app\modules\admin\actions\MyCaptchaAction',
                'width'=>100,
                'height'=>40,
                'minLength'=>4,
                'maxLength'=>6,
            ]
        ];
    }

    //首页
    public function actionIndex(){
		if(!Yii::$app->user->isGuest){
            return $this->redirect('/admin/default');
        }
        return $this->render('index');
    }

    //忘记密码
    public function actionForget(){
        return $this->render('forget');
    }

    //验证码初始化
    public function actionInitCaptcha(){
        $GtSdk = new GeetestLib(Yii::$app->params['geetest']['CAPTCHA_ID'], Yii::$app->params['geetest']['PRIVATE_KEY']);
        $user_id = uniqid();
        $status = $GtSdk->pre_process($user_id);
        Yii::$app->session->set('gtserver',$status);
        Yii::$app->session->set('user_id',$user_id);
        return $GtSdk->get_response_str();
    }

    //校验账户邮箱数据
    public function actionCheck(){
        Yii::$app->response->format = 'json';
        if(!Yii::$app->request->isPost){
            return ['code'=>0,'msg'=>'请求方式不正确！'];
        }

        $account = Yii::$app->request->post('account');
        if(empty($account)){
            return ['code'=>0,'msg'=>'请输入账户名或邮箱！'];
        }

        $info = SysUser::find()->select('id,email,token')->where('username=:username or email=:username',[':username'=>$account])->asArray()->one();
        if(empty($info)){
            return ['code'=>0,'msg'=>'账户或邮箱不存在！'];
        }

        if(!empty($info['token'])){
            $id = Yii::$app->cache->get(SysUser::cacheKey.'Forget'.$info['token']);
            if($id == $info['id']){
                return ['code'=>0,'msg'=>'邮件已发送，请至邮箱中点击重置密码链接！'];
            }
        }

        //生成token
        $token = 'T-'.strtoupper(md5(microtime(true))).'-'.Tools::getUUID(15,'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789');
        $url = 'http://'.$_SERVER['HTTP_HOST'].'/admin/login/reset?token='.$token;
        $mail= Yii::$app->mailer->compose();
        $mail->setTo($info['email']);
        $mail->setSubject("管理中心-密码重置");
        $mail->setHtmlBody("<h4>请在浏览器中访问&nbsp;&nbsp;<a href='".$url."' target='_blank'>".$url."</a></h4>");
        if($mail->send()){
            Yii::$app->db->createCommand()->update(SysUser::tableName(),['token'=>$token],'id=:id',[':id'=>$info['id']])->execute();
            Yii::$app->cache->set(SysUser::cacheKey.'Forget'.$token,$info['id'],86400);
            return ['code' => 1, 'msg' => '邮件发送成功'];
        }else {
            return ['code' => 0, 'msg' => '邮件发送异常'];
        }
    }

    //密码重置
    public function actionReset($token = null){
        $msg = 'Token有误';
        if(Tools::checkLetter($token,'/^(T-)[A-Z0-9]{32}-[A-Z0-9]{15}$/')){
            $id = Yii::$app->cache->get(SysUser::cacheKey.'Forget'.$token);
            if(empty($id)){
                $msg = 'Token已过期';
            }else{
                Yii::$app->session->set(SysUser::cacheKey.'_FID',$id);
                Yii::$app->session->set(SysUser::cacheKey.'_FTOKEN',$token);
                $msg = '';
            }
        }
        return $this->render('reset',['msg'=>$msg]);
    }

    //更新用户密码
    public function actionChange(){
        Yii::$app->response->format = 'json';
        if(!Yii::$app->request->isPost){
            return ['code'=>0,'msg'=>'请求方式不正确！'];
        }

        $new_pwd = Yii::$app->request->post('new_pwd');
        $confirm_pwd = Yii::$app->request->post('confirm_pwd');
        if(empty($new_pwd) || empty($confirm_pwd)){
            return ['code'=>0,'msg'=>'密码不能为空！'];
        }

        if($new_pwd != $confirm_pwd){
            return ['code'=>0,'msg'=>'两次密码不一致！'];
        }

        $id = Yii::$app->session->get(SysUser::cacheKey.'_FID');
        $token = Yii::$app->session->get(SysUser::cacheKey.'_FTOKEN');
        if(empty($id) || empty($token)){
            return ['code'=>0,'msg'=>'Token已过期！'];
        }

        $flag = Yii::$app->db->createCommand()->update(SysUser::tableName(),['token'=>'','pwd'=>Yii::$app->security->generatePasswordHash($new_pwd,10)],'id=:id',[':id'=>$id])->execute();
        if($flag !== false){
            Yii::$app->session->remove(SysUser::cacheKey.'_FID');
            Yii::$app->session->remove(SysUser::cacheKey.'_FTOKEN');
            Yii::$app->cache->delete(SysUser::cacheKey.'Forget'.$token);
            return ['code'=>1,'msg'=>'重置成功'];
        }else{
            return ['code'=>1,'msg'=>'重置失败'];
        }
    }

    //账户登录
    public function actionSubmit(){
        Yii::$app->response->format = 'json';
        $post = Yii::$app->request->post();

        $account = isset($post['name'])?$post['name']:'';
        $pwd     = isset($post['pwd'])?$post['pwd']:'';
        $code    = isset($post['code'])?$post['code']:'';
        if(!Tools::checkLetter($account,"/^[a-zA-Z0-9|@|_|-|.]+$/") || !Tools::checkLetterAndNumber($pwd)){
            return ['code'=>0,'msg'=>'账户名或密码格式不正确'];
        }

        if(!Tools::checkLetter($code,"/^[a-zA-Z0-9]{4,6}$/")){
            return ['code'=>0,'msg'=>'请填写正确的验证码'];
        }

        //验证码检测
        $ca = Yii::$app->createController('admin/login/captcha');
        if($ca !== false) {
            list($controller, $actionID) = $ca;
            $captcha = $controller->createAction($actionID);
            if(!$captcha->validate($code,false)){
                return ['code'=>0,'msg'=>'请填写正确的验证码'];
            }
        }else{
            return ['code'=>0,'msg'=>'请填写正确的验证码'];
        }

        if(!SysUser::login($account,$pwd)){
            return ['code'=>0,'msg'=>'账户或密码不正确'];
        }

        SysLog::add('账户登录',1,['username'=>$account]);

        return ['code'=>1,'msg'=>'登录成功'];
    }

    //注销
    public function actionOut(){
        if(!Yii::$app->user->isGuest){
            Yii::$app->user->logout();
        }

        return $this->redirect('/admin/login');
    }

    //验证码验证
    private function _veryCaptcha($challenge,$validate,$seccode){
        if(empty($challenge) || empty($validate) || empty($seccode)){
            return false;
        }

        $GtSdk   = new GeetestLib(Yii::$app->params['geetest']['CAPTCHA_ID'], Yii::$app->params['geetest']['PRIVATE_KEY']);
        $status  = Yii::$app->session->get('gtserver');
        $user_id = Yii::$app->session->get('user_id');

        if ($status == 1) {   //服务器正常
            $result = $GtSdk->success_validate($challenge, $validate, $seccode, $user_id);
            if($result){
                return true;
            }else{
                return false;
            }
        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($challenge,$validate,$seccode)) {
                return true;
            }else{
                return false;
            }
        }
    }
}