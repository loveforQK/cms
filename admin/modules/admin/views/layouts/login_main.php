<?php
use yii\helpers\Html;

Yii::$app->getView()->registerCssFile('/admin/static/css/bootstrap.min.css');
Yii::$app->getView()->registerJsFile('/admin/static/js/pixi.min.js',['depends'=>['yii\web\JqueryAsset']]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?>-管理中心</title>
    <?php $this->head() ?>
    <style type="text/css">
        body{
            background: url("/admin/static/img/login_bg.jpg") no-repeat #000;
            min-height: 100vh;
            color:#666;
        }
        .panel{border-radius: 0;box-shadow: 0 0 80px #000;opacity: 0.9;border:none;}
        .login-box{margin-top:150px;}
        .login-box .panel-heading{font-size: 20px;color:#999;background: #339999;color:#efefef;border-radius: 0;}
        canvas{display: block;position: fixed;z-index:-1;}
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4"></div>
        <div class="col-lg-4">
            <?= $content ?>
        </div>
        <div class="col-lg-4"></div>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
