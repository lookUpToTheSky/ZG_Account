
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
        <legend>添加用户</legend>
    </fieldset>
    <form class="layui-form " action="">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <input type="text" name="user_name" lay-verify="username" required lay-verify="required" placeholder="字符串+数字组合" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" name="user_password" lay-verify="password" required lay-verify="required" placeholder="6-12位" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="user_email" required lay-verify="required" placeholder="123@qq.com" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">是否禁用</label>
            <div class="layui-input-block">
                <input type="checkbox" name="user_state" autocomplete="off" lay-text="是|否" lay-skin="switch">
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="login">立即提交</button>
            </div>
        </div>
    </form>
<script>
    Date.prototype.Format = format
    let nowTime = new Date().getTime();
    let time = new Date(nowTime).Format('yyyy-MM-dd HH:mm:ss')

    layui.use('form', function () {
        var form = layui.form,
        layer = layui.layer;
        form.on('submit(login)', function (data) {
            sessionStorage.isLgoin = 1; //模拟登录状态,实际使用时请删除掉
            let loadIndex = layer.load();
            let params = data.field;
            params.user_state = $('input[name=user_state]').is(':checked')?0: 1;
            params.created_at = params.updated_at = time;
            $.ajax({
                url: "{{url('admin/addUserInfo')}}",
                data: params,
                type: 'post',
                success: (res) => {
                    layer.close(loadIndex);
                    $('input').val('');
                    layer.msg(res.msg);
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
            },
            password: [
                /^[\S]{6,12}$/
                ,'密码必须6到12位，且不能出现空格'
            ]
        });  
    });
    function format(fmt) {
        var o = {
            "M+": this.getMonth() + 1, //�·� 
            "d+": this.getDate(), //�� 
            "H+": this.getHours(), //Сʱ 
            "m+": this.getMinutes(), //�� 
            "s+": this.getSeconds(), //�� 
            "q+": Math.floor((this.getMonth() + 3) / 3), //���� 
            "S": this.getMilliseconds() //���� 
        };
        if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
        for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }
</script>
</body>
</html>
