
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>添加用户 - QAdmin后台模板</title>
    <link rel="stylesheet" href="./static/common/layui/css/layui.css">
    <link rel="stylesheet" href="./static/admin/css/style.css">
    <link rel="stylesheet" href="./static/admin/css/style-iframe.css">
    <script src="./static/common/layui/layui.js"></script>
    <script src="./static/common/jquery-3.3.1.min.js"></script>
    <script src="./static/common/vue.min.js"></script>
</head>
<body>
    <fieldset class="layui-elem-field layui-field-title">
        <legend>上传文件</legend>
    </fieldset>
    <div class="layui-upload">
        <button type="button" class="layui-btn layui-btn-normal" id="file">选择文件上传</button> 
        <h6>支持上传文件格式： (.obj，.mtl，.fbx，.FBX，.gltf)</span>
        <div class="layui-upload-list">
        <table class="layui-table">
            <thead>
            <tr><th>文件名</th>
            <th>大小</th>
            <th>状态</th>
            <th>操作</th>
            </tr></thead>
            <tbody id="demoList"></tbody>
        </table>
        </div>
        <button type="button" class="layui-btn layui-btn-disabled" id="testListAction">开始上传</button>
    </div> 
<script>
    Date.prototype.Format = format
    let nowTime = new Date().getTime();
    let time = new Date(nowTime).Format('yyyy-MM-dd HH:mm:ss')
    layui.use('upload', function(){
        var upload = layui.upload;
        //多文件列表示例
        var demoListView = $('#demoList')
        ,uploadListIns = upload.render({
            elem: '#file'
            ,url: "{{url('admin/uploadSource')}}" //改成您自己的上传接口
            ,accept: 'file'
            ,exts: 'obj|mtl|fbx|FBX|gltf'
            ,multiple: true
            ,auto: false,
            size: 10240
            ,bindAction: '#testListAction'
            ,choose: function(obj){   
                var files = this.files = obj.pushFile(); //将每次选择的文件追加到文件队列
                $('#testListAction').removeClass('layui-btn-disabled');
            //读取本地文件
            obj.preview(function(index, file, result){
                var fileSize = file.size/1024 >= 1024? (file.size/1024/1024).toFixed(1) +'M' : (file.size/1024).toFixed(1) + 'kb';
                var tr = $(['<tr id="upload-'+ index +'">'
                ,'<td>'+ file.name +'</td>'
                ,'<td>'+ fileSize + '</td>'
                ,'<td>等待上传</td>'
                ,'<td>'
                    ,'<button class="layui-btn layui-btn-xs demo-reload layui-hide">重传</button>'
                    ,'<button class="layui-btn layui-btn-xs layui-btn-danger demo-delete">删除</button>'
                ,'</td>'
                ,'</tr>'].join(''));
                
                //单个重传
                tr.find('.demo-reload').on('click', function(){
                    obj.upload(index, file);
                });
                
                //删除
                tr.find('.demo-delete').on('click', function(){
                    delete files[index]; //删除对应的文件
                    tr.remove();
                    uploadListIns.config.elem.next()[0].value = ''; //清空 input file 值，以免删除后出现同名文件不可选
                });
                demoListView.append(tr);
            });
            },
            before: () => {
                $('#testListAction').addClass('layui-btn-disabled');
            }
            ,done: function(res, index, upload){
                if(res.data){ //上传成功
                    var tr = demoListView.find('tr#upload-'+ index)
                    ,tds = tr.children();
                    tds.eq(2).html('<span style="color: #5FB878;">上传成功</span>');
                    tds.eq(3).html(''); //清空操作
                    return delete this.files[index]; //删除文件队列已经上传成功的文件
                }
                this.error(index, upload);
            }
            ,error: function(index, upload){
                var tr = demoListView.find('tr#upload-'+ index)
                ,tds = tr.children();
                tds.eq(2).html('<span style="color: #FF5722;">上传失败</span>');
                tds.eq(3).find('.demo-reload').removeClass('layui-hide'); //显示重传
            }
        });
    });
    function format(fmt) {
        var o = {
            "M+": this.getMonth() + 1, 
            "d+": this.getDate(), 
            "H+": this.getHours(), 
            "m+": this.getMinutes(), 
            "s+": this.getSeconds(),  
            "q+": Math.floor((this.getMonth() + 3) / 3), 
            "S": this.getMilliseconds() 
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }
</script>
</body>
</html>
