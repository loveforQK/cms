<?php
use app\models\SysNews;
use yii\bootstrap\ActiveForm;
use yii\widgets\Pjax;

$this->title = SysNews::$typelist[$model->type];
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li>新闻资讯</li>
    <li><?= $this->title ?></li>
</ol>
<?php $form = ActiveForm::begin(['action' => '/admin/news/publish?type='.$model->type.'&id='.$model->id]); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        文章信息
        <a href="/admin/news?type=<?= $model->type ?>" class="btn pull-right">返回</a>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3">
                <?= $form->field($model, 'title')->textInput() ?>
                <?= $form->field($model, 'status')->dropDownList(SysNews::$statuslist) ?>
                <?= $form->field($model, 'home')->dropDownList(['不推荐','推荐'])->hint('若设置首页推荐，请设置状态“发布”同时上传缩略图') ?>
                <div class="form-group">
                    <label class="control-label">缩略图（270*270）</label>
                    <div class="input-group">
                        <input type="text" name="SysNews[thumb]" value="<?= $model->thumb ?>" class="form-control">
                        <div class="input-group-addon btn-upload">上传</div>
                    </div>
                    <p class="help-block"></p>
                    <img<?= empty($model->thumb)?' class="hide"':'' ?> src="<?= $model->thumb ?>" style="max-width: 150px;max-height:150px;">
                </div>
                <?= $form->field($model, 'pubtime')->textInput(['readonly'=>"readonly"]) ?>
                <?= $form->field($model, 'info')->textarea() ?>
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
