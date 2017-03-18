$(function () {
    // ~~~~~~~~~~~侧边导航~~~~~~~~
    $('.slideBar .slideButton:nth-child(2)').hover(function () {
        $(this).stop().animate({
            width:'250px'
        },500);
        $(this).find('div').css({
            display:'inline-block'
        });
    },function () {
        $(this).find('div').css({
            display:'none'
        });
        $(this).stop().animate({
            width:'40px',
            background:'#3f3f3f'
        },500);
    });
    $('.slideBar .slideButton:nth-child(1),.slideBar .slideButton:nth-child(3)').hover(function () {
        $(this).children('.slideCode').css({
            display:'inline-block'
        });
        $(this).children('.slideCode').stop().animate({
            right:'40px'
        },500);
    },function () {
        $(this).children('.slideCode').stop().animate({
            right:'0',
            background:'#3f3f3f'
        },500);
        $(this).children('.slideCode').css({
            display:'none'
        });
    });
    $('.slideBar .slideButton:nth-child(4)').on('click',function () {
        $('html,body').stop().animate({
            scrollTop:'0'
        },500)
    })
    // ~~~~~~~~~~~顶部头像动画~~~~~~~~
    $('.top-content-icon').hover(function () {
        $(this).children('.shadow').stop().animate({
            top:0
        },500)
    },function () {
        $(this).children('.shadow').stop().animate({
            top:'139px'
        },500)
    })
    // ~~~~~~~~~~~自定义滚动条~~~~~~~~
    // $("#customScroll").panel({iWheelStep:32});
    // ~~~~~~~~~~~底部轮播~~~~~~~~
    var tab=document.getElementById("tab");
    var tabLi=tab.getElementsByTagName("li");
    var banner2=document.getElementById("banner2");
    var wrap=document.getElementById("wrap");
    var pic=document.getElementById("pic");
    var picLi=pic.getElementsByTagName("li");
    var dot=document.getElementById("dot");
    var dotLi=dot.getElementsByTagName("li");
    var prev=document.getElementById("prev");
    var next=document.getElementById("next");
    pic.innerHTML+=pic.innerHTML;
    pic.style.width=picLi.length*wrap.offsetWidth+"px";
    var index=0;
    var timer=null;
    function nextFn(){
        index++;
        if(index>dotLi.length-1){
            index=0;
        }
        if(pic.offsetLeft<=(-picLi.length*wrap.offsetWidth)/2){
            pic.style.left="0px";
            pic.style.transitionDuration="0s";
        }
        pic.style.left=pic.offsetLeft-wrap.offsetWidth+"px";
        pic.style.transition="all .4s ease";

        for (var i=0;i<dotLi.length;i++) {
            tabLi[i].className="";
            dotLi[i].className="";
        }
        tabLi[index].className="active1";
        dotLi[index].className="active2";
    }
    function prevFn(){
        index--;
        if(index<0){
            index=dotLi.length-1;
        }
        if(pic.offsetLeft>=(-wrap.offsetWidth)){
            pic.style.left=(-picLi.length*wrap.offsetWidth)/2+"px";
            pic.style.transitionDuration="0s";
        }
        pic.style.left=pic.offsetLeft+wrap.offsetWidth+"px";
        pic.style.transition="all .4s ease";
        for (var i=0;i<dotLi.length;i++) {
            tabLi[i].className="";
            dotLi[i].className="";
        }
        tabLi[index].className="active1";
        dotLi[index].className="active2";
    }
    prev.onclick=function(){
        prevFn();
    }
    next.onclick=function(){
        nextFn();
    }
    // run();
   /* function run(){
        clearInterval(timer);
        timer=setInterval(function(){
            nextFn();
        },3000);
    }*/
    for (var i=0;i<dotLi.length;i++) {
        dotLi[i].index1=i;
        dotLi[i].onclick=function(){
            index1=this.index1;
            for (var i = 0; i < dotLi.length; i++){
                dotLi[i].className="";
                tabLi[i].className="";
            }
            tabLi[index1].className="active1";
            this.className="active2";
            run();
            pic.style.left= -this.index1*wrap.offsetWidth+"px";
        }
    }
    for (var i=0;i<tabLi.length;i++) {
        tabLi[i].index2=i;
        tabLi[i].onclick=function(){
            index2=this.index2;
            for (var i = 0; i < tabLi.length; i++){
                dotLi[i].className="";
                tabLi[i].className="";
            }
            dotLi[index2].className="active2";
            this.className="active1";
            // run();
            pic.style.left= -this.index2*wrap.offsetWidth+"px";
        }
    }
    banner2.onmouseenter=function(){
        clearInterval(timer);
    }
    // banner2.onmouseleave=function(){
    //     run();
    // }
    tab.onmouseenter=function(){
        clearInterval(timer);
    }
    // tab.onmouseleave=function(){
    //     run();
    // }
    // ~~~~~~~~~~~底部下拉列表~~~~~~~~
    $('.footer-form .dropdown ul li').on('click',function () {
        var txt = $(this).text() + '<span class="icon"></span>';
        $('.footer-form .dropdown .form-demand').html(txt);
    });
    // ~~~~~~~~~~~底部表单验证~~~~~~~~
    $(".footer-form input").on('blur',function () {
        var $thisVal = $(this).val().trim();
        var $thisClass = $(this).attr('class');
        var regTel=/^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/;
        var regEmail=/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/;
        var regBudget=/^([0-9]+|[0-9]{1,3}(,[0-9]{3})*)(.[0-9]{1,2})?$/;
        if($thisClass == 'name' || $thisClass == 'company'){
            if($thisVal == ""){
                swal({
                    title:"该项不能为空",
                    text: "该弹框将会在1.5秒后关闭",
                    timer:1500,
                    showConfirmButton:false
                });
                forbidClick();
            }
        }else if ($thisClass == 'tel'){
            if (!regTel.test($thisVal)){
                swal({
                    title:"请输入正确的号码",
                    text: "该弹框将会在1.5秒后关闭",
                    timer:1500,
                    showConfirmButton:false
                });
                forbidClick();
            }
        }else if ($thisClass == 'email'){
            if (!regEmail.test($thisVal)){
                swal({
                    title:"请输入正确的邮箱",
                    text: "该弹框将会在1.5秒后关闭",
                    timer:1500,
                    showConfirmButton:false
                });
                forbidClick();
            }
        }else if ($thisClass == 'budget'){
            if (!regBudget.test($thisVal)){
                swal({
                    title:"请输入正确的金额",
                    text: "该弹框将会在1.5秒后关闭",
                    timer:1500,
                    showConfirmButton:false
                });
                forbidClick();
            }
        }
    })
    $(".footer-form div.submit").on('click',function () {
        if(forbidClick){
            return false;
        }
    })
});
function forbidClick() {
    return false;
}
