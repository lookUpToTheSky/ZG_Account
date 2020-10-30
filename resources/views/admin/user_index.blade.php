
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>用户列表</title>
    <link rel="stylesheet" href={{asset("admin/static/common/layui/css/layui.css")}}>
    <link rel="stylesheet" href={{asset("admin/static/admin/css/style.css")}}>
    <link rel="stylesheet" href={{asset("admin/static/admin/css/style-iframe.css")}}>
    <script src={{asset("admin/static/common/layui/layui.js")}}></script>
    <script src={{asset("admin/static/common/jquery-3.3.1.min.js")}}></script>
    <script src={{asset("admin/static/common/vue.min.js")}}></script>
</head>
<body>
<div id="app">
    <div class="layui-row">
        <div class="layui-col-xs-offset9 layui-row"  style="width: 300px">
            <div class="layui-input-inline layui-col-xs8">
                <input type="text" @input="onShowList" name="search" v-model="searchValue" placeholder="手机/用户名/邮箱/昵称" class="layui-input key">
            </div>
            <button type="button" @click="onSearch" class="layui-btn sou layui-col-xs4">搜索</button>
        </div>
    </div>
    <table class="layui-table">
        <thead>
            <tr>
                <th><a href="?/admin/user/index/order/uid.html">用户ID</a></th>
                <th>手机</th>
                <th>用户名</th>
                <th>密码</th>
                <th>昵称</th>
                <th>qq</th>
                <th>邮箱</th>
                <th>注册时间</th>
                <th>登录时间</th>
                <th>登录次数</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in searchList" :key="item.user_id">
                <td v-text="item.user_id"></td>
                <td v-text="item.user_phone || '-'"></td>
                <td v-text="item.user_name"></td>
                <td v-text="item.user_password || '-'"></td>
                <td v-text="item.user_pet_name || '-'"></td>
                <td v-text="item.user_qq || '-'"></td>
                <td v-text="item.user_email"></td>
                <td v-text="item.created_at"></td>
                <td v-text="item.updated_at"></td>
                <td v-text="item.user_login_times"></td>
                <td v-if="item.user_state ==1" style="color: #009688">正常</td>
                <td v-else style="color: #FF5722">封禁</td>
                <td>
                    <button class="layui-btn layui-btn-xs layui-btn-normal" @click="onEdit(item)">
                        <i class="layui-icon">&#xe642;</i>修改
                    </button>
                    <button class="layui-btn layui-btn-xs layui-btn-danger" @click="onDelete(item)"  >
                       <i class="layui-icon">&#xe640;</i>删除
                    </button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<script>
    var info;
    const vm = new Vue({
        el:'#app',
        data () {
            return {
                userList: null,
                searchList: [],
                layer: null,
                searchValue: null,
                token: sessionStorage.getItem('_token')
            }
        },
        methods: {
            initLayui() {
                layui.use('form', () => {
                    let layer = this.layer = layui.layer;
                    $ = layui.jquery;
                    let index = layer.load();
                    $.ajax({
                        url: "{{url('admin/getUserList')}}",
                        data: {token: this.token},
                        type: 'get',
                        success: (res) => {
                            layer.close(index);
                            if(res.code == 200) {
                                let data = res.data.sort( (a, b) =>  b.user_id - a.user_id);
                                this.searchList = this.userList = data;
                            }else{
                                layer.msg(res.msg, {time: 1000});
                            }
                        },
                        fail: () => {
                            layer.msg('加载失败！', {time: 1000});
                        }
                    })
                })   
            },
            //修改用户信息
            onEdit (row) {
                info = JSON.stringify(row);
                sessionStorage.setItem('userInfo', info);
                this.layer.open({
                    type: 2 //此处以iframe举例
                    ,title: '修改用户信息'
                    ,area: ['600px', '400px']
                    ,shade: 0.5
                    ,maxmin: true
                    ,btnAlign: 'c' //按钮居中
                    ,offset: [] 
                    ,content: window.location.origin + `/admin/userEdit`
                    ,btn: ['确定', '取消'] //只是为了演示
                    ,yes: () => {
                        document.getElementsByTagName('iframe')[0].contentWindow.saveUserInfo();
                        let editInfo = JSON.parse(sessionStorage.getItem('userInfo'));
                        editInfo.token = this.token;
                        $.ajax({
                            url: "{{url('admin/editUserInfo')}}",
                            type: 'post',
                            data: editInfo,
                            success: (res) => {
                                if(res.code == 200) {
                                    this.userList.forEach( (item, index) => {
                                        if(item.user_id === editInfo.user_id){
                                            this.$set(this.userList, index, editInfo); 
                                        }
                                    })
                                    this.layer.msg(res.msg);
                                }
                            }
                        })
                        sessionStorage.removeItem('userInfo')
                        this.layer.closeAll();
                    }
                    ,btn2: () => {
                        sessionStorage.removeItem('userInfo')
                        this.layer.closeAll();
                    }
                    ,zIndex: -1//重点1
                    ,success: (layero) => {
                        this.layer.setTop(layero); //重点2
                    }
                });
            },
            // 删除用户
            onDelete(row) {
                let params = {
                    user_id: row.user_id,
                    token: this.token
                }
                layer.msg(`确定删除${row.user_name}用户？`, {
                    btn: ['确定', '取消'],
                    time: 0,
                    anim: 0,
                    btnAlign: 'c',
                    yes: () => {
                        this.layer.closeAll();
                        $.ajax({
                            url: "{{url('admin/deleteUserInfo')}}",
                            type: 'post',
                            data: params,
                            success: (res) => {
                                if(res.code == 200) {
                                    this.userList.forEach( (item, index) => {
                                        if(item.user_id === row.user_id){
                                            this.userList.splice(index, 1);
                                        }
                                    })
                                    this.layer.msg(res.msg);
                                }
                            }
                        })
                    }
                });
                
            },
            onShowList() {
                if(!this.searchValue) {
                    this.searchList = this.userList;
                }
            },
            onSearch () {
                if(this.searchValue) {
                    this.searchList = this.userList.filter( value => {
                        if(value.user_name.indexOf(this.searchValue) > -1 ||
                        value.user_phone.indexOf(this.searchValue) > -1||
                        value.user_email.indexOf(this.searchValue) > -1 ||
                        value.user_pet_name.indexOf(this.searchValue) > -1
                        ) {
                            return value;
                        }
                    })
                }else{
                    this.searchList = this.userList;  
                }
            }
        },
        mounted () {
           this.initLayui(); 
        }
    })
    
</script>
</body>
</html>
