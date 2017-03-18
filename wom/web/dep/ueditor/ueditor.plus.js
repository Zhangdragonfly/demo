/*
UE.registerUI('fork',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'关注引导样式',cssRules:'background-position: -928px -0px;width:61px !important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});UE.registerUI('title',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'标题区样式',cssRules:'background-position: -887px -0px;width:39px! important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});UE.registerUI('con',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'内容区样式',cssRules:'background-position: -836px -0px;width:48px! important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});UE.registerUI('hutui',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'互推',cssRules:'background-position: -836px -45px;width:62px! important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});UE.registerUI('division',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'分割线样式',cssRules:'background-position: -903px -22px;width:50px! important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});UE.registerUI('guide',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'引导阅读样式',cssRules:'background-position: -836px -22px;width:62px! important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});UE.registerUI('other',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'其他样式',cssRules:'background-position:-955px -22px;width:41px! important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});UE.registerUI('mystyle',function(editor,uiName){var btn=new UE.ui.Button({name:uiName,title:'其他样式',cssRules:'background-position:-902px -45px;width:63px! important;height:20px! important;',onclick:function(){editor.execCommand(uiName);}});editor.addListener('selectionchange',function(){var state=editor.queryCommandState(uiName);if(state==-1){btn.setDisabled(true);btn.setChecked(false);}else{btn.setDisabled(false);btn.setChecked(state);}});return btn;});
*/

jQuery.fn.farbtastic=function(callback){$.farbtastic(this,callback);return this;};jQuery.farbtastic=function(container,callback){var container=$(container).get(0);return container.farbtastic||(container.farbtastic=new jQuery._farbtastic(container,callback));}

jQuery._farbtastic=function(container,callback){var fb=this;$(container).html('<div class="farbtastic"><div class="color"></div><div class="wheel"></div><div class="overlay"></div><div class="h-marker marker"></div><div class="sl-marker marker"></div></div>');var e=$('.farbtastic',container);fb.wheel=$('.wheel',container).get(0);fb.radius=84;fb.square=100;fb.width=194;if(navigator.appVersion.match(/MSIE [0-6]\./)){$('*',e).each(function(){if(this.currentStyle.backgroundImage!='none'){var image=this.currentStyle.backgroundImage;image=this.currentStyle.backgroundImage.substring(5,image.length-2);$(this).css({'backgroundImage':'none','filter':"progid:DXImageTransform.Microsoft.AlphaImageLoader(enabled=true, sizingMethod=crop, src='"+image+"')"});}});}
fb.linkTo=function(callback){if(typeof fb.callback=='object'){$(fb.callback).unbind('keyup',fb.updateValue);}
fb.color=null;if(typeof callback=='function'){fb.callback=callback;}
else if(typeof callback=='object'||typeof callback=='string'){fb.callback=$(callback);fb.callback.bind('keyup',fb.updateValue);if(fb.callback.get(0).value){fb.setColor(fb.callback.get(0).value);}}
return this;}
fb.updateValue=function(event){if(this.value&&this.value!=fb.color){fb.setColor(this.value);}}
fb.setColor=function(color){var unpack=fb.unpack(color);if(fb.color!=color&&unpack){fb.color=color;fb.rgb=unpack;fb.hsl=fb.RGBToHSL(fb.rgb);fb.updateDisplay();}
return this;}
fb.setHSL=function(hsl){fb.hsl=hsl;fb.rgb=fb.HSLToRGB(hsl);fb.color=fb.pack(fb.rgb);fb.updateDisplay();return this;}
fb.widgetCoords=function(event){var x,y;var el=event.target||event.srcElement;var reference=fb.wheel;if(typeof event.offsetX!='undefined'){var pos={x:event.offsetX,y:event.offsetY};var e=el;while(e){e.mouseX=pos.x;e.mouseY=pos.y;pos.x+=e.offsetLeft;pos.y+=e.offsetTop;e=e.offsetParent;}
var e=reference;var offset={x:0,y:0}
while(e){if(typeof e.mouseX!='undefined'){x=e.mouseX-offset.x;y=e.mouseY-offset.y;break;}
offset.x+=e.offsetLeft;offset.y+=e.offsetTop;e=e.offsetParent;}
e=el;while(e){e.mouseX=undefined;e.mouseY=undefined;e=e.offsetParent;}}
else{var pos=fb.absolutePosition(reference);x=(event.pageX||0*(event.clientX+$('html').get(0).scrollLeft))-pos.x;y=(event.pageY||0*(event.clientY+$('html').get(0).scrollTop))-pos.y;}
return{x:x-fb.width/2,y:y-fb.width/2};}
fb.mousedown=function(event){if(!document.dragging){$(document).bind('mousemove',fb.mousemove).bind('mouseup',fb.mouseup);document.dragging=true;}
var pos=fb.widgetCoords(event);fb.circleDrag=Math.max(Math.abs(pos.x),Math.abs(pos.y))*2>fb.square;fb.mousemove(event);return false;}
fb.mousemove=function(event){var pos=fb.widgetCoords(event);if(fb.circleDrag){var hue=Math.atan2(pos.x,-pos.y)/6.28;if(hue<0)hue+=1;fb.setHSL([hue,fb.hsl[1],fb.hsl[2]]);}
else{var sat=Math.max(0,Math.min(1,-(pos.x/fb.square)+.5));var lum=Math.max(0,Math.min(1,-(pos.y/fb.square)+.5));fb.setHSL([fb.hsl[0],sat,lum]);}
return false;}
fb.mouseup=function(){$(document).unbind('mousemove',fb.mousemove);$(document).unbind('mouseup',fb.mouseup);document.dragging=false;}
fb.updateDisplay=function(){var angle=fb.hsl[0]*6.28;$('.h-marker',e).css({left:Math.round(Math.sin(angle)*fb.radius+fb.width/2)+'px',top:Math.round(-Math.cos(angle)*fb.radius+fb.width/2)+'px'});$('.sl-marker',e).css({left:Math.round(fb.square*(.5-fb.hsl[1])+fb.width/2)+'px',top:Math.round(fb.square*(.5-fb.hsl[2])+fb.width/2)+'px'});$('.color',e).css('backgroundColor',fb.pack(fb.HSLToRGB([fb.hsl[0],1,0.5])));if(typeof fb.callback=='object'){$(fb.callback).css({backgroundColor:fb.color,color:fb.hsl[2]>0.5?'#000':'#fff'});$(fb.callback).each(function(){if(this.value&&this.value!=fb.color){this.value=fb.color;}});}
else if(typeof fb.callback=='function'){fb.callback.call(fb,fb.color);}}
fb.absolutePosition=function(el){var r={x:el.offsetLeft,y:el.offsetTop};if(el.offsetParent){var tmp=fb.absolutePosition(el.offsetParent);r.x+=tmp.x;r.y+=tmp.y;}
return r;};fb.pack=function(rgb){var r=Math.round(rgb[0]*255);var g=Math.round(rgb[1]*255);var b=Math.round(rgb[2]*255);return'#'+(r<16?'0':'')+r.toString(16)+(g<16?'0':'')+g.toString(16)+(b<16?'0':'')+b.toString(16);}
fb.unpack=function(color){if(color.length==7){return[parseInt('0x'+color.substring(1,3))/255,parseInt('0x'+color.substring(3,5))/255,parseInt('0x'+color.substring(5,7))/255];}
else if(color.length==4){return[parseInt('0x'+color.substring(1,2))/15,parseInt('0x'+color.substring(2,3))/15,parseInt('0x'+color.substring(3,4))/15];}}
fb.HSLToRGB=function(hsl){var m1,m2,r,g,b;var h=hsl[0],s=hsl[1],l=hsl[2];m2=(l<=0.5)?l*(s+1):l+s-l*s;m1=l*2-m2;return[this.hueToRGB(m1,m2,h+0.33333),this.hueToRGB(m1,m2,h),this.hueToRGB(m1,m2,h-0.33333)];}
fb.hueToRGB=function(m1,m2,h){h=(h<0)?h+1:((h>1)?h-1:h);if(h*6<1)return m1+(m2-m1)*h*6;if(h*2<1)return m2;if(h*3<2)return m1+(m2-m1)*(0.66666-h)*6;return m1;}
fb.RGBToHSL=function(rgb){var min,max,delta,h,s,l;var r=rgb[0],g=rgb[1],b=rgb[2];min=Math.min(r,Math.min(g,b));max=Math.max(r,Math.max(g,b));delta=max-min;l=(min+max)/2;s=0;if(l>0&&l<1){s=delta/(l<0.5?(2*l):(2-2*l));}
h=0;if(delta>0){if(max==r&&max!=g)h+=(g-b)/delta;if(max==g&&max!=b)h+=(2+(b-r)/delta);if(max==b&&max!=r)h+=(4+(r-g)/delta);h/=6;}
return[h,s,l];}
$('*',e).mousedown(fb.mousedown);fb.setColor('#000000');if(callback){fb.linkTo(callback);}}
(function(factory){if(typeof define==="function"&&define.amd){define(["jquery"],factory);}else{factory(jQuery);}}(function($,undefined){var uuid=0,slice=Array.prototype.slice,_cleanData=$.cleanData;$.cleanData=function(elems){for(var i=0,elem;(elem=elems[i])!=null;i++){try{$(elem).triggerHandler("remove");}catch(e){}}
_cleanData(elems);};$.widget=function(name,base,prototype){var fullName,existingConstructor,constructor,basePrototype,proxiedPrototype={},namespace=name.split(".")[0];name=name.split(".")[1];fullName=namespace+"-"+name;if(!prototype){prototype=base;base=$.Widget;}
$.expr[":"][fullName.toLowerCase()]=function(elem){return!!$.data(elem,fullName);};$[namespace]=$[namespace]||{};existingConstructor=$[namespace][name];constructor=$[namespace][name]=function(options,element){if(!this._createWidget){return new constructor(options,element);}
if(arguments.length){this._createWidget(options,element);}};$.extend(constructor,existingConstructor,{version:prototype.version,_proto:$.extend({},prototype),_childConstructors:[]});basePrototype=new base();basePrototype.options=$.widget.extend({},basePrototype.options);$.each(prototype,function(prop,value){if(!$.isFunction(value)){proxiedPrototype[prop]=value;return;}
proxiedPrototype[prop]=(function(){var _super=function(){return base.prototype[prop].apply(this,arguments);},_superApply=function(args){return base.prototype[prop].apply(this,args);};return function(){var __super=this._super,__superApply=this._superApply,returnValue;this._super=_super;this._superApply=_superApply;returnValue=value.apply(this,arguments);this._super=__super;this._superApply=__superApply;return returnValue;};})();});constructor.prototype=$.widget.extend(basePrototype,{widgetEventPrefix:existingConstructor?basePrototype.widgetEventPrefix:name},proxiedPrototype,{constructor:constructor,namespace:namespace,widgetName:name,widgetFullName:fullName});if(existingConstructor){$.each(existingConstructor._childConstructors,function(i,child){var childPrototype=child.prototype;$.widget(childPrototype.namespace+"."+childPrototype.widgetName,constructor,child._proto);});delete existingConstructor._childConstructors;}else{base._childConstructors.push(constructor);}
$.widget.bridge(name,constructor);};$.widget.extend=function(target){var input=slice.call(arguments,1),inputIndex=0,inputLength=input.length,key,value;for(;inputIndex<inputLength;inputIndex++){for(key in input[inputIndex]){value=input[inputIndex][key];if(input[inputIndex].hasOwnProperty(key)&&value!==undefined){if($.isPlainObject(value)){target[key]=$.isPlainObject(target[key])?$.widget.extend({},target[key],value):$.widget.extend({},value);}else{target[key]=value;}}}}
return target;};$.widget.bridge=function(name,object){var fullName=object.prototype.widgetFullName||name;$.fn[name]=function(options){var isMethodCall=typeof options==="string",args=slice.call(arguments,1),returnValue=this;options=!isMethodCall&&args.length?$.widget.extend.apply(null,[options].concat(args)):options;if(isMethodCall){this.each(function(){var methodValue,instance=$.data(this,fullName);if(!instance){return $.error("cannot call methods on "+name+" prior to initialization; "+"attempted to call method '"+options+"'");}
if(!$.isFunction(instance[options])||options.charAt(0)==="_"){return $.error("no such method '"+options+"' for "+name+" widget instance");}
methodValue=instance[options].apply(instance,args);if(methodValue!==instance&&methodValue!==undefined){returnValue=methodValue&&methodValue.jquery?returnValue.pushStack(methodValue.get()):methodValue;return false;}});}else{this.each(function(){var instance=$.data(this,fullName);if(instance){instance.option(options||{})._init();}else{$.data(this,fullName,new object(options,this));}});}
return returnValue;};};$.Widget=function(){};$.Widget._childConstructors=[];$.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",defaultElement:"<div>",options:{disabled:false,create:null},_createWidget:function(options,element){element=$(element||this.defaultElement||this)[0];this.element=$(element);this.uuid=uuid++;this.eventNamespace="."+this.widgetName+this.uuid;this.options=$.widget.extend({},this.options,this._getCreateOptions(),options);this.bindings=$();this.hoverable=$();this.focusable=$();if(element!==this){$.data(element,this.widgetFullName,this);this._on(true,this.element,{remove:function(event){if(event.target===element){this.destroy();}}});this.document=$(element.style?element.ownerDocument:element.document||element);this.window=$(this.document[0].defaultView||this.document[0].parentWindow);}
this._create();this._trigger("create",null,this._getCreateEventData());this._init();},_getCreateOptions:$.noop,_getCreateEventData:$.noop,_create:$.noop,_init:$.noop,destroy:function(){this._destroy();this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData($.camelCase(this.widgetFullName));this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName+"-disabled "+"ui-state-disabled");this.bindings.unbind(this.eventNamespace);this.hoverable.removeClass("ui-state-hover");this.focusable.removeClass("ui-state-focus");},_destroy:$.noop,widget:function(){return this.element;},option:function(key,value){var options=key,parts,curOption,i;if(arguments.length===0){return $.widget.extend({},this.options);}
if(typeof key==="string"){options={};parts=key.split(".");key=parts.shift();if(parts.length){curOption=options[key]=$.widget.extend({},this.options[key]);for(i=0;i<parts.length-1;i++){curOption[parts[i]]=curOption[parts[i]]||{};curOption=curOption[parts[i]];}
key=parts.pop();if(value===undefined){return curOption[key]===undefined?null:curOption[key];}
curOption[key]=value;}else{if(value===undefined){return this.options[key]===undefined?null:this.options[key];}
options[key]=value;}}
this._setOptions(options);return this;},_setOptions:function(options){var key;for(key in options){this._setOption(key,options[key]);}
return this;},_setOption:function(key,value){this.options[key]=value;if(key==="disabled"){this.widget().toggleClass(this.widgetFullName+"-disabled ui-state-disabled",!!value).attr("aria-disabled",value);this.hoverable.removeClass("ui-state-hover");this.focusable.removeClass("ui-state-focus");}
return this;},enable:function(){return this._setOption("disabled",false);},disable:function(){return this._setOption("disabled",true);},_on:function(suppressDisabledCheck,element,handlers){var delegateElement,instance=this;if(typeof suppressDisabledCheck!=="boolean"){handlers=element;element=suppressDisabledCheck;suppressDisabledCheck=false;}
if(!handlers){handlers=element;element=this.element;delegateElement=this.widget();}else{element=delegateElement=$(element);this.bindings=this.bindings.add(element);}
$.each(handlers,function(event,handler){function handlerProxy(){if(!suppressDisabledCheck&&(instance.options.disabled===true||$(this).hasClass("ui-state-disabled"))){return;}
return(typeof handler==="string"?instance[handler]:handler).apply(instance,arguments);}
if(typeof handler!=="string"){handlerProxy.guid=handler.guid=handler.guid||handlerProxy.guid||$.guid++;}
var match=event.match(/^(\w+)\s*(.*)$/),eventName=match[1]+instance.eventNamespace,selector=match[2];if(selector){delegateElement.delegate(selector,eventName,handlerProxy);}else{element.bind(eventName,handlerProxy);}});},_off:function(element,eventName){eventName=(eventName||"").split(" ").join(this.eventNamespace+" ")+this.eventNamespace;element.unbind(eventName).undelegate(eventName);},_delay:function(handler,delay){function handlerProxy(){return(typeof handler==="string"?instance[handler]:handler).apply(instance,arguments);}
var instance=this;return setTimeout(handlerProxy,delay||0);},_hoverable:function(element){this.hoverable=this.hoverable.add(element);this._on(element,{mouseenter:function(event){$(event.currentTarget).addClass("ui-state-hover");},mouseleave:function(event){$(event.currentTarget).removeClass("ui-state-hover");}});},_focusable:function(element){this.focusable=this.focusable.add(element);this._on(element,{focusin:function(event){$(event.currentTarget).addClass("ui-state-focus");},focusout:function(event){$(event.currentTarget).removeClass("ui-state-focus");}});},_trigger:function(type,event,data){var prop,orig,callback=this.options[type];data=data||{};event=$.Event(event);event.type=(type===this.widgetEventPrefix?type:this.widgetEventPrefix+type).toLowerCase();event.target=this.element[0];orig=event.originalEvent;if(orig){for(prop in orig){if(!(prop in event)){event[prop]=orig[prop];}}}
this.element.trigger(event,data);return!($.isFunction(callback)&&callback.apply(this.element[0],[event].concat(data))===false||event.isDefaultPrevented());}};$.each({show:"fadeIn",hide:"fadeOut"},function(method,defaultEffect){$.Widget.prototype["_"+method]=function(element,options,callback){if(typeof options==="string"){options={effect:options};}
var hasOptions,effectName=!options?method:options===true||typeof options==="number"?defaultEffect:options.effect||defaultEffect;options=options||{};if(typeof options==="number"){options={duration:options};}
hasOptions=!$.isEmptyObject(options);options.complete=callback;if(options.delay){element.delay(options.delay);}
if(hasOptions&&$.effects&&$.effects.effect[effectName]){element[method](options);}else if(effectName!==method&&element[effectName]){element[effectName](options.duration,options.easing,callback);}else{element.queue(function(next){$(this)[method]();if(callback){callback.call(element[0]);}
next();});}};});}));