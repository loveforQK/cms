<?php
$this->title = '忘记密码'
?>
<form id="login-form" class="form-horizontal" action="/admin/login/submit" autocomplete="off">
    <div class="panel panel-default login-box">
        <div class="panel-heading">管理中心</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-3 control-label"></label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="请输入账户名或邮箱" id="account" />
                    <p class="help-block"></p>
                </div>
            </div>
        </div>
        <div class="panel-footer clearfix">
            <button type="button" class="btn btn-success pull-right btn-send">发送</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END') ?>
    $(document).ready(function(){
        $('#account').on('focus',function(){
            $('#account').parent().parent().removeClass('has-error');
            $('#account').next('p').html('');
        });

        $('.btn-send').on('click',function(){
            var account = $.trim($('#account').val()),obj = $(this);
            if(account == ''){
                $('#account').parent().parent().addClass('has-error');
                $('#account').next('p').html('请输入账户名或邮箱');
                return false;
            }
            obj.html('邮件发送中...').prop('disabled',true);
            $.post('/admin/login/check',{_csrf:$('meta[name="csrf-token"]').attr('content'),account:account},function(data){
                obj.html('发送').prop('disabled',false);
                if(data.code == 1){
                    alert('邮件发送成功，请至邮箱中访问密码重置链接！');
                }else{
                    alert(data.msg);
                }
            });
        });
    });
    <?php $this->endBlock(); ?>
</script>
<?php $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>