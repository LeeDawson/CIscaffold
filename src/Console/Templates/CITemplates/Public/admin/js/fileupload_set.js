/**
 * 上传文件控件的方法配置，控制不同上传控件的样式等
 * 上传图片：fileUploadSet_img()
 * 参数：comp_id：type="file"的input上设置的id
 *       files_box_id：盛放上传的文件显示区域的id
 *       url：接口url
 *       limit_num:限制传图的张数 >0时：限制相应的张数；-1时：不限制；其他值：不能传图
 * **/
//一个全局变量用来保存上传的图片信息
var upload_img_obj = {};
var fileUploadSet_img = function (comp_id, files_box_id, limit_num, url) {
    upload_img_obj[files_box_id] = [];
    // console.log(upload_img_obj);
    //每一个上传文件列里面的一个透明的提交按钮，存在，不可见
    // uploadButton = $('<div/>')
    //     .addClass('hide_btn')
    //     .on('click', function () {
    //         var $this = $(this),
    //             data = $this.data();
    //         $this
    //             .off('click')
    //             .on('click', function () {
    //                 $this.remove();
    //                 data.abort();
    //             });
    //         // console.log('数据提交');
    //         data.submit().always(function () {          //数据提交
    //             //数据提交后无论怎样都会执行
    //             $this.remove();
    //         });
    //     });
    //每一个上传文件列里面的删除按钮
    var closeButton = $("<div class='closebtn close_btn'><i class='iconfont cha-icon'></i></div>")
                        .on('click', function () {
                            var deleteinfo = $(this).closest(".item_box").attr('imginfo');
                            var deleteindex = upload_img_obj[files_box_id].indexOf(deleteinfo);
                            if (deleteindex > -1) {
                                upload_img_obj[files_box_id].splice(deleteindex, 1);
                            }
                            // console.log(upload_img_obj);
                            $(this).closest(".item_box").remove();
                        });
    $('#'+comp_id).fileupload({
        url: url,                                                  //服务器API
        dataType: 'json',                                          //期望从服务器得到json类型的返回数据
        autoUpload: true,                                         //是否自动上传
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,              //文件格式限制
        maxFileSize: 999000,                                      //文件最大
        previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true
    }).on('fileuploadadd', function (e, data) {                 //在图片添加时会触发事件fileuploadadd
        // alert("fileuploadadd");

        // var file_name = data.files[0].name;


        // $.each(data.files, function (index, file) {             //上传的文件在data里，data.files是个数组，里面存放着file
        //     var node = $(data.context);
        //     if (!index) {
        //         node
        //             .append(uploadButton.clone(true).data(data));
        //     }
        // });

    }).on('fileuploadprocessalways', function (e, data) {     //在文件添加过程中触发，在fileuploadadd之后
        // alert("这是fileuploadprocessalways");

        var index = data.index,
            file = data.files[index];
        if (file.error) {
            // node
            //     .append('<br>')
            //     .append($('<span class="text-danger"/>').text(file.error));
            // $(data.context).remove();
            alert('上传失败');
        }
    }).on('fileuploaddone', function (e, data) {
        // alert("这是fileuploaddone");
        // var this_parentid = $(data.context).closest('.files_box').attr("id");
        // if(upload_img_obj[this_parentid]){
        //     var curlength =  upload_img_obj[this_parentid].length;
        //     if(curlength >= limit_num){
        //         alert('最多只能传图'+limit_num+'张');
        //         return false;
        //     }
        // }

        $.each(data.result.files, function (index, file) {
            if(upload_img_obj[files_box_id]){
                // console.log('有了');
                if(upload_img_obj[files_box_id].length < limit_num){
                    var str = "<div class='img_box item_box'>" +
                        "           <div class='imgup'>" +
                        "           </div>" +
                        "           <div class='infobox'>" +
                        "                <div class='loadstate'>" +
                        "                     <span></span>" +
                        "                 </div>" +
                        "             </div>" +
                        "       </div>";
                    data.context = $(str).appendTo('#'+files_box_id);

                    // var index = data.index,
                    var _file = data.files[index],
                        node = $('.imgup',$(data.context));
                    if (_file.preview) {             //加预览图片的canvas, file.preview是显示图片的canvas
                        $(_file.preview).css({"position":"absolute","left":"0","transformOrigin":"0% 0%","transform":"scale(1.35,1.35)"}); //调整canvas尺寸使适合
                        node.prepend(_file.preview);
                    }

                    var succ = $("<i class='iconfont succ-icon'></i>");
                    $('.loadstate',$(data.context)).prepend(succ);
                    $('.infobox',$(data.context)).prepend(closeButton.clone(true));
                    $('.loadstate span',$(data.context)).text('上传成功');

                    upload_img_obj[files_box_id].push(file.name);
                    $(data.context).attr("imginfo",file.name);
                    // console.log(file.name);
                    // console.log(upload_img_obj);
                    // console.log($(data.context));
                    // console.log(upload_img_obj[this_parentid].length);

                }else{
                    // alert('最多只能传图'+limit_num+'张');
                }

            }else{
                // console.log('没有');
            }



        });
    }).on('fileuploadfail', function (e, data) {
        // alert("这是fileuploadfail");
        $.each(data.files, function (index) {
            // var error = $('<i class="fail-icon"></i>');
            // $('.loadstate',$(data.context)).prepend(error);
            // $('.loadstate span',$(data.context)).text('上传失败');
            // $('.infobox',$(data.context)).prepend(closeButton.clone(true));
            $(data.context).remove();
            alert('上传失败');
        });
    }).prop('disabled', !$.support.fileInput)
        .parent().addClass($.support.fileInput ? undefined : 'disabled');

    //限制传图片
    $('#'+comp_id).on('click',function () {
        if(limit_num > 0){
            if(upload_img_obj[files_box_id].length > limit_num-1){
                alert('最多只能传图'+limit_num+'张');
                return false;
            }
        }else if(limit_num === -1){
            //无限制传图
        }else{
            //参数不为-1或>0的值时，不能传图
            alert('此处不能传图');
            return false;
        }

    })

};



