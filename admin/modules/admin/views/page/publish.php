<?php
use yii\bootstrap\ActiveForm;
use app\widgets\ShowErrors;

$this->title =  '页面详情';
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li>页面</li>
    <li><?= $model->title ?></li>
</ol>
<?= ShowErrors::widget([]) ?>
<?php $form = ActiveForm::begin(['action' => '/admin/page/publish?id='.$model->id]); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        文章信息
        <a href="/admin/page" class="btn pull-right">返回</a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'title')->textInput() ?>
                <div class="form-group">
                    <label class="control-label">路径</label>
                    <div class="input-group">
                        <div class="input-group-addon">http://<?= $_SERVER['HTTP_HOST'] ?>/page/</div>
                        <input type="text" class="form-control" name="SysPage[dirname]" value="<?= $model->dirname ?>">
                    </div>
                    <p class="help-block">* 仅限字母、数字、下划线组合，50个字符以内</p>
                </div>
                <?= $form->field($model, 'menu1')->dropDownList(['','1','2','3']) ?>
                <?= $form->field($model, 'menu2')->dropDownList(['','1','2','3','4']) ?>
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
