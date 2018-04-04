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
                <a class="all-change" href="#"><i class="icon-globe"></i>动态</a>
                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="btnGroupDrop1">
                    <li><a href="/admin/news?type=<?= SysNews::TYPE_BUSINESS ?>">机构动态</a></li>
                    <li><a href="/admin/news?type=<?= SysNews::TYPE_INDUSTRY ?>">行业新闻</a></li>
                </ul>
            </li>
            <li class="btn-group" role="group">
                <a class="all-change" href="/admin/zhaopin"><i class="icon-github-alt"></i>招聘</a>
            </li>
            <li class="btn-group" role="group">
                <a class="all-change" href="/admin/appraisal"><i class="icon-group"></i>鉴定人员</a>
            </li>
            <li class="btn-group" role="group">
                <a class="all-change" href="/admin/page"><i class="icon-file-alt"></i>页面</a>
            </li>
            <li class="btn-group" role="group">
                <a class="all-change" href="javascript:;"><i class="icon-cogs"></i>系统</a>
                <ul class="dropdown-menu dropdown-menu-left" aria-labelledby="btnGroupDrop1">
                    <li><a href="/admin/config">站点设置</a></li>
                    <li><a href="/admin/user">管理员</a></li>
                    <li><a href="/admin/log">系统日志</a></li>
                </ul>
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