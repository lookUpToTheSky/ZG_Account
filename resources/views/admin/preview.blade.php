<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>模型预览</title>
    <link rel="stylesheet" href={{asset("admin/static/common/layui/css/layui.css")}}>
    <link rel="stylesheet" href={{asset("admin/static/admin/css/style.css")}}>
    <script src={{asset("admin/static/common/layui/layui.js")}}></script>
    <script src={{asset("admin/static/common/jquery-3.3.1.min.js")}}></script>
    <script src={{asset("admin/static/common/three/three.min.js")}}></script>
    <script src={{asset("admin/static/common/three/OrbitControls.js")}}></script>

    <script src={{asset("admin/static/common/three/EffectComposer.js")}}></script>
    <script src={{asset("admin/static/common/three/CopyShader.js")}}></script>
    <script src={{asset("admin/static/common/three/RenderPass.js")}}></script>
    <script src={{asset("admin/static/common/three/ShaderPass.js")}}></script>
    <script src={{asset("admin/static/common/three/OutlinePass.js")}}></script>
    <script src={{asset("admin/static/common/three/SMAAShader.js")}}></script>
    <script src={{asset("admin/static/common/three/SMAAPass.js")}}></script>
    <script src={{asset("admin/static/common/three/LuminosityHighPassShader.js")}}></script>
    <script src={{asset("admin/static/common/three/UnrealBloomPass.js")}}></script>

    <script src={{asset("admin/static/common/three/MTLLoader.js")}}></script>
    <script src={{asset("admin/static/common/three/OBJLoader.js")}}></script>
    <script src={{asset("admin/static/common/three/inflate.min.js")}}></script>
    <script src={{asset("admin/static/common/three/FBXLoader.js")}}></script>
    <script src={{asset("admin/static/common/three/DRACOLoader.js")}}></script>
    <script src={{asset("admin/static/common/three/GLTFLoader.js")}}></script>
    <script src={{asset("admin/static/common/three/onEvent.js")}}></script>
    <style>
        #threeView {
            width: 100vw;
            height: 100vh;
            overflow: hidden;
        }
    </style>
</head>
<body onload="init()">
    <div id="threeView"></div>
</body>
    <script>
        let params = GetRequest(location.search);
        let camera, scene, renderer, controls, stats, composer, outLineColor;
        let objLoader = new THREE.OBJLoader();
        let mtlLoader = new THREE.MTLLoader();
        let fbxLoader = new THREE.FBXLoader();
        let gltfLoader = new THREE.GLTFLoader();
        const threeView = $('#threeView');
        //3D场景初始化
        function init() {
            initScene();
            animate();
            setSkyBox('dark');
            // loadModel(url, name);
            switch(params.type.toLowerCase()){
                case '.obj': loadModelObj(params.url, params.name);
                    break;
                case '.fbx': loadModelFbx(params.url, params.name);
                    break;
                case '.gltf': loadModelGltf(params.url, params.name);
                    break;
            }
        }
        function initScene() {
            camera = new THREE.PerspectiveCamera( 75, threeView.width() / threeView.height(), 1, 20000 );
            // camera.position.set( -230, 150, 0 ); 
            camera.position.set( 0, 150, 350 ); 
            // camera.lookAt(0,0,0);
            scene = new THREE.Scene();
            THREE.onEvent(scene, camera,threeView.width(), threeView.height());//事件初始化
            //场景中添加网格辅助
            let grid = new THREE.GridHelper( 1200, 20, 0x38b0de, 0x38b0de);
            // scene.add( grid );
            // 给场景添加一个环境光
            let ambientLight = new THREE.AmbientLight( 0x404040);
            scene.add( ambientLight );
            // 给场景添加一个平行光出来
            let dirLight = new THREE.DirectionalLight( 0xffffff);
            dirLight.position.set( 1, 1.75, 1 );
            scene.add( dirLight );
            //实例化一个渲染器s
            renderer = new THREE.WebGLRenderer( { antialias: true, alpha: true } );
            renderer.setPixelRatio( window.devicePixelRatio );
            renderer.setSize( threeView.width(), threeView.height());
            // renderer.setClearColor( 0xffffff, 0.1);   
            threeView.append( renderer.domElement );
            //控制相机
            controls = new THREE.OrbitControls( camera, renderer.domElement);
            controls.minPolarAngle =0;
            controls.maxPolarAngle =Math.PI/2.1;
            //设置相机移动距离
            controls.minDistance = 100;
            controls.maxDistance = 1000;
            // 使动画循环使用时阻尼或自转 意思是否有惯性
            controls.enableDamping = true;
            //动态阻尼系数 就是鼠标拖拽旋转灵敏度
            controls.dampingFactor = 0.6;
            window.addEventListener( 'resize', onWindowResize, false );
            composerPass(renderer);
        }
        //后期通道
        function composerPass(renderer) {
            composer = new THREE.EffectComposer(renderer);//通道组合器
            var renderPass = new THREE.RenderPass( scene, camera );//渲染一个新环境
            // 外边框outLine
            outLineColor = new THREE.OutlinePass( 
                new THREE.Vector2( threeView.width(), threeView.height() ), scene, camera);
            outLineColor.visibleEdgeColor.set( 'orangered' );
            outLineColor.edgeStrength = 8;
            //场景发光
            var bloomPass = new THREE.UnrealBloomPass(new THREE.Vector2( threeView.width(), threeView.height() ))
            bloomPass.exposure =1;
            bloomPass.threshold = 0;
            bloomPass.strength = .5;
            bloomPass.radius = 1;
            bloomPass.enabled  = false;
            //抗锯齿SMAAShader
            var SMAAShader= new THREE.SMAAPass( threeView.width(),  threeView.height() );
            SMAAShader.renderToScreen = true;

            composer.addPass( renderPass );
            composer.addPass( bloomPass );
            composer.addPass(outLineColor);
            composer.addPass( SMAAShader );

        }
        //屏幕宽度监听
        function onWindowResize() {
            camera.aspect = threeView.width() /threeView.height();
            camera.updateProjectionMatrix();
            renderer.setSize( threeView.width(),threeView.height() );
            composer.setSize( threeView.width(),threeView.height() );
            THREE.onEvent(scene, camera,threeView.width(), threeView.height());//事件初始化
        }
        //天空盒
        function setSkyBox(type) {
            // if(sky != undefined) scene.remove(sky);
            let loader = new THREE.TextureLoader();
            let skyBox = new THREE.BoxGeometry(20000,20000,20000);
            let rootPath = 'image/';
            let imgNameArr = ['_posx','_negx','_posy','_negy','_posz','_negz'];
            let format = '.jpg';
            let materialArr = [];
            for(let i=0; i< imgNameArr.length;i++) {
                materialArr.push(new THREE.MeshBasicMaterial({
                    map:loader.load(rootPath+type+imgNameArr[i]+format),
                    side: THREE.BackSide}));
            }
            let sky = new THREE.Mesh(skyBox, materialArr);
            sky.name = 'skyBox';
            scene.add(sky);
        }
        //obj
        function loadModelObj(fileUrl, fileName) {
            return new Promise((reslove, reject) => {
                // mtlLoader.load(fileUrl, function(material) {
                //     material.preload();
                //     objLoader.setMaterials(material);
                    objLoader.load(fileUrl, function(object) {
                        object.name = fileName;
                        scene.add(object);
                        reslove();
                    });   
                // })
            }); 
        }
         //fbx
         function loadModelFbx(fileUrl, fileName) {
            return new Promise((reslove, reject) => {
                fbxLoader.load(fileUrl, function(object) {
                    object.name = fileName;
                    scene.add(object);
                    reslove();
                });  
            }); 
        }
        //gltf
        function loadModelGltf(fileUrl, fileName) {
            return new Promise((reslove, reject) => {
                gltfLoader.load(fileUrl, function(object) {
                    object.name = fileName;
                    scene.add(object);
                    reslove();
                });  
            }); 
        }
        //渲染
        function animate() {
            //更新性能插件
            controls.update();
            // renderer.render(scene, camera);=
            composer.render();
            composer.autoClear = false; 
            requestAnimationFrame(animate);
        }
        function GetRequest() {
            var url = decodeURI(location.search); //获取url中"?"符后的字串
            var theRequest = new Object();
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for(var i = 0; i < strs.length; i ++) {
                    theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
                }
            }
            return theRequest;
        }
    </script>
</html>