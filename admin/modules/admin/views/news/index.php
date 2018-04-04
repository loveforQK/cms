<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\SysNews;

$this->title = SysNews::$typelist[$model->type];
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li>新闻资讯</li>
    <li><?= $this->title ?></li>
</ol>
<?php Pjax::begin() ?>
<div class="panel panel-default">
    <div class="panel-heading">
        文章列表
        <a href="/admin/news/publish?type=<?= $model->type ?>" class="btn pull-right">发布</a>
    </div>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table table-bordered table-hover table-striped',
        ],
        'dataProvider' => $model->search(),
        'filterModel'  => $model,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '10%'],
            ],
            [
                'attribute' => 'title',
                'headerOptions' => ['width' => '30%'],
            ],
            [
                'filter' => SysNews::$statuslist,
                'attribute' => 'status',
                'format' => 'html',
                'headerOptions' => ['width' => '20%'],
                'value' => function ($model, $index, $widget) {
                    return '<span class="'.($model->status == 1?'text-success':'text-danger').'">'.SysNews::$statuslist[$model->status].'</span>';
                },
            ],
            [
                'attribute' => 'home',
                'headerOptions' => ['width' => '10%'],
                'value' => function ($model, $index, $widget) {
                    return $model->home == 1?'首页推荐':'-';
                },
            ],
            [
                'attribute' => 'pubtime',
                'headerOptions' => ['width' => '20%']
            ],
            [
                'header' => '操作',
                'value' => function ($model, $index, $widget) {
                    return '<a href="/admin/news/publish?id='.$model->id.'&type='.$model->type.'" class="btn pull-right" data-pjax="0">编辑</a><a href="javascript:;" class="btn pull-right btn-del" data-pjax="0">删除</a>';
                },
                'format' => 'raw',
                'headerOptions' => ['width' => '20%'],
            ],
        ],
    ]);
    ?>
</div>
<?php Pjax::end() ?>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END'); ?>
    $(document).ready(function(){
        var type = <?= $model->type ?>;
        $('body').delegate('.btn-del','click',function(){
            if(!confirm('确定要删除吗？')){
                return false;
            }
            var obj = $(this).parent().parent('tr'),id = obj.data('key');
            $.getJSON('/admin/news/del',{id:id,type:type},function(data){
                if(data.code == 1){
                    obj.remove();
                }
            });
        });
    });
    <?php $this->endBlock(); ?>
</script>
<?= $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>