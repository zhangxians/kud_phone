
function InitWebUploader(config){
    var $list = $(config.listelem  ||'#fileList');
    var uploader =WebUploader.create({
        runtimeOrder:'html5,flash',
        // 自动上传
        auto: true,
        fileVal:"filedata",//[默认值：'file'] 设置文件上传域的name。
        fileNumLimit:config.fileNumLimit||30,
        fileSingleSizeLimit:config.fileSingleSizeLimit||30*1024*1024,
        duplicate :true,
        swf: '/packages/webuploader/0.1.5/Uploader.swf',
        // 文件接收服务端。
        server: config.server, // 服务器端处理地址
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: config.pick ||({
            id: '#filePicker',
            innerHTML: '<i class="edu-iconfont">&#xe693;</i> 上传文件',//按钮文字
            multiple:false//是否能同时选择多个文件
        }),
        // 只允许选择文件，可选。
        accept: config.accept || {
            title: '文件',
            extensions:"zip,zip",
            mimeTypes:".zip,.zip"
        }
    });
    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {
        $list.empty();
        $list.append( '<div id="' + file.id + '" class="item">' +
            '<h4 class="info">' + file.name + '</h4>' +
            '<p class="state">等待上传...</p>' +
            '</div>' );
    });
    // 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var $li = $( '#'+file.id ),$percent = $li.find('.progress .file-progress-bar');
        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<div class="progress progress-striped active">' +
                '<div class="file-progress-bar" role="progressbar" style="width: 0%;height: 8px;">' +
                '</div>' +
                '</div>').appendTo( $li ).find('.file-progress-bar');
        }
        $li.find('p.state').text('上传中...');
        $percent.css( 'width', percentage * 100 + '%' );
    });
    uploader.on( 'uploadError', function( file ) {
        $( '#'+file.id ).find('p.state').text('上传出错');
    });
    uploader.on("error",function (type){
        if(type=="F_EXCEED_SIZE"){
            alert("上传文件太大，请分隔后重新上传！");
        }else if(type=="F_DUPLICATE"){
            alert("不能重复上传！");
        }else if(type=="Q_EXCEED_NUM_LIMIT"){
            alert("上传文件超过限制！");
        }
        else{
            alert(type+"文件有误！");
        }
    });
    uploader.on( 'uploadSuccess', function( file,response ) {
        var callback=config.uploadSuccess;
        if(callback){
            callback(file,response );
        }
    });
}