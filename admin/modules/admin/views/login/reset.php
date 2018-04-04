<?php
$this->title = '重置密码'
?>
    <form id="login-form" class="form-horizontal" action="" autocomplete="off">
        <div class="panel panel-default login-box">
            <div class="panel-heading">管理中心</div>
            <div class="panel-body">
                <?php if(empty($msg)){ ?>
                <div class="form-group">
                    <label class="col-sm-3 control-label">新密码</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" placeholder="新密码" id="new_pwd" />
                        <p class="help-block"></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">确认密码</label>
                    <div class="col-sm-6">
                        <input type="password" class="form-control" placeholder="确认密码" id="confirm_pwd" />
                        <p class="help-block"></p>
                    </div>
                </div>
                <?php }else{ ?>
                    <h4 class="text-danger"><?= $msg ?></h4>
                <?php } ?>
            </div>
            <?php if(empty($msg)){ ?>
            <div class="panel-footer clearfix">
                <button type="button" class="btn btn-success pull-right btn-save">重置</button>
            </div>
            <?php } ?>
        </div>
    </form>
    <script type="text/javascript">
        <?php $this->beginBlock('JS_END') ?>
        $(document).ready(function(){
            $('.form-control').on('focus',function(){
                $(this).parent().parent().removeClass('has-error');
                $(this).next('p').html('');
            });

            $('.btn-save').on('click',function(){
                var obj = $(this),param = {_csrf:$('meta[name="csrf-token"]').attr('content')},error = false;
                $('.form-control').each(function(){
                    var a = $(this),val = $.trim(a.val()),label = a.parent().prev().html(),name = a.attr('id');
                    if(val == ''){
                        a.next().html(label+'不能为空');
                        a.parent().parent().addClass('has-error');
                        error = true;
                    }else{
                        param[name] = val;
                    }
                });
                if(param['new_pwd'] != param['confirm_pwd']){
                    error = true;
                    $('#confirm_pwd').next().html('两次密码不一致');
                    $('#confirm_pwd').parent().parent().addClass('has-error');
                }
                if(error){
                    return false;
                }
                obj.prop('disabled',true);
                $.post('/admin/login/change',param,function(data){
                    obj.prop('disabled',false);
                    if(data.code == 1){
                        alert('重置成功');
                        window.location.href = '/admin/login';
                    }else{
                        alert(data.msg);
                    }
                });
            });
        });
        <?php $this->endBlock(); ?>
    </script>
<?php $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>