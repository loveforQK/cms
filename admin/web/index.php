<?php

// comment out the following two lines when deployed to production
$xhprof = isset($_GET['xhprof']) && $_GET['xhprof'] == 1?true:false;

if($xhprof){
    xhprof_enable();
}

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('APP_START') or define('APP_START', microtime(true));

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();

if($xhprof){
    $xhprof_data = xhprof_disable();
    include_once "/opt/htdocs/xhprof/xhprof_lib/utils/xhprof_lib.php";
    include_once "/opt/htdocs/xhprof/xhprof_lib/utils/xhprof_runs.php";
    $xhprof_runs = new XHProfRuns_Default('/opt/htdocs/xhprof/source');
    $run_id = $xhprof_runs->save_run($xhprof_data, "xhprof_foo");
    var_dump('Location:http://xhprof.go4cool.com/index.php?run='.$run_id.'&source=xhprof_foo');
}