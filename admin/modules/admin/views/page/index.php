<?php
use yii\widgets\Pjax;
use yii\grid\GridView;

$this->title = '自定义页面';
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li>页面</li>
</ol>
<?php Pjax::begin() ?>
<div class="panel panel-default">
    <div class="panel-heading">
        文章列表
        <a href="/admin/page/publish" class="btn pull-right">发布</a>
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
                'headerOptions' => ['width' => '20%'],
            ],
            [
                'attribute' => 'dirname',
                'headerOptions' => ['width' => '40%'],
                'format'=>'raw',
                'value'=>function ($model, $index, $widget) {
                    $url = 'http://'.$_SERVER['HTTP_HOST'].'/page/'.$model->dirname.'/';
                    return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
                },
            ],
            [
                'attribute' => 'menu1',
                'headerOptions' => ['width' => '10%'],
            ],
            [
                'attribute' => 'menu2',
                'headerOptions' => ['width' => '10%'],
            ],
            [
                'header' => '操作',
                'value' => function ($model, $index, $widget) {
                    return '<a href="/admin/page/publish?id='.$model->id.'" class="btn pull-right" data-pjax="0">编辑</a><a href="javascript:;" class="btn pull-right btn-del" data-dirname="'.$model->dirname.'" data-pjax="0">删除</a>';
                },
                'format' => 'raw',
                'headerOptions' => ['width' => '10%'],
            ],
        ],
    ]);
    ?>
</div>
<?php Pjax::end() ?>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END'); ?>
    $(document).ready(function(){
        $('body').delegate('.btn-del','click',function(){
            if(!confirm('确定要删除吗？')){
                return false;
            }
            var obj = $(this).parent().parent('tr'),id = obj.data('key'),dirname = $(this).data('dirname');
            $.getJSON('/admin/page/del',{id:id,dirname:dirname},function(data){
                if(data.code == 1){
                    obj.remove();
                }
            });
        });
    });
    <?php $this->endBlock(); ?>
</script>
<?= $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>