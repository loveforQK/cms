<?php
use app\models\SysNews;
use app\models\SysLog;

$this->title = '控制面板';
$business = SysNews::find()->select('title,pubtime')->where('type='.SysNews::TYPE_BUSINESS)->orderBy('pubtime desc')->limit(10)->asArray()->all();
$industry = SysNews::find()->select('title,pubtime')->where('type='.SysNews::TYPE_INDUSTRY)->orderBy('pubtime desc')->limit(10)->asArray()->all();
$log = SysLog::find()->select('name,addtime')->where('type<>1')->orderBy('addtime desc')->limit(10)->asArray()->all();
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li><?= $this->title ?></li>
</ol>
<div class="row">
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">最新企业新闻<a class="pull-right" href="/admin/news?type=1">更多</a></div>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr><th width="70%">标题</th><th width="30%">发布时间</th></tr>
                </thead>
                <tbody>
                <?php foreach($business as $val){ ?>
                <tr><td><?= $val['title'] ?></td><td><?= $val['pubtime'] ?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">最新行业新闻<a class="pull-right" href="/admin/news?type=2">更多</a></div>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr><th width="70%">标题</th><th width="30%">发布时间</th></tr>
                </thead>
                <tbody>
                <?php foreach($industry as $val){ ?>
                    <tr><td><?= $val['title'] ?></td><td><?= $val['pubtime'] ?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">站点资源生成发布</div>
            <div class="panel-body">
                <div class="build_result"></div>
            </div>
            <div class="panel-footer clearfix">
                <button class="btn btn-info btn-build pull-right">一键生成</button>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">最近操作日志<a class="pull-right" href="/admin/log">更多</a></div>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                <tr><th width="60%">标题</th><th width="40%">执行时间</th></tr>
                </thead>
                <tbody>
                <?php foreach($log as $val){ ?>
                    <tr><td><?= $val['name'] ?></td><td><?= $val['addtime'] ?></td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel panel-default">
            <div class="panel-heading">注意事项</div>
            <ul class="list-group">
                <li class="list-group-item">添加、更新、删除新闻、页面等数据资源后，需要在此页面点击<button class="btn btn-info">一键生成</button>，发布页面资源</li>
                <li class="list-group-item">上传图片格式仅限：PNG、JPG、JPEG、GIF、BMP</li>
                <li class="list-group-item">上传图片文件大小仅限2M以内</li>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END'); ?>
    $(document).ready(function(){
        $('.btn-build').on('click',function(){
            init();

        });

        //初始化
        function init(){
            $('.build_result').html('');
            $.getJSON('/admin/default/start',{type:'init'},function(data){
                if(data.code == 1){
                    build_news_detail();
                }
                $('.build_result').prepend(data.msg);
            });
        }

        //生成评测详细页面
        function build_news_detail(){
            $.getJSON('/admin/default/start',{type:'news_detail'},function(data){
                if(data.code == 1){
                    build_page_detail();
                }
                $('.build_result').prepend(data.msg);
            });
        }

        //生成评测列表
        function build_page_detail(){
            $.getJSON('/admin/default/start',{type:'page'},function(data){
                if(data.code == 1){
                    build_news_list();
                }
                $('.build_result').prepend(data.msg);
            });
        }

        //生成攻略详细
        function build_news_list(){
            $.getJSON('/admin/default/start',{type:'news_list'},function(data){
                if(data.code == 1){
                    build_home();
                }
                $('.build_result').prepend(data.msg);
            });
        }

        //生成首页与站点地图
        function build_home(){
            $.getJSON('/admin/default/start',{type:'home'},function(data){
                $('.build_result').prepend(data.msg);
            });
        }
    });
    <?php $this->endBlock(); ?>
</script>
<?= $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>