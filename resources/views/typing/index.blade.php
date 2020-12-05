<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/element-ui@2.13.0/lib/theme-chalk/index.css">
<style>
    #typingText.el-input__inner {
        background-color: #C0C4CC;
    }

    #typingText {
        position: fixed;
        bottom: 0;
        width: 100%;
        z-index: 1000;
    }
</style>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title>
</head>
<body>
    <div id="app">
        <div v-if="(displayText) && (displayText.length > 0)">
            Typing area
            <div v-if="typingIndex < displayText.length">現在要打的字 @{{ displayText[typingIndex] }} <span v-show="typeResult">, @{{ typeResult }}</span></div>
            <div v-else>結束</div>
            <div style="background-color: #303133;margin: 10px; white-space: pre-line">
                <span style="color: #67C23A">@{{ displayText.slice(0, typingIndex) }}</span>
                <span :style="currentTypingStyle" id="targetChar">@{{ displayText[typingIndex] }}</span>
                <span style="color: #F2F6FC">@{{ displayText.slice(typingIndex+1, displayText.length) }}</span>
            </div>
        </div>
        <div style="margin: 10px">
            <el-input v-model="typingText"
                      id="typingText"
                      ref="typingText"
                      @input="handleTypingTextChanged"
                      @keyup.backspace.native="handleBackSpace"
                      @keydown.native="handleKeyPress"
            ></el-input>
        </div>
        <span>複製文章</span>
        <el-button type="danger" plain @click="resetType">Reset</el-button>
        <el-input
            @input="handleCopyTextChanged"
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
    new Vue({
        el: '#app',
        data() {
            return {
                typeResult: null,
                typingText: '',
                copyText: '',
                displayText: '',
                typingIndex: 0,
                currentTypingStyle: 'color: #E6A23C',
            }
        },
        mounted() {
            window.addEventListener("keypress", e => {
                this.focusInput();
            });
        },
        methods: {
            handleCopyTextChanged(value) {
                this.displayText = value;
                this.focusInput();
            },
            handleTypingTextChanged(value) {
                value = value.slice(value.length - 1);
                if (this.displayText.length > 0) {
                    if (this.typingIndex > this.displayText.length) {
                        this.typeResult ='無法再輸入更多';
                        return;
                    }

                    if (value.toLowerCase() === this.displayText[this.typingIndex].toLowerCase()) {
                        this.typeResult = null;
                        this.currentTypingStyle = 'color: #E6A23C';
                        do {
                            this.typingIndex += 1;
                            if (this.typingIndex === this.displayText.length) {
                                this.typeResult ='全部輸入完成';
                                return;
                            }
                        } while (!this.displayText[this.typingIndex].match(/^[A-Za-z]+$/));
                    } else {
                        this.typeResult = '錯誤，你輸入: ' + value;
                        this.currentTypingStyle = 'color: #F56C6C';
                    }
                }
            },
            focusInput() {
                this.$refs.typingText.focus();
            },
            resetType() {
                this.currentTypingStyle = 'color: #E6A23C';
                this.typeResult = null;
                this.typingIndex = 0;
                this.focusInput();
            },
            handleBackSpace() {
                if (this.typingIndex) {
                    this.typingIndex--;
                    this.typeResult = 'backspace';
                }
            },
            handleKeyPress(value) {
                switch(value.key) {
                    case 'ArrowDown':
                        while((this.typingIndex < this.displayText.length) &&
                            (!this.displayText[++this.typingIndex].match(/\n/g)));
                        this.typeResult = 'jump line';
                        break;
                }
            },
        },
    });
</script>
