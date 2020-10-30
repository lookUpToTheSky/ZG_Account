<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>资源列表</title>
    <link rel="stylesheet" href={{asset("admin/static/common/layui/css/layui.css")}}>
    <link rel="stylesheet" href={{asset("admin/static/admin/css/style.css")}}>
    <link rel="stylesheet" href={{asset("admin/static/admin/css/style-iframe.css")}}>
    <script src={{asset("admin/static/common/layui/layui.js")}}></script>
    <script src={{asset("admin/static/common/jquery-3.3.1.min.js")}}></script>
    <script src={{asset("admin/static/common/vue.min.js")}}></script>
    <style>
    body .demo-class .layui-layer-title{background:#1E9FFF; color:#fff; border: none;}
    body .demo-class .layui-layer-btn{background:#1E9FFF;height: 30px;}
    body .demo-class .layui-layer-btn a{background:#333;}
    body .demo-class .layui-layer-btn .layui-layer-btn1{background:#999;}
    </style>
</head>
<body>
<div id="app">
    <fieldset class="layui-elem-field layui-field-title">
        <legend>模型列表</legend>
    </fieldset>
    <div class="layui-row">
        <div class="layui-col-xs-offset9 layui-row"  style="width: 300px">
            <div class="layui-input-inline layui-col-xs8">
                <input type="text" @input="onShowList" name="search" v-model="searchValue"  placeholder="文件名" class="layui-input key">
            </div>
            <button type="button" @click="onSearch" class="layui-btn sou layui-col-xs4">搜索</button>
        </div>
    </div>
    <table class="layui-table">
        <thead>
            <tr>
                <th>模型ID</th>
                <th>模型名称</th>
                <th>模型地址</th>
                <th>模型大小</th>
                <th>模型类型</th>
                <th>上传用户</th>
                <th>上传时间</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in searchList" :key="item.source_id">
                <td v-text="item.source_id"></td>
                <td v-text="item.source_name"></td>
                <td>
                    <i class="layui-icon">&#xe64c;</i>
                    <a @click="onPreview(item)" style="cursor: pointer" v-text="item.source_url"></a>
                </td>
                <td v-text="item.source_size"></td>
                <td v-text="item.source_type"></td>
                <td v-text="item.upload_user || '-'"></td>
                <td v-text="item.upload_time"></td>
                <td>
                    <button class="layui-btn layui-btn-xs layui-btn-normal"><a :href="'{{url('admin/downloadSource?source_id=')}}'+item.source_id" style="color: #fff"><i class="layui-icon">&#xe601;</i>下载</a></button>
                    <button class="layui-btn layui-btn-xs layui-btn-normal" @click="onEdit(item)"><i class="layui-icon">&#xe642;</i>修改</button>
                    <button class="layui-btn layui-btn-xs layui-btn-danger" @click="onDelete(item)"><i class="layui-icon">&#xe640;</i>删除</button>
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
                sourceList: null,
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
                        url: "{{url('admin/getSourceList')}}",
                        data: {token: this.token},
                        type: 'get',
                        success: (res) => {
                            layer.close(index);
                            if(res.code == 200) {
                                let data = res.data.sort( (a, b) =>  b.source_id - a.source_id);
                                this.searchList = this.sourceList = data;
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
                    source_id: row.source_id,
                    token: this.token
                }
                layer.msg(`确定删除${row.source_name}文件？`, {
                    btn: ['确定', '取消'],
                    time: 0,
                    anim: 0,
                    btnAlign: 'c',
                    yes: () => {
                        this.layer.closeAll();
                        $.ajax({
                            url: "{{url('admin/deleteSource')}}",
                            type: 'post',
                            data: params,
                            success: (res) => {
                                if(res.code == 200) {
                                    this.sourceList.forEach( (item, index) => {
                                        if(item.source_id === row.source_id){
                                            this.sourceList.splice(index, 1);
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
                    this.searchList = this.sourceList;
                }
            },
            onSearch () {
                if(this.searchValue) {
                    this.searchList = this.sourceList.filter( value => {
                        if(value.source_name.indexOf(this.searchValue) > -1 ||
                        value.source_type.indexOf(this.searchValue) > -1||
                        value.source_id == this.searchValue
                        ) {
                            return value;
                        }
                    })
                }else{
                    this.searchList = this.sourceList;  
                }
                
            },
            onPreview(item) {
                this.layer.open({
                    type: 2 //此处以iframe举例
                    ,title: `${item.source_name}模型预览`
                    ,area: ['80vw', '90vh']
                    ,shade: 0
                    ,maxmin: true
                    ,skin: 'demo-class'
                    ,offset: '20px'
                    ,btnAlign: 'c' //按钮居中
                    ,content: window.location.origin + `/admin/preview?url=${item.source_url}&name=${item.source_name}&type=${item.source_type}`
                    ,btn: [] //只是为了演示
                    ,yes: () => {
                        this.layer.closeAll();
                    }
                    ,zIndex: -1//重点1
                    ,success: (layero) => {
                        this.layer.setTop(layero); //重点2
                    }
                });
            }
        },
        mounted () {
           this.initLayui(); 
        }
    })
    
</script>
</body>
</html>
