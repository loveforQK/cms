<?php
use app\models\SysConfig;

$this->title = '站点设置';

?>
<ol class="breadcrumb">
    <li><a href="/"><i class="icon-home"></i></a></li>
    <li><?= $this->title ?></li>
</ol>
<div class="panel panel-default">
    <div class="panel-heading">
        <?= $this->title ?>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-5">
                <div class="form-group">
                    <label class="control-label">站点标题</label>
                    <input type="text" name="title" value="<?= SysConfig::getValue('title') ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label">站点关键词</label>
                    <textarea name="keywords" class="form-control" rows="3"><?= SysConfig::getValue('keywords') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">站点描述</label>
                    <textarea name="info" class="form-control" rows="3"><?= SysConfig::getValue('info') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="control-label">百度统计代码</label>
                    <textarea name="baidu" class="form-control" rows="3"><?= SysConfig::getValue('baidu') ?></textarea>
                </div>
            </div>
            <div class="col-lg-2"></div>
            <div class="col-lg-5">
                <div class="form-group">
                    <label class="control-label">公司地址</label>
                    <input type="text" name="address" value="<?= SysConfig::getValue('address') ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label">联系电话</label>
                    <input type="text" name="phone" value="<?= SysConfig::getValue('phone') ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label">企业传真</label>
                    <input type="text" name="fax" value="<?= SysConfig::getValue('fax') ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label class="control-label">企业邮箱</label>
                    <input type="text" name="email" value="<?= SysConfig::getValue('email') ?>" class="form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="panel-footer clearfix">
        <button type="button" class="btn pull-right btn-save">保存</button>
    </div>
</div>
<script type="text/javascript">
    <?php $this->beginBlock('JS_END'); ?>
    $(document).ready(function(){
        $('.btn-save').on('click',function(){
            var params = {
                _csrf:$('meta[name="csrf-token"]').attr('content')
            };
            $('.form-control').each(function(){
                var name = $(this).attr('name'),val = $(this).val();
                params[name] = val;
            });
            $('button').prop('disabled',true);
            $.post('/admin/config/save',params,function(data){
                $('button').prop('disabled',false);
                alert(data.msg);
            });
        });
    });
    <?php $this->endBlock(); ?>
</script>
<?= $this->registerJs($this->blocks['JS_END'], yii\web\View::POS_END); ?>
