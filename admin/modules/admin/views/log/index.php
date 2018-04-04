<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\search\SearchLog;

$this->title = '系统日志';
$search = new SearchLog();
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li><?= $this->title ?></li>
</ol>
<?php Pjax::begin() ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            日志列表
        </div>
        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-bordered table-hover table-striped',
            ],
            'dataProvider' => $search->search(),
            'filterModel'  => $search,
            'columns' => [
                [
                    'attribute' => 'name',
                    'headerOptions' => ['width' => '30%'],
                ],
                [
                    'attribute' => 'nickname',
                    'headerOptions' => ['width' => '10%']
                ],
                [
                    'attribute' => 'type',
                    'filter'=>\app\models\SysLog::$typelist,
                    'headerOptions' => ['width' => '10%'],
                    'value' => function ($model, $index, $widget) {
                        return \app\models\SysLog::$typelist[$model->type];
                    },
                ],
                [
                    'attribute' => 'ip',
                    'headerOptions' => ['width' => '10%']
                ],
                [
                    'attribute' => 'addtime',
                    'headerOptions' => ['width' => '20%']
                ],
                [
                    'header' => Yii::t('app',"操作"),
                    'value' => function ($model, $index, $widget) {
                        return '<a href="javascript:;" class="btn pull-right btn-edit" data-pjax="0">查看</a><pre class="hide">'.var_export(json_decode($model->data,true),true).'</pre>';
                    },
                    'format' => 'raw',
                    'headerOptions' => ['width' => '20%'],
                ],
            ],
        ]);
        ?>
    </div>
<?php Pjax::end() ?>
<div id="myModel" class="modal fade" tabindex="-2" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="panel panel-default">
            <div class="panel-heading">
                数据片段
            </div>
            <div class="panel-body">
                <pre></pre>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END'); ?>
    $(document).ready(function(){
        $('body').delegate('.btn-edit','click',function(){
            $('#myModel').find('pre').html($(this).next('pre').html());
            $('#myModel').modal('show');
        });
    });
    <?php $this->endBlock(); ?>
</script>
<?= $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>