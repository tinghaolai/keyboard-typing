<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/element-ui@2.13.0/lib/theme-chalk/index.css">

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>
    <div id="app">
        <div>複製文章</div>
        <el-input
            type="textarea"
            :rows="20"
            v-model="copyText">
        </el-input>
    </div>
</body>
</html>


<script src="//cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
<script src="https://cdn.bootcss.com/axios/0.19.0/axios.min.js"></script>
<script src="https://cdn.bootcss.com/element-ui/2.12.0/index.js"></script>
<script src="https://cdn.bootcss.com/element-ui/2.12.0/locale/zh-TW.js"></script>
<script type='text/javascript' id="__bs_script__">//<![CDATA[
    document.write("<script async src='/browser-sync/browser-sync-client.2.10.1.js'><\/script>".replace("HOST", location.hostname));
    //]]></script>
<script>
    console.log(1234);
    new Vue({
        el: '#app',
        data() {
            return {
                copyText: '123',
            }
        },
        mounted() {
          console.log('mounted');
        },
    });
</script>
