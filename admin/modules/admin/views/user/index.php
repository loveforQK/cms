<?php
use yii\widgets\Pjax;
use yii\grid\GridView;
use app\models\SysUser;
use app\models\search\SearchUser;
use yii\bootstrap\ActiveForm;

$this->title = '管理员';
$search = new SearchUser();
$formModel = new SysUser();
$errors = Yii::$app->session->getFlash('list_error',[],true);
?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li><?= $this->title ?></li>
</ol>
<?php foreach($errors as $val){ ?>
<div class="alert alert-danger alert-dismissible fade in" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
    <?= $val ?>
</div>
<?php } ?>
<?php Pjax::begin() ?>
<div class="panel panel-default">
    <div class="panel-heading">
        管理员列表
        <a href="javascript:;" class="btn pull-right btn-add">添加</a>
    </div>
    <?= GridView::widget([
        'tableOptions' => [
            'class' => 'table table-bordered table-hover table-striped',
        ],
        'dataProvider' => $search->search(),
        'filterModel'  => $search,
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['width' => '10%'],
            ],
            [
                'attribute' => 'username',
                'headerOptions' => ['width' => '25%'],
            ],
            [
                'attribute' => 'nickname',
                'headerOptions' => ['width' => '25%']
            ],
            [
                'attribute' => 'email',
                'headerOptions' => ['width' => '20%']
            ],
            [
                'header' => Yii::t('app',"操作"),
                'value' => function ($model, $index, $widget) {
                    return '<a href="javascript:;" class="btn pull-right btn-edit" data-pjax="0">编辑</a><a href="javascript:;" class="btn pull-right btn-del" data-pjax="0">删除</a>';
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
        <?php $form = ActiveForm::begin(['action' => '','id'=>'user_form']); ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                用户信息
            </div>
            <div class="panel-body">
                <?= $form->field($formModel, 'username')->textInput() ?>
                <?= $form->field($formModel, 'nickname')->textInput() ?>
                <?= $form->field($formModel, 'pwd')->passwordInput() ?>
                <?= $form->field($formModel, 'email')->textInput()->hint('请正确填写，便于后期找回密码') ?>
            </div>
            <div class="panel-footer clearfix">
                <button type="submit" class="btn pull-right">保存</button>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END'); ?>
    var url = '/admin/user/save';
    $(document).ready(function(){
        $('.btn-add').on('click',function(){
            id = 0;
            $('#user_form')[0].reset();
            $('.form-group').removeClass('has-error');
            $('.help-block').html('');
            $('#user_form').attr('action',url);
            $('#myModel').modal('show');
        });

        $('body').delegate('.btn-edit','click',function(){
            var obj = $(this).parent().parent('tr');
            $('#user_form')[0].reset();
            $('#user_form').attr('action',url+'?id='+obj.data('key'));
            $('#sysuser-username').val(obj.children('td').eq(1).html());
            $('#sysuser-nickname').val(obj.children('td').eq(2).html());
            $('#sysuser-email').val(obj.children('td').eq(3).html());
            $('.form-group').removeClass('has-error');
            $('.help-block').html('');
            $('#myModel').modal('show');
        });

        $('body').delegate('.btn-del','click',function(){
            if(!confirm('确定要删除吗？')){
                return false;
            }

            var obj = $(this).parent().parent('tr');
            id = obj.data('key');

            $.getJSON('/admin/user/del',{id:id},function(data){
                if(data.code == 1){
                    obj.remove();
                }
            });
        });
    });
    <?php $this->endBlock(); ?>
</script>
<?= $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>