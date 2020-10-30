
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>后台管理系统</title>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./static/common/layui/css/layui.css">
    <link rel="stylesheet" href="./static/admin/css/style.css">
    <script src="./static/common/layui/layui.js"></script>
    <script src="./static/common/jquery-3.3.1.min.js"></script>
    <script src="./static/common/vue.min.js"></script>
    <style>
        .right h2{
            margin: 10px 0;
        }
        .right li{
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div id="app">
    <!--顶栏-->
    <header class="layui-bg-black layui-clear">
        <h3 class="logo" v-text="webName">1321</h3>
        <ul class="layui-nav layui-layout-right">
            <li class="layui-nav-item">
                <a href="">控制台<span class="layui-badge">9</span></a>
            </li>
            <li class="layui-nav-item">
                <a href="">个人中心<span class="layui-badge-dot"></span></a>
            </li>
            <li class="layui-nav-item" lay-unselect="">
                <a href="javascript:;"><img src="//t.cn/RCzsdCq" class="layui-nav-img">我</a>
                <dl class="layui-nav-child">
                <dd><a href="javascript:;">修改信息</a></dd>
                <dd><a href="javascript:;">安全管理</a></dd>
                <dd><a href="javascript:;">退了</a></dd>
                </dl>
            </li>
        </ul>
    </header>
    <div class="main">
        <!--左栏-->
        <div class="left">
            <ul class="cl" >
                <!--顶级分类-->
                <li v-for="vo,index in menu" :class="{hidden:vo.hidden}">
                    <a href="javascript:;" :class="{active:vo.active}" @click="onActive(index)">
                        <i class="layui-icon" v-html="vo.icon"></i>
                        <span v-text="vo.name"></span>
                        <i class="layui-icon arrow" v-show="vo.url.length==0">&#xe61a;</i> <i v-show="vo.active" class="layui-icon active">&#xe623;</i>
                    </a>
                    <!--子级分类-->
                    <div v-for="vo2,index2 in vo.list">
                        <a @click="onToPage(vo2.url)" target="main" :class="{active: vo2.active}"  v-text="vo2.name"></a>
                        <i v-show="vo2.active" class="layui-icon active">&#xe623;</i>
                    </div>
                </li>
            </ul>
        </div>

        <!--右侧-->
        <div class="right">
            <iframe src= {{url('admin/main')}} marginwidth="0" marginheight="0" frameborder="0" scrolling="auto" target="_self" ></iframe>
        </div>
    </div>
</div>

<script type="text/javascript">
    let menu = [
    // {
    //     "name": "首页",
    //     "icon": "&#xe68e;",
    //     "url": "index.html",
    //     "hidden": false,
    //     "list": []
    // },{
    //     "name": "基本组件",
    //     "icon": "&#xe653;",
    //     "url": "",
    //     "hidden": false,
    //     "list": [{
    //         "name": "layui基本组件",
    //         "url": "pages_component.html",
    //     },{
    //         "name": "layui内置模块",
    //         "url": "pages_model.html"
    //     },{
    //         "name": "提示框",
    //         "url": "pages_msg.html"
    //     }]
    // }, 
    {
        "name": "用户管理",
        "icon": "&#xe612;",
        "url": "",
        "hidden": false,
        "list": [{
            "name": "用户列表",
            "url": "/admin/main"
        }, {
            "name": "添加用户",
            "url": "/admin/userAdd"
        }]
    },{
        "name": "权限管理",
        "icon": "&#xe609;",
        "url": "",
        "hidden": false,
        "list": [{
            "name": "角色添加",
            "url": "type_index.html"
        }, {
            "name": "角色授权",
            "url": "article_index.html"
        },
        {
            "name": "用户角色",
            "url": "article_index.html"
        }
        ]
    },{
        "name": "模型管理",
        "icon": "&#xe609;",
        "url": "",
        "hidden": false,
        "list": [{
            "name": "模型列表",
            "url": "/admin/sourceList"
        },
        {
            "name": "上传模型",
            "url": "/admin/sourceAdd"
        }]
    }, {
        "name": "系统设置",
        "icon": "&#xe620;",
        "url": "",
        "hidden": false,
        "list": [{
            "name": "网站设置",
            "url": "web_index.html"
        }, {
            "name": "友情连接",
            "url": "flink_index.html"
        }, {
            "name": "导航管理",
            "url": "nav_index.html"
        }, {
            "name": "修改密码",
            "url": "web_pwd.html"
        }, {
            "name": "清除缓存",
            "url": "web_cache.html"
        }]
    }, {
        "name": "场景管理",
        "icon": "&#xe857;",
        "url": "",
        "hidden": false,
        "list": [{
            "name": "备份数据库",
            "url": "db_backup.html"
        }, {
            "name": "还原数据库",
            "url": "db_reduction.html"
        }]
    }, {
        "name": "退出登录",
        "icon": "&#xe65c;",
        "url": "/admin/logout",
        "list": []
    }];
    let config = {
        webName : "BIM_Acount",
        menuList : menu,
        //layer全局提示层
        layerMsg: {
            offset: 't', //坐标 (详细说明 https://www.layui.com/doc/modules/layer.html#offset)
            shade: [0.4, '#000'] //遮罩 (详细说明 https://www.layui.com/doc/modules/layer.html#shade)
        }
    };
</script>
<script src="./static/admin/js/script.js"></script>
</body>
</html>
