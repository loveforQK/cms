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
<?php $form = ActiveForm::begin(['action' => '/admin/appraisal/save?id='.$model->id]); ?>
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
                <?= $form->field($model, 'position')->textInput() ?>
                <?= $form->field($model, 'phone')->textInput() ?>
                <div class="form-group">
                    <label class="control-label" id="thumb_label">头像</label>
                    <div class="input-group">
                        <input type="text" name="SysAppraisal[thumb]" id="form_thumb" value="<?= $model->thumb ?>" class="form-control">
                        <div class="input-group-addon btn-upload">上传</div>
                    </div>
                    <p class="help-block"></p>
                    <img src="<?= $model->thumb ?>" style="max-width: 150px;max-height:150px;">
                </div>
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
