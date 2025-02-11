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

    #displayKeyboard {
        position: fixed;
        bottom: 0px;
        padding-bottom: 50px;
        width: 100%;
        z-index: 1000;
    }

    .el-button.na {
        background-color: #C0C4CC;
        color: #E4E7ED;
    }

    .keyboard button {
        width: 80px;
        margin: 5px;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .press {
        background-color: #409EFF;
        color: #F2F6FC;
    }

    .wrong {
        background-color: #E6A23C;
        color: #F2F6FC;
    }

    .target {
        background-color: #F56C6C;
        color: #F2F6FC;
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
        <div>
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
            <div v-else>
                No Article
            </div>
            <div v-show="ifDisplayKeyboard"
                 id="displayKeyboard"
                 style="background-color:black; display: flex; justify-content: center; align-items: center;">
                <div :style="'width: 40%; ' + ((currentKeyboard === 1) ? 'padding-right: 5%' : 'padding-left: 5%')"
                     v-for="currentKeyboard of 2">
                    <span v-for="(classValue, keyName) in keyboard"
                          class="keyboard"
                          v-if="(keyboardReg[currentKeyboard - 1].includes(keyName))">
                    <br v-if="classValue === 'lineBreak'">
                    <div v-else-if="classValue === 'nextKeyboard'"></div>
                    <el-button v-else-if="classValue === 'na'" plain :class="classValue">@{{ 'NA' }}</el-button>
                    <el-button v-else plain :class="classValue">@{{ keyName }}</el-button>
                    </span>
                </div>
                <div v-if="(displayText) && (displayText.length > 0)" style="position: fixed; color: white; padding-right: 5%">
                    <span style="color: #67C23A">@{{ currentWordPass }}</span>
                    <span :style="currentTypingStyle" id="targetChar">@{{ displayText[typingIndex] }}</span>
                    <span style="color: #F2F6FC">@{{ currentWordNotYet }}</span>
                </div>
            </div>
        </div>
        <div style="margin: 10px">
            <el-input v-model="typingText"
                      id="typingText"
                      ref="typingText"
                      @input="handleTypingTextChanged"
                      @keyup.native="handleTypingKeyup"
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
            let lastCopy = localStorage.getItem('copyText');
            let ifDisplayKeyboard = localStorage.getItem('ifDisplayKeyboard');

            return {
                currentWordPass: '',
                currentWordNotYet: '',
                prevText: '',
                ifDisplayKeyboard: (null === ifDisplayKeyboard) ? true : ifDisplayKeyboard,
                typeResult: null,
                typingText: '',
                copyText: lastCopy,
                displayText: lastCopy,
                typingIndex: 0,
                currentTypingStyle: 'color: #E6A23C',
                keyboard: {
                    'NA1': 'na',
                    'NA2': 'na',
                    'NA3': 'na',
                    'P': '',
                    'Y': '',
                    'lineBreak1': 'lineBreak',
                    'A': '',
                    'O': '',
                    'E': '',
                    'U': '',
                    'I': '',
                    'lineBreak2': 'lineBreak',
                    'NA4': 'na',
                    'Q': '',
                    'J': '',
                    'K': '',
                    'X': '',
                    'F': '',
                    'G': '',
                    'C': '',
                    'R': '',
                    'L': '',
                    'lineBreak6': 'lineBreak',
                    'D': '',
                    'H': '',
                    'T': '',
                    'N': '',
                    'S': '',
                    'lineBreak7': 'lineBreak',
                    'B': '',
                    'M': '',
                    'W': '',
                    'V': '',
                    'Z': '',
                },
                keyboardReg: [
                    ['P', 'Y', 'A', 'O', 'E', 'U', 'I', 'Q', 'J', 'K', 'X', 'NA1', 'NA2', 'NA3', 'NA4', 'lineBreak1', 'lineBreak2'],
                    ['F', 'G', 'C', 'R', 'L', 'D', 'H', 'T', 'N', 'S', 'B', 'M', 'W', 'V', 'Z', 'lineBreak6', 'lineBreak7']
                ],
            }
        },
        mounted() {
            window.addEventListener("keypress", e => {
                this.focusInput();
            });

            this.modifyCurrentWord();
        },
        methods: {
            handleCopyTextChanged(value) {
                this.displayText = value;
                this.focusInput();
                localStorage.setItem('copyText', value);
            },
            handleTypingKeyup(value) {
                this.keyboard[value.key.toUpperCase()] = '';
            },
            handleTypingTextChanged(value) {
                value = value.slice(value.length - 1);
                let upperCase = value.toUpperCase();
                this.keyboard[this.prevText] = '';
                this.prevText = upperCase;
                this.keyboard[this.prevText] = 'press';
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
                        this.keyboard[this.displayText[this.typingIndex].toUpperCase()] = 'target';
                        this.keyboard[this.prevText] = 'wrong';
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
                        this.typeResult = 'jump line';
                        while((this.typingIndex < this.displayText.length) &&
                            (!this.displayText[++this.typingIndex].match(/\n/g))) {}

                        while (!this.displayText[this.typingIndex].match(/^[A-Za-z]+$/)) {
                            this.typingIndex++;
                        }

                        this.scrollToTarget();
                        break;
                    case 'ArrowUp':
                        this.typeResult = 'jump line back';
                        while((this.typingIndex !== 0) &&
                        (!this.displayText[--this.typingIndex].match(/\n/g))) {}

                        while (!this.displayText[this.typingIndex].match(/^[A-Za-z]+$/)) {
                            this.typingIndex--;
                        }

                        while((this.typingIndex !== 0) &&
                        (!this.displayText[--this.typingIndex].match(/\n/g))) {}

                        while (!this.displayText[this.typingIndex].match(/^[A-Za-z]+$/)) {
                            this.typingIndex++;
                        }


                        this.scrollToTarget();
                        break;
                }
            },
            scrollToTarget() {
                let target = document.getElementById('targetChar');
                if (target) {
                    let offset = 150;
                    window.scrollTo({
                        top: window.scrollY + target.getBoundingClientRect().top - offset,
                        behavior: 'smooth'
                    });
                }
            },
            modifyCurrentWord() {
                let startIndex = this.typingIndex;
                let lastIndex = this.typingIndex;
                while ((lastIndex < this.displayText.length) &&
                    (this.displayText[lastIndex].match(/^[A-Za-z]+$/))) {
                    lastIndex++;
                }

                while ((startIndex > 0) &&
                    (this.displayText[startIndex].match(/^[A-Za-z]+$/))) {
                    startIndex--;
                }

                this.currentWordPass = this.displayText.slice(startIndex, this.typingIndex);
                this.currentWordNotYet = this.displayText.slice(this.typingIndex + 1, lastIndex);
            },
        },
        watch: {
            typingIndex() {
                this.modifyCurrentWord();
            }
        }
    });
</script>
