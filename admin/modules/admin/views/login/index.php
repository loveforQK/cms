<?php
$this->title = '系统登录'
?>
<form id="login-form" class="form-horizontal" action="" autocomplete="off">
    <div class="panel panel-default login-box">
        <div class="panel-heading">管理中心</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-4 control-label">账户名</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="account">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">密&nbsp;&nbsp;&nbsp;码</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="pwd">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">验证码</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" id="verfycode" />
                </div>
                <div class="col-sm-2">
                    <img id="mycaptcha" src="/admin/login/captcha" style="cursor: pointer;">
                </div>
            </div>

        </div>
        <div class="panel-footer clearfix">
            <a href="/admin/login/forget" target="_blank">忘记密码</a>
            <button type="button" class="btn btn-success pull-right btn-login">登录</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END') ?>
    $(document).ready(function(){
        $('.btn-login').on('click',function(){
            login();
        });

        $('#user_name,#user_pwd').on('blur',function(){
            $(this).parent().removeClass('has-error');
            $(this).next('span').html('');
        });

        $('#user_pwd').on('keyup',function(e){
            if(e.keyCode == 13){
                login();
            }
        });

        $('#mycaptcha').on('click',function(){
            reset_captcha();
        });

        //重新加载验证码
        function reset_captcha(){
            $.getJSON('/admin/login/captcha',{refresh:1},function(data){
                $('#mycaptcha').attr('src',data.url);
            });
        }

        function login(){
            var params = {
                _csrf:$('meta[name="csrf-token"]').attr('content'),
                name:$('#account').val(),
                pwd:$('#pwd').val(),
                code:$('#verfycode').val()
            };

            if(params['name'] == ''){
                $('#account').parent().parent().addClass('has-error');
                return false;
            }

            if(params['pwd'] == ''){
                $('#pwd').parent().parent().addClass('has-error');
                return false;
            }

            if(params['code'] == ''){
                $('#verfycode').parent().parent().addClass('has-error');
                return false;
            }

            $('button').prop('disabled',true);
            $.post('/admin/login/submit',params,function(data){
                $('button').prop('disabled',false);
                if(data.code == 1){
                    window.location.href = '/admin/default'
                }else{
                    reset_captcha();
                    alert(data.msg);
                }
            });
        }
    });
    <?php $this->endBlock(); ?>
</script>
<?php $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>