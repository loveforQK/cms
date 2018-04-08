<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\models\SysNews;

AppAsset::register($this);
$maintitle = '管理中心';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?>-<?= $maintitle ?></title>
    <?php $this->head() ?>
</head>
<body>
<nav class="navbar">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript:;"><?= $maintitle ?></a>
        </div>
        <ul class="nav navbar-nav">
            <li><a class="all-change" href="/admin/default"><i class="icon-dashboard"></i>首页</a></li>
            <li>
                <a class="all-change" href="/admin/news"><i class="icon-globe"></i>文章</a>
            </li>
            <li class="btn-group" role="group">
                <a class="all-change" href="/admin/user"><i class="icon-user"></i>管理员</a>
            </li>
            <li class="btn-group" role="group">
                <a class="all-change" href="/admin/log"><i class="icon-list"></i>系统日志</a>
            </li>
        </ul>
        <ul class="nav navbar-nav pull-right">
            <li class="active"><a class="all-change" href="/admin/login/out"><i class="icon-user"></i><?= !empty(Yii::$app->user->getIdentity()->nickname)?Yii::$app->user->getIdentity()->nickname:strtoupper(Yii::$app->user->getIdentity()->username) ?>，注销</a></li>
        </ul>
    </div>
</nav>
<div class="container-fluid"><div class="well" style="overflow: hidden;"><?= $content ?></div></div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>