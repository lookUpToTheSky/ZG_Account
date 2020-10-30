<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>后台管理系统</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href={{asset('admin/static/common/layui/css/layui.css')}}>
    <link rel="stylesheet" href={{asset('admin/static/admin/css/login.css')}}>
    <script src={{asset('admin/static/common/config.js')}}></script>
    <script src={{asset('admin/static/common/layui/layui.js')}}></script>
</head>

<body id="login">
<div class="login">
    <h2>管理系统</h2>
    <form class="layui-form" method="post" target="_blank" action="">
        <div class="layui-form-item">
            <input type="username" name="admin_name" lay-verify="username" autocomplete="on" placeholder="用户名" class="layui-input">
            <i class="layui-icon input-icon">&#xe66f;</i>
        </div>
        <div class="layui-form-item">
            <input type="password" name="admin_password" lay-verify="pass" placeholder="密码"  class="layui-input">
            <i class="layui-icon input-icon">&#xe673;</i>
        </div>
        <div class="layui-form-item layui-row">
            <div class="layui-col-xs6">
                <input type="text" name="code" lay-verify="code" maxlength="4" placeholder="验证码" autocomplete="off" class="layui-input">
                <i class="layui-icon input-icon">&#xe679;</i>
            </div>
            <img src={{url('admin/code')}} title="刷新" onclick="this.src = '{{url('admin/code?')}}' + Math.random()" height="38px" width="80px" alt="error" class="code layui-col-xs-offset2 layui-col-xs4">
        </div>
        <div class="layui-form-item">
            <input type="checkbox" name="box" lay-skin="primary" title="记住密码" checked=""> <a class="back" href="javascript:;"  style="margin-top: 10px">忘记密码？</a>
        </div>
        <div class="layui-form-item">
            <button style="width: 100%" class="layui-btn" lay-submit lay-filter="login">立即登录</button>
        </div>
    </form>
</div>
</body>
<script>
    layui.use('form', function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery;
        form.on('submit(login)', function (data) {
            sessionStorage.isLgoin = 1; //模拟登录状态,实际使用时请删除掉
            let loadIndex = layer.load();
            $.ajax({
                url: "{{url('admin/login')}}",
                data: data.field,
                type: 'get',
                success: (res) => {
                    layer.close(loadIndex);
                    if(res.code == -1) {
                        layer.msg(res.msg, {time: 1000});
                        $('input').val('');
                        $('.code').attr("src", "{{url('admin/code')}}");
                    }else{
                        sessionStorage.setItem('_token', res.data.token);
                        window.location.href = "{{url('admin/index')}}";
                    }
                }
            })
            return false;
        });
        form.verify({
            username: function(value, item){ //value：表单的值、item：表单的DOM对象
                if(!value) {
                    return '用户名不能为空！'
                }
                if(!new RegExp("^[a-zA-Z0-9_\u4e00-\u9fa5\\s·]+$").test(value)){
                    return '用户名不能有特殊字符';
                }
                if(/(^\_)|(\__)|(\_+$)/.test(value)){
                    return '用户名首尾不能出现下划线\'_\'';
                }
                if(/^\d+\d+\d$/.test(value)){
                    return '用户名不能全为数字';
                }
            }
            //数组的两个值分别代表：[正则匹配、匹配不符时的提示文字]
            ,pass: [
                /^[\S]{6,12}$/
                ,'密码必须6到12位，且不能出现空格'
            ],
            code: [
                /^[\S]{4}$/,
                '验证码必须6位'] 
        });  
    });
</script>
</html>
