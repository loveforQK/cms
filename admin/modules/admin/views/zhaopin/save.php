<?php
use yii\bootstrap\ActiveForm;
use app\widgets\ShowErrors;

$this->title =  '招聘信息';
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li>招聘</li>
    <li><?= $this->title ?></li>
</ol>
<?= ShowErrors::widget([]) ?>
<?php $form = ActiveForm::begin(['action' => '/admin/zhaopin/save?id='.$model->id]); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        招聘信息
        <a href="/admin/zhaopin" class="btn pull-right">返回</a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'title')->textInput() ?>
                <?= $form->field($model, 'sort')->textInput() ?>
            </div>
            <div class="col-lg-9">
                <?= $form->field($model,'content')->widget('yii\ueditor\UEditor',[]) ?>
            </div>
        </div>
    </div>
    <div class="panel-footer clearfix">
        <button type="submit" class="btn pull-right">保存</button>
    </div>
</div>
<?php ActiveForm::end(); ?>
