$(function(){
    /**
     * 上传单个封面图片
     * ①:给父级盒子加id(upload-single-img-container)
     * ②:必须要在上传文件的那个按钮选项中加入id(upload-single-img-btn)
     * ③:要在这个按钮的兄弟级ul加id(upload-single-img-file-list);
     * 例:
     <div id="upload-single-img-container" class="plupload-container">
         <button id="upload-single-img-btn"  class="btn btn-danger bg-main">上传图片</button>
         <ul id="upload-single-img-file-list" class="file-list"></ul>
     </div>
     *
     */
    //实例化一个plupload上传对象
    var uploader_single_img = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4', //用来指定上传方式，默认选择最合适的。(可以不配置)
        browse_button : 'upload-single-img-btn', //触发文件选择对话框的按钮，为那个元素id
        url : 'upload.php', //服务器端的上传页面地址
        flash_swf_url : 'js/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : 'js/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        multi_selection: false, //是否可以在文件浏览对话框中选择多个文件

        // 筛选条件
        filters : {
            max_file_size : '2mb',//限制上传图片的大小
            mime_types: [
                {title : "Image files", extensions : "jpeg,jpg,gif,png"}, //限制文件类型
                // {title : "Zip files", extensions : "zip"}
            ],
            prevent_duplicates:true  //阻止选择则重复的文件
        },

        init: {
            PostInit: function() {
                $("#upload-single-img-btn").on("click",function(){
                    uploader_single_img.start();
                    return false;
                })
            },

             FilesAdded: function(up, files) {
                 console.log(uploader_single_img.files.length);
                 if(uploader_single_img.files.length > 0){
                     $("#upload-single-img-btn").attr('disabled',true);
                 }
                 $(document).on('click', '#upload-single-img-file-list .delete-pic', function () {
                     $(this).parent().remove();
                     $("#upload-single-img-btn").removeAttr('disabled',true);
                     console.log(uploader_single_img.files.length);
                     uploader_single_img.files.length -= 1;
                 });
                 if(uploader_single_img.files.length > 1){ // 限制最多上传5张图
                     uploader_single_img.splice(1,999);
                     return false;
                 }
                  //uploader_single_img.start();   //选择文件后立即上传

                 for(var i = 0, len = files.length; i<len; i++){
                     var file_name = files[i].name; //文件名
                     //构造html来更新UI
                     var html = '<li id="file-' + files[i].id +'"><div class="progress"><span class="bar"></span><span class="percent">上传中 0%</span></div><a class="delete-pic" href="javascript:;"><i></i></a></li>';

                     $(html).appendTo('#upload-single-img-file-list');
                     !function(i){
                         previewImage(files[i],function(imgsrc){
                             $('#file-'+files[i].id).append('<img src="'+ imgsrc +'" />');
                         })
                     }(i);
                 }
             },

             UploadProgress: function(up, file) {
                 //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var percent = file.percent;
                $("#" + file.id).find('.bar').css({"width": percent + "%"});
                $("#" + file.id).find('.percent').text("上传中 "+percent + "%");
             },

             Error: function(up, err) {
                $("#console").append(document.createTextNode("\nError #" + err.code + ": " + err.message));
             }
        }
    });

     //在实例对象上调用init()方法进行初始化。
    uploader_single_img.init();


    //执行反馈重新上传
    var reuploader_single_img = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4', //用来指定上传方式，默认选择最合适的。(可以不配置)
        browse_button : 'reupload-single-img-btn', //触发文件选择对话框的按钮，为那个元素id
        url : 'upload.php', //服务器端的上传页面地址
        flash_swf_url : 'js/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : 'js/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        multi_selection: false, //是否可以在文件浏览对话框中选择多个文件

        // 筛选条件
        filters : {
            max_file_size : '2mb',//限制上传图片的大小
            mime_types: [
                {title : "Image files", extensions : "jpeg,jpg,gif,png"}, //限制文件类型
                // {title : "Zip files", extensions : "zip"}
            ],
            prevent_duplicates:true  //阻止选择则重复的文件
        },

        init: {
            PostInit: function() {
                $("#upload-single-img-btn").on("click",function(){
                    reuploader_single_img.start();
                    return false;
                })
            },

            FilesAdded: function(up, files) {
                console.log(reuploader_single_img.files.length);
                if(reuploader_single_img.files.length > 0){
                    $("#reupload-single-img-btn").attr('disabled',true);
                }
                $(document).on('click', '#reupload-single-img-file-list .delete-pic', function () {
                    $(this).parent().remove();
                    $("#reupload-single-img-btn").removeAttr('disabled',true);
                    console.log(reuploader_single_img.files.length);
                    reuploader_single_img.files.length -= 1;
                });
                if(reuploader_single_img.files.length > 1){ // 限制最多上传5张图
                    reuploader_single_img.splice(1,999);
                    return false;
                }
                //reuploader_single_img.start();   //选择文件后立即上传

                for(var i = 0, len = files.length; i<len; i++){
                    var file_name = files[i].name; //文件名
                    //构造html来更新UI
                    var html = '<li id="file-' + files[i].id +'"><div class="progress"><span class="bar"></span><span class="percent">上传中 0%</span></div><a class="delete-pic" href="javascript:;"><i></i></a></li>';

                    $(html).appendTo('#reupload-single-img-file-list');
                    !function(i){
                        previewImage(files[i],function(imgsrc){
                            $('#file-'+files[i].id).append('<img src="'+ imgsrc +'" />');
                        })
                    }(i);
                }
            },

            UploadProgress: function(up, file) {
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var percent = file.percent;
                $("#" + file.id).find('.bar').css({"width": percent + "%"});
                $("#" + file.id).find('.percent').text("上传中 "+percent + "%");
            },

            Error: function(up, err) {
                $("#console").append(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
    });

    //在实例对象上调用init()方法进行初始化。
    reuploader_single_img.init();



    /**
     * 上传多张图片
     * ①:给父级盒子加id(upload-multiple-img-container)
     * ②:必须要在上传文件的那个按钮选项中加入id(upload-multiple-img-btn)
     * ③:要在这个按钮的兄弟级ul加id(upload-multiple-img-file-list);
     * 例:
     <div id="upload-multiple-img-container" class="plupload-container">
         <button id="upload-multiple-img-btn"  class="btn btn-danger bg-main">上传图片</button>
         <ul id="upload-multiple-img-file-list" class="file-list"></ul>
     </div>
     *
     */


    //实例化一个plupload上传对象
    var uploader_multiple_img = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4', //用来指定上传方式，默认选择最合适的。(可以不配置)
        browse_button : 'upload-multiple-img-btn', //触发文件选择对话框的按钮，为那个元素id
        url : 'upload.php', //服务器端的上传页面地址
        flash_swf_url : 'js/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : 'js/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        multi_selection: true, //是否可以在文件浏览对话框中选择多个文件

        // 筛选条件
        filters : {
            max_file_size : '2mb',//限制上传图片的大小
            mime_types: [
                {title : "Image files", extensions : "jpeg,jpg,gif,png"}, //限制文件类型
                // {title : "Zip files", extensions : "zip"}
            ],
            prevent_duplicates:true  //阻止选择则重复的文件
        },

        init: {
            PostInit: function() {
                $("#upload-multiple-img-btn").on("click",function(){
                    uploader_multiple_img.start();
                    return false;
                })
            },

            FilesAdded: function(up, files) {
                console.log(uploader_multiple_img.files.length);
                if(uploader_multiple_img.files.length > 4){
                    $("#upload-multiple-img-btn").attr('disabled',true);
                }
                $(document).on('click', '#upload-multiple-img-file-list .delete-pic', function () {
                    $(this).parent().remove();
                    $("#upload-multiple-img-btn").removeAttr('disabled',true);
                    console.log(uploader_multiple_img.files.length);
                    uploader_multiple_img.files.length -= 1;
                });
                if(uploader_multiple_img.files.length > 5){ // 限制最多上传5张图
                    uploader_multiple_img.splice(5,999);
                    return false;
                }
                //uploader_multiple_img.start();   //选择文件后立即上传

                for(var i = 0, len = files.length; i<len; i++){
                    var file_name = files[i].name; //文件名
                    //构造html来更新UI
                    var html = '<li id="file-' + files[i].id +'"><div class="progress"><span class="bar"></span><span class="percent">上传中 0%</span></div><a class="delete-pic" href="javascript:;"><i></i></a></li>';

                    $(html).appendTo('#upload-multiple-img-file-list');
                    !function(i){
                        previewImage(files[i],function(imgsrc){
                            $('#file-'+files[i].id).append('<img src="'+ imgsrc +'" />');
                        })
                    }(i);
                }
            },

            UploadProgress: function(up, file) {
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var percent = file.percent;
                $("#" + file.id).find('.bar').css({"width": percent + "%"});
                $("#" + file.id).find('.percent').text("上传中 "+percent + "%");
            },

            Error: function(up, err) {
                $("#console").append(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
    });
    //
    ////在实例对象上调用init()方法进行初始化。
    uploader_multiple_img.init();


    //使用plupload中的mOxie对象
    function previewImage(file,callback){//file为plupload事件监听函数参数中的file对象,callback为预览图片准备完成的回调函数
        if(!file || !/image\//.test(file.type)) return; //确保文件是图片
        if(file.type=='image/gif'){//gif使用FileReader进行预览,因为mOxie.Image只支持jpg和png
            var fr = new mOxie.FileReader();
            fr.onload = function(){
                callback(fr.result);
                fr.destroy();
                fr = null;
            }
            fr.readAsDataURL(file.getSource());
        }else{
            var preloader = new mOxie.Image();
            preloader.onload = function() {
                preloader.downsize(100, 100);//先压缩一下要预览的图片,宽100，高100
                var imgsrc = preloader.type=='image/jpeg' ? preloader.getAsDataURL('image/jpeg',80) : preloader.getAsDataURL(); //得到图片src,实质为一个base64编码的数据
                callback && callback(imgsrc); //callback传入的参数为预览图片的url
                preloader.destroy();
                preloader = null;
            };
            preloader.load(file.getSource());
        }
    }



    /**
     * 上传文件
     * ①:给父级盒子加id(upload-file-container)
     * ②:在上传文件的那个按钮选项中加入id(upload-file-btn)
     * ③:在这个按钮的兄弟级ul加id(upload-file-list);
     * 例:
     <div id="upload-file-container" class="plupload-container">
         <button id="upload-file-btn"  class="btn btn-danger bg-main">上传文件</button>
         <ul id="upload-file-list" class="file-list"></ul>
     </div>
     *
     */

    //实例化一个plupload上传对象
    var uploader_file = new plupload.Uploader({
        runtimes : 'html5,flash,silverlight,html4', //用来指定上传方式，默认选择最合适的。(可以不配置)
        browse_button : 'upload-file-btn', //触发文件选择对话框的按钮，为那个元素id
        url : 'upload.php', //服务器端的上传页面地址
        flash_swf_url : 'js/Moxie.swf', //swf文件，当需要使用swf方式进行上传时需要配置该参数
        silverlight_xap_url : 'js/Moxie.xap', //silverlight文件，当需要使用silverlight方式进行上传时需要配置该参数
        multi_selection: true, //是否可以在文件浏览对话框中选择多个文件

        // 筛选条件
        filters : {
            max_file_size : '20mb',//限制上传文件的大小
            mime_types: [
                { title : "*", extensions : "*" }
                //{title : "Image files", extensions : "jpeg,jpg,gif,png"}, //限制文件类型
                // {title : "Zip files", extensions : "zip"}
            ],
            prevent_duplicates:true  //阻止选择则重复的文件
        },

        init: {
            PostInit: function() {
                $("#upload-file-btn").on("click",function(){
                    uploader_file.start();
                    return false;
                })
            },

            FilesAdded: function(up, files) {
                console.log(uploader_file.files.length);
                if(uploader_file.files.length > 9){
                    $("#upload-file-btn").attr('disabled',true);
                }
                $(document).on('click', '#upload-file-list .delete-pic', function () {
                    $(this).parents('li').remove();
                    $("#upload-file-btn").removeAttr('disabled',true);
                    console.log(uploader_file.files.length);
                    uploader_file.files.length -= 1;
                });
                if(uploader_file.files.length > 10){ // 限制最多上传5张图
                    uploader_file.splice(10,999);
                    return false;
                }
                //uploader_file.start();   //选择文件后立即上传

                for(var i = 0, len = files.length; i<len; i++){
                    var file_name = files[i].name; //文件名
                    //构造html来更新UI
                    var html = '<li id="file-' + files[i].id +'"><div class="progress"><span class="bar"></span><span class="percent">上传中 0%</span></div><p>' +file_name+ '<i class="delete-pic"></i></p></li>';

                    $(html).appendTo('#upload-file-list');
                    //!function(i){
                    //    previewImage(files[i],function(imgsrc){
                    //        $('#file-'+files[i].id).append('<img src="'+ imgsrc +'" />');
                    //    })
                    //}(i);
                }
            },

            UploadProgress: function(up, file) {
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
                var percent = file.percent;
                $("#" + file.id).find('.bar').css({"width": percent + "%"});
                $("#" + file.id).find('.percent').text("上传中 "+percent + "%");
            },

            Error: function(up, err) {
                $("#console").append(document.createTextNode("\nError #" + err.code + ": " + err.message));
            }
        }
    });

    //在实例对象上调用init()方法进行初始化。
    uploader_file.init();



    // //绑定各种事件，并在事件监听函数中做你想做的事
    // uploader_pic.bind('FilesAdded',function(uploader_pic,files){
    //     //每个事件监听函数都会传入一些很有用的参数，
    //     //我们可以利用这些参数提供的信息来做比如更新UI，提示上传进度等操作
    // });
    // uploader_pic.bind('UploadProgress',function(uploader_pic,file){
    //     //每个事件监听函数都会传入一些很有用的参数，
    //     //我们可以利用这些参数提供的信息来做比如更新UI，提示上传进度等操作
    // });
    //......
    //......

    //最后给"开始上传"按钮注册事件
    // document.getElementById('start_upload').onclick = function(){
    //     uploader_pic.start(); //调用实例对象的start()方法开始上传文件，当然你也可以在其他地方调用该方法
    // }


    //绑定文件添加进队列事件
    //uploader_pic.bind('FilesAdded',function(uploader_pic,files){
    //    console.log(uploader_pic.files.length);
    //
    //    if(uploader_pic.files.length > 0){
    //        $("#pickfiles").attr('disabled',true);
    //    }
    //    $(document).on('click', '#file-list .delete-pic', function () {
    //        $(this).parent().remove();
    //        $("#pickfiles").removeAttr('disabled',true);
    //        uploader_pic.files.length = uploader_pic.files.length - 1;
    //        console.log(uploader_pic.files.length);
    //    });
    //    if(uploader_pic.files.length > 5){ // 限制最多上传5张图
    //        uploader_pic.splice(4,999);
    //        return false;
    //    }
    //    // uploader_pic.start();   //选择文件后立即上传
    //
    //    // for (var i in files) {
    //    //     if(i>2){
    //    //         uploader_pic.splice(3,100);
    //    //         uploader_pic.removeFile( uploader_pic.getFile(file[i].id));
    //    //     }
    //    // }
    //
    //    // if ($("#file-list").children("li").length > 1) {
    //    //         alert("最多上传一张图片！");
    //    //         uploader_pic.destroy();
    //    // } else {
    //    //     var li = '';
    //    //     plupload.each(files, function(files) { //遍历文件
    //    //         li += "<li id='" + files['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>上传中 0%</span></div></li>";
    //    //     });
    //    //     $("#file-list").append(li);
    //    //     // uploader_pic.start();
    //    // }
    //
    //    for(var i = 0, len = files.length; i<len; i++){
    //        var file_name = files[i].name; //文件名
    //        //构造html来更新UI
    //        var html = '<li id="file-' + files[i].id +'"><div class="progress"><span class="bar"></span><span class="percent">上传中 0%</span></div><a class="delete-pic" href="javascript:;"><i></i></a></li>';
    //
    //        $(html).appendTo('#file-list');
    //        !function(i){
    //            previewImage(files[i],function(imgsrc){
    //                $('#file-'+files[i].id).append('<img src="'+ imgsrc +'" />');
    //            })
    //        }(i);
    //    }
    //});
})