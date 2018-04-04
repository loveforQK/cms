<?php
use app\models\SysConfig;
?>
<meta charset="utf-8">
<title><?= isset($title)?$title.'-':'' ?><?= SysConfig::getValue('title') ?></title>
<meta name="keywords" content="<?= SysConfig::getValue('keywords') ?>" />
<meta name="description" content="<?= SysConfig::getValue('info') ?>" />
<meta name="copyright" content="" />