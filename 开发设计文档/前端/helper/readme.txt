                                                沃米前端开发helper文件说明


/* 主要分为两部分: */

1.common-helper:主要用来放置大家公用的文件,方法,插件,工具类.

2.private-helper:可以用于放自己私有的，逻辑复杂做了封装的方法.

注释规范:注明每一个模块的功能,使用场合,如何调用.
        待功能和代码成型后,创建一个使用文档,方便查询使用方法.



/* 对于helper文件的后续补充和完善 */

    1.该js文件需要多人协同进行补充和完善.
    2.相关开发人员再开发过程中遇到公用的方法,可以进行封装和优化,补充到js库,方便以后调用,提高开发效率.
    3.补充时应该注意:
        ① 对于变量和函数的命名一定要具有概括性,语义性,方便其他开发人员理解和使用.
        ② 代码要尽量精简,功能考虑全面,要从多个方面考虑可能遇到的情况.
        ③ 注释要尽可能完善.
        ④ 把要注意的地方标注清楚.

    4.编码时应该注意:
        1. 为了避免 各种变量混乱，没有很好的隔离作用域,页面变的复杂的时候,很难去维护。
           可采取单个变量模拟命名空间。
           更为推荐的方法是：函数闭包法。把所有的东西都包在了一个自动执行的闭包里面。


            下面举例说明(统计input内输入字符的长度):

             1️⃣ 最简陋的写法就是采用全局变量和全局函数的写法。

            <!DOCTYPE html>
            <html>
            <head>
              <meta charset="utf-8">
              <title>test</title>
              <script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
              <script>
                $(function() {

                  var input = $('#J_input');

                  //用来获取字数
                  function getNum(){
                    return input.val().length;
                  }

                  //渲染元素
                  function render(){
                    var num = getNum();

                    //没有字数的容器就新建一个
                    if ($('#J_input_count').length == 0) {
                      input.after('<span id="J_input_count"></span>');
                    };

                    $('#J_input_count').html(num+'个字');
                  }

                  //监听事件
                  input.on('keyup',function(){
                    render();
                  });

                  //初始化，第一次渲染
                  render();

                })
              </script>
            </head>
            <body>
            <input type="text" id="J_input"/>
            </body>
            </html>

            缺点：
            基本功能可以实现,但是各种变量混乱，没有很好的隔离作用域,当页面变的复杂的时候,会很难去维护。当然少数的活动页面可以简单用用。




            2️⃣ 、作用域隔离

            var textCount = {
              input:null,
              init:function(config){
                this.input = $(config.id);
                this.bind();
                //这边范围对应的对象，可以实现链式调用
                return this;
              },
              bind:function(){
                var self = this;
                this.input.on('keyup',function(){
                  self.render();
                });
              },
              getNum:function(){
                return this.input.val().length;
              },
              //渲染元素
              render:function(){
                var num = this.getNum();

                if ($('#J_input_count').length == 0) {
                  this.input.after('<span id="J_input_count"></span>');
                };

                $('#J_input_count').html(num+'个字');
              }
            }

            $(function() {
              //在domready后调用
              textCount.init({id:'#J_input'}).render();
            })

            使用单个变量模拟命名空间。

            这样一改造，立马变的清晰了很多，所有的功能都在一个变量下面。代码更清晰，并且有统一的入口调用方法。

            但是还是有些瑕疵，这种写法没有私有的概念，比如上面的getNum,bind应该都是私有的方法。但是其他代码可以很随意的改动这些。当代码量特别特别多的时候，很容易出现变量重复，或被修改的问题。




             3️⃣ 、函数闭包写法：

            var TextCount = (function(){
              //私有方法，外面将访问不到
              var _bind = function(that){
                that.input.on('keyup',function(){
                  that.render();
                });
              }

              var _getNum = function(that){
                return that.input.val().length;
              }

              var TextCountFun = function(config){

              }

              TextCountFun.prototype.init = function(config) {
                this.input = $(config.id);
                _bind(this);

                return this;
              };

              TextCountFun.prototype.render = function() {
                var num = _getNum(this);

                if ($('#J_input_count').length == 0) {
                  this.input.after('<span id="J_input_count"></span>');
                };

                $('#J_input_count').html(num+'个字');
              };
              //返回构造函数
              return TextCountFun;

            })();

            $(function() {
              new TextCount().init({id:'#J_input'}).render();
            })

            这种写法，把所有的东西都包在了一个自动执行的闭包里面，所以不会受到外面的影响，并且只对外公开了TextCountFun构造函数，生成的对象只能访问到init,render方法。

            这种写法已经满足绝大多数的需求了。这种写法类似于大部分的jQuery插件写法。


            四、面向对象:

            当一个页面特别复杂，当我们需要的组件越来越多，或当我们需要做一套组件。仅仅用这个就不行了。
            首先的问题就是，这种写法太灵活了，写单个组件还可以。如果我们需要做一套风格相近的组件，而且是多个人同时在写，就需要用面向对象。
            考虑到任务,时间,技术水平等条件,面向对象写法暂时不使用.










