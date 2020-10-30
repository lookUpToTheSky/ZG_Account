var vue = new Vue({
    el:'#app',
    data:{
        webName: config.webName ,
        menu:[],
        active: '/admin/main',
        address:[]
    },
    created:function(){
        let data = JSON.stringify(config.menuList);
        this.menu = JSON.parse(data);
        this.thisActive();
        this.thisAttr();
    },
    methods:{
        //记住收展
        onActive:function (pid,id=false) {
            let data;
            if(id===false){
                data = this.menu[pid];
                if(data.url.length>0){
                    this.menu.forEach((v,k)=>{
                        v.active = false;
                        v.list.forEach((v2,k2)=>{
                            v2.active = false;
                        })
                    })

                    data.active = true;

                }
                data.hidden = !data.hidden;
            }else{
                this.menu.forEach((v,k)=>{
                    v.active = false;
                    v.list.forEach((v2,k2)=>{
                        v2.active = false;
                    })
                })
                data = this.menu[pid].list[id];
            }
            if(data.url.length>0){
                if(data.target){
                    if(data.target=='_blank'){
                        window.open(data.url);
                    }else{
                        window.location.href = data.url;
                    }
                }else{
                    window.location.href = data.url;
                }

            }
        },
        //菜单高亮
        thisActive:function(){
            let newMenu = [];
            this.menu.forEach((v,k)=>{
                newMenu.push({...v});
                newMenu[k].list = [];
                newMenu[k].active = false;
                if(this.active === v.url) {
                    newMenu[k].active = true;
                }
                v.list.forEach((v2,k2)=>{
                    newMenu[k].list.push({...v2});
                    newMenu[k].list[k2].active = false;
                    if(this.active === v2.url) {
                        newMenu[k].list[k2].active = true;
                    }
                })
            })
            this.menu = newMenu;
        },
        //当前位置
        thisAttr:function () {
            //当前位置
            let address = [{
                name:'首页',
                url:'index.html'
            }];
            this.menu.forEach((v,k)=>{
                    v.list.forEach((v2,k2)=>{
                        if(v2.active){
                        address.push({
                            name:v.name,
                            url:'javascript:;'
                        })
                        address.push({
                            name:v2.name,
                            url:v2.url,
                        })
                        this.address = address;
                    }
                })
            })
        },
        onToPage(url) {
            let origin = window.location.origin;
            this.active = url;
            if($('.right iframe').attr('src') !== origin + this.active) {
                $('.right iframe').attr('src', origin + this.active);
                this.thisActive();
            }
        }
    }
})


$(document).ready(function() {
    //删除
    $(".del").click(function () {
        var url = $(this).attr("href");
        var id = $(this).attr("data-id");

        layer.confirm('你确定要删除么?', {
            btn: ['确定', '取消'] //按钮
        }, function () {
            $.get(url, function (data) {
                if (data.code == 1) {
                    $(id).fadeOut();
                    layer.msg(data.msg, {icon: 1});
                } else {
                    layer.msg(data.msg, {icon: 2});
                }
            });
        }, function () {
            layer.msg("您取消了删除!");
        });
        return false;
    });
})

function delCache(){
    sessionStorage.clear();
    localStorage.clear();
}

function msg(code=1,msg='',url='',s=3) {
    if(typeof code == 'object') {
        msg = code.msg;
        url = code.url || '';
        s = code.s || 3;
        code = code.code;
    }
    code = code==1 ? 1 : 2;
    layer.msg(msg, {icon: code,offset: config.layerMsg.offset || 't',shade: config.layerMsg.shade || [0.4, '#000']});
    if(url){
        setTimeout(function () {
            window.location.href = url;
       },s*1000);
    }
}


// //百度统计,使用时请去掉
// var _hmt = _hmt || [];
// (function() {
//     var hm = document.createElement("script");
//     hm.src = "https://hm.baidu.com/hm.js?2b45cf3bb7ac4664bb612c10feebf85d";
//     var s = document.getElementsByTagName("script")[0];
//     s.parentNode.insertBefore(hm, s);
// })();





