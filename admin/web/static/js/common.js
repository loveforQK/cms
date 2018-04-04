var btnObj,uploadCallback = function(data){
    if(data.state == 'SUCCESS'){
        btnObj.prev().val(data.url);
        btnObj.parent().parent().prev().removeClass('hide');
        btnObj.parent().next().next().attr('src',data.url).removeClass('hide');
    }else{
        btnObj.parent().next().html(data.state);
        btnObj.parent().parent().addClass('has-error');
    }
};

$.tools = {
    initUpload:function(){
        if($('.btn-upload').length == 0){
            return false;
        }

        $('body').append('<div class="hide"><form id="uplaodForm" action="/admin/upload?action=uploadimage&parent=uploadCallback" method="post" enctype="multipart/form-data" target="uploadIframe"><input type="file" id="uploadFile" name="upfile" value=""></form><iframe name="uploadIframe"></iframe></div>');

        $('.btn-upload').click(function(){
            btnObj = $(this);
            $('#uploadFile').click();
        });

        $('body').delegate('#uploadFile','change',function(){
            $('#uplaodForm').submit();
        });
    }
};

$(document).ready(function(){
    $.tools.initUpload();
});