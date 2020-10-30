<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>用户修改</title>
    <link rel="stylesheet" href={{asset("admin/static/common/layui/css/layui.css")}}>
    <link rel="stylesheet" href={{asset("admin/static/admin/css/style.css")}}>
    <link rel="stylesheet" href={{asset("admin/static/admin/css/style-iframe.css")}}>
    <script src={{asset("admin/static/common/layui/layui.js")}}></script>
    <script src={{asset("admin/static/common/jquery-3.3.1.min.js")}}></script>
    <script src={{asset("admin/static/common/vue.min.js")}}></script>
    <style>
        .d-flex {
            display: flex;
            justify-content: space-around;
            align-items: center;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="layui-form-item layui-row">
            <div class="layui-col-xs6  d-flex">
                <label>用户名：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="text" name="user_name" lay-verify="username" v-model="userInfo.user_name" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-col-xs6 d-flex">
                <label>密码：</label>
                <div class="layui-input-inline"  style="flex: 1">
                    <input type="tel" name="user_password" lay-verify="password" v-model="userInfo.user_password" autocomplete="off" class="layui-input">
                </div>
            </div>
            
        </div> 
        <div class="layui-form-item layui-row">
            <div class="layui-col-xs6  d-flex">
                <label>昵称：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="tel" name="phone" v-model="userInfo.user_pet_name" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-col-xs6  d-flex">
                <label>qq：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="text" name="email" lay-verify="email" v-model="userInfo.user_qq" autocomplete="off" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-form-item layui-row">
            <div class="layui-col-xs6  d-flex">
                <label>qq邮箱：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="text" name="email" lay-verify="email" v-model="userInfo.user_email" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-col-xs6 d-flex">
                <label>手机：</label>
                <div class="layui-input-inline"  style="flex: 1">
                    <input type="tel" name="phone" lay-verify="required|phone" v-model="userInfo.user_phone" autocomplete="off" class="layui-input">
                </div>
            </div>
        </div>
        <div class="layui-form-item layui-row">
            <div class="layui-col-xs6 d-flex">
                <label>状态：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="radio" @change="oncChangeState(1)" name="state" value="1" :checked="userInfo.user_state ==1"/>正常
                    <input type="radio" @change="oncChangeState(0)" name="state" value="0" :checked="userInfo.user_state ==0"/>封禁
                </div>
            </div>
            <div class="layui-col-xs6 d-flex">
                <label>登录次数：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="text" disabled  name="times" v-model="userInfo.user_login_times" class="layui-input layui-bg-gray"/>
                </div>
            </div>
        </div>
        <div class="layui-form-item layui-row">
            <div class="layui-col-xs6  d-flex">
                <label>注册时间：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="text" name="" id="date1" v-model="userInfo.created_at" disabled class="layui-input layui-bg-gray">
                </div>
            </div>
            <div class="layui-col-xs6  d-flex">
                <label>登录时间：</label>
                <div class="layui-input-inline" style="flex: 1">
                    <input type="text" name="" id="date2" v-model="userInfo.updated_at" disabled class="layui-input layui-bg-gray">
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    var editInfo;
    let vm = new Vue({
        el: '#app',
        data: {
            userInfo: null,
            layer: null
        },
        created () {
            this.userInfo = JSON.parse(sessionStorage.getItem('userInfo'));
            if(!!this.userInfo) {
                editInfo = this.userInfo;
            }else {
                this.userInfo = [];
            }
        },
        methods: {
            initLayui() {
                layui.use('form', () => {
                    this.layer = layui.layer;
                    var form = layui.form;
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
                })   
            },
            oncChangeState (state) {
                this.userInfo.user_state = state*1;
            }
        },
        mounted () {
            this.initLayui()
        }
    }) 
    function saveUserInfo() {
        sessionStorage.setItem('userInfo', JSON.stringify(editInfo));   
    }
</script>
</html>