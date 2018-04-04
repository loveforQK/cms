<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\search\SearchZhaopin;

$searchModel = new SearchZhaopin();
$this->title = '招聘';

?>
    <ol class="breadcrumb">
        <li><a href="/"><i class="icon-home"></i></a></li>
        <li>招聘</li>
    </ol>
<?php Pjax::begin() ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            文章列表
            <a href="/admin/zhaopin/save" class="btn pull-right">发布</a>
        </div>
        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-bordered table-hover table-striped',
            ],
            'dataProvider' => $searchModel->search(),
            'filterModel'  => $searchModel,
            'columns' => [
                [
                    'attribute' => 'sort',
                    'headerOptions' => ['width' => '20%'],
                ],
                [
                    'attribute' => 'title',
                    'headerOptions' => ['width' => '60%'],
                ],
                [
                    'header' => '操作',
                    'value' => function ($model, $index, $widget) {
                        return '<a href="/admin/zhaopin/save?id='.$model->id.'" class="btn pull-right" data-pjax="0">编辑</a><a href="javascript:;" class="btn pull-right btn-del" data-pjax="0">删除</a>';
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
            $('body').delegate('.btn-del','click',function(){
                if(!confirm('确定要删除吗？')){
                    return false;
                }
                var obj = $(this).parent().parent('tr'),id = obj.data('key');
                $.getJSON('/admin/zhaopin/del',{id:id},function(data){
                    if(data.code == 1){
                        obj.remove();
                    }
                });
            });
        });
        <?php $this->endBlock(); ?>
    </script>
<?= $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>