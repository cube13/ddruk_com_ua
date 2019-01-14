/* function zatychka() */
document.write('<style type="text/css">#unjs {display:none;}</style>')

var hiddclass = "disnone" //для совместимости

var textname="text"

function init(){/* Для добавления комментариев */
 if (window.textname) {
  document.getElementById('text').name=textname
  document.getElementById('textcom').name=textname
 }
}

function checkname(obj) { /* Существует ли уже имя файла? */
 var name=obj.value, c=''
 if (name.length>3) {
  var src="chekname.php?name="+name
  scriptRequest(src)
 }
}

// http://simon.incutio.com/archive/2004/05/26/addLoadEvent
function addLoadEvent(func) {
 var oldonload = window.onload;
 if (typeof window.onload != 'function') {
  window.onload = func;
 }
 else {
  window.onload = function() {
   oldonload();
   func();
  }
 }
}

//by http://javascript.ru/ui/offset
function getOffsetSum(elem) {
 var top=0
 while(elem) {
  top = top + parseInt(elem.offsetTop)
  elem = elem.offsetParent
 }
 return top
}

function getOffsetLeft(elem) {
 var left=0
 while(elem) {
  left = left + parseInt(elem.offsetLeft)
  elem = elem.offsetParent
 }
 return left
}

//by http://javascript.ru/ui/offset
function getTopLeft(elem) {
 var top=0, left=0
 while(elem) {
  top = top + parseInt(elem.offsetTop)
  left = left + parseInt(elem.offsetLeft)
  elem = elem.offsetParent
 }
 return {top:top, left:left}
}

function stripTags(str) {
 /* дополнительная красота (в нашем скрипте не нужно)
 str=str.replace(/(\<(br|tr|p|li|dt|dd))/ig,"\n$1"); */
 str=str.replace(/\<[^\<\>]+\>/ig,"");
 return str;
}

function striptags(str) {
 return stripTags(str)
}

//by torrents.ru
function getCookie(name) {
 var value=new RegExp("(^|;)\\s*"+name+"\\s*\\=\\s*([^;]+)($|;)","i").exec(unescape(document.cookie));
 return value && value[2];
}

function setCookie(name, value, path, time) {
// alert(name+"//"+value)
 path = (path) ? "; path=" + path : ""
 var expires = new Date()
 if (isNaN(time)) expires = ""
 else {
  if (time < 0) time = -(12*60*60*1000)
  else if (time === 0) time = 36*24*60*60*1000
  expires.setTime(expires.getTime()+time)
  expires = expires.toGMTString()
  expires = "; expires=" + expires
 }
 name=("string"==typeof(name) && name) || this.name || this.id
 value = ("undefined" == typeof(value)) ? this.value : value
 value=(this.type && "checkbox"==this.type && "on"==value)?1:value
 value=(this.type && "select-one"==this.type)?this.selectedIndex:value
 
 document.cookie = escape(name) + "=" + escape(value) + expires + path
}

function trim(str) {
 var newstr=str.replace(/^\s+|\s+$/,"");
 return newstr;
}

/* function toggle(obj) { //deprecated!!
 var el = document.getElementById(obj);
 if ( el.style.display != 'none' ) {
  el.style.display = 'none';
 }
 else {
  //иногда придётся писать 'block' или 'inline'
  el.style.display = '';
 }
} */

function getbyclass(cl, tag, obj) {
 tag = tag || "*"
 obj = obj || window.document
 var arr=[], el, list = obj.getElementsByTagName(tag)
 for (var i in list) {
  el=list[i]
//  if (!list.hasOwnProperty(i)) continue
//http://ir2.ru/javascript-if.aspx
  if (!el.tagName) continue
  if (hasClass(el, cl)) arr.push(el)
  //(i + ": " + name(el) + "\n")
 }
 return arr
}

function insertAfter(parent, node, referenceNode) {
// if (referenceNode && referenceNode.nextSibling)
  parent.insertBefore(node, referenceNode && referenceNode.nextSibling);
// else
//  parent.appendChild(node);
}

var completed=true;
function scriptRequest(src) {
 if (!completed) return false;
 var el=document.createElement("SCRIPT");
 el.type="text/javascript";
 document.body.appendChild(el);
 completed=false;
 el.src=src;
}

function hex2dec(str){
 return (parseInt(str,16))
}

function dec2hex(num){
 return num.toString(16)
}

function cancel(e) {
 e = e || window.event
 if (27==e.keyCode) hidTemp()
}

function hidTemp() { /* Спрятать всплывшее <>*/
 tempobj=window.tempobj || window.parent.tempobj //вызов из iframe
 if (!tempobj) return
 var el, t = (tempobj.length) ? tempobj : new Array(tempobj) //несколько элементов
 for (var i=0; i<t.length; i++) {
  if (t[i]) addClass(t[i], hiddclass)
 }
}

function stripSelfHref() {/* Убрать со страницы ссылки "на себя" */
 var links=document.getElementsByTagName("A");
 for (var a=0; a<links.length; a++) {
  var elem=links[a];
  if (elem.href==document.location.href) {
   elem.removeAttribute("href");
  }
 }
}

//by otvety.google.ru, mihaild, 09.09.2009 22:41:15
function get_GET() {
 var parts=document.location.search.substr(1).split("&");
 var GET={}, gettex="", curr;
 for (i=0; i<parts.length; i++) {
  curr = parts[i].split('=');
  GET[curr[0]] = curr[1];
 }
 return GET;
}

function checkform(obj){
 var list=obj.elements, el
 for (var i=0; i<list.length; i++) {
  el=list[i]
  if (hasClass(el, "need")) {
   if (!el.value) {
    alert('Обязательный элемент '+el.name)
    return false 
   }
  }
 }
}

function fetch(obj) {
 var msg = '', val, own
 for (var prop in obj) {
  if (prop) {
   try {val = obj[prop]}
   catch (e) {val = e && e.message + "!!"}
  }
  try {own = obj.hasOwnProperty(prop)}
  catch (e) {own = false}
  prop = own ? "<em>" + prop + "</em>" : prop
  msg += "<strong>" + prop + "</strong>" + ": " + val + "<br>\n"
 }
 return msg
}

/*
function findProp(arr, obj, ess){
 var prop, tmp
 for (var i=0; i<arr.length; i++) {
  tmp = arr[i]
  if (tmp in obj && (!ess || obj[tmp])) {
   prop = tmp; break;
  }
 }
 return prop
}
*/

function togglevis(obj, knop) {
 knop=knop || {}
 var i, o, note = findProp(["value", "innerHTML"], knop)
 if (typeof obj == "string")
  obj = document.getElementById(obj);
 if (!obj) return
 if (!obj.pop) obj=[obj]
 for (i=0; i<obj.length; i++) {
  o = obj[i]
  if (!hasClass(o, hiddclass)) addClass(o, hiddclass)
  else delClass(o, hiddclass)
 }
 if (!hasClass(obj[0], hiddclass)) 
  knop[note] = "Скрыть"
 else knop[note] = "Показать"
}

function findChild(obj, tag, classn, rec) {
 if (!obj) return null
 if (!obj.hasChildNodes()) return null
 var objtag //=obj.tagName !!! 16:04 13.09.2010
 var el, list=obj.childNodes
 for (var i=0; i<list.length; i++) {
  el = list[i]
  objtag = el.tagName
  if (objtag && tag.toLowerCase()==objtag.toLowerCase() 
   && (!classn || hasClass(el, classn))) {
   return el
   break
  }
  else if (rec) findChild(el, tag, classn, true)
 }
 return null
}

function findParent(obj, tag, classn){ /*22:50 29.06.2010*/
//  msg("/findP/"+obj.tagName+"//"+tag+"//"+classn)
 //передавать массив имя-значение (доделать)
 var objtag
 if (typeof(tag) == "object" && null!=tag) {
  while (obj && document.body!=obj.parentNode) {
   obj=obj.parentNode
   if (obj==tag) return obj
  }
  return null
 }
// var log1="//"+obj.tagName+" "+obj.className
 while (obj && document.body!=obj.parentNode) {
  obj=obj.parentNode //не сам объект!!
  objtag=obj && obj.tagName
//  if (!objtag) {obj=obj.parentNode; continue;}
//  msg("/c/"+objtag+"//"+tag)
  if (objtag && tag.toLowerCase()==objtag.toLowerCase()) {
   if (!classn || -1!=obj.className.indexOf(classn)) {
    return obj
   }
  }
//  log1+="//"+objtag+" "+obj.className
//  alert(log1)
 }
 return null
}

function findParent2(obj, arr){
 arr=[{name:"tagName", value:"li"}, {name:"className", value:hiddclass}]
 var prop, name, value, re
 while (obj && document.body!=obj.parentNode) {
  name=arr[0].name; value=arr[0].value; 
  re = new RegExp("\\b"+value+"\\b", "i")
  prop=obj && obj[name]
  if (obj && obj[name] && obj[name].toLowerCase()==value.toLowerCase()) {
   if (!classn || -1!=obj.className.indexOf(classn)) {
    return obj
   }
  }
  obj=obj.parentNode
//  log1+="//"+objtag+" "+obj.className
//  alert(log1)
 }
 return null
}

function setchbox(el) {
  if (!(el && el.type && "checkbox"==el.type)) return
  gc=parseInt(getCookie(el.id))
  if (gc) { /* else значения из PHP */
   ch=(1==gc)?true:false
   el.checked=ch
  }
}

function prevent(e) {
 e.cancelBubble = true
 if (e.stopPropagation) {
  e.stopPropagation()
  e.preventDefault()
 }
}

//http://www.openjs.com/scripts/dom/class_manipulation.php
function hasClass(obj, c) {
 //http://ir2.ru/javascript-if.aspx
 if (!c || !obj) return false
 var re = new RegExp('(\\s+|^)' + c + '(\\s+|$)', 'g')
 if (typeof obj == "string") obj = {className: obj}
 return (re.test(obj.className)) ? re : false
}

function delClass(obj, c) {
 if (!obj) return
 if (typeof obj == "string") obj = {className: obj, gen: true}
 var oc = obj.className, re = hasClass(oc, c)
 if (re) obj.className = oc.replace(re, " ")
 if (obj.gen) return obj.className
}

function addClass(obj, c) {
 if (!obj) return
 if (typeof obj == "string") obj = {className: obj, gen: true}
 oc = obj.className
 if (!oc || !hasClass(oc, c)) obj.className += " " + c
 if (obj.gen) return obj.className
}

function replaceClass(obj, c) {
 if (!c.length) return
 if (typeof obj == "string") obj = {className: obj, gen: true}
 var oc1 = obj.className
 if (!oc1) obj.className = c[0]
 else {
  var  oc2 = delClass(oc1, c[1])
  oc2 = addClass(oc2, c[0])
  if (oc2 != oc1) obj.className = oc2
 }
 if (obj.gen) return obj.className
}

function addclass(obj, c) {addClass(obj, c)}
function delclass(obj, c) {delClass(obj, c)}

function hidd(obj, yes) {
 if (yes) obj.className += " " + hiddclass
 else delclass(obj, hiddclass)
}

function listpop(arr, obj) {
 //удаляются все заданные одинаковые obj из arr
 //возвращается (?) первый найденный
 var ret=null
 for (var j=arr.length-1; j>-1; j--) {
  if (arr[j]==obj) {
   if (!ret) ret=arr.splice(j,1)
   else arr.splice(j,1)
  }
 }
 return ret
}

function listadd(arr, obj) {
/* проверяется уникальность и эл-т obj добавляется в arr */
 var flag=false 
 for (var j=0; j<arr.length; j++) {
  if (arr[j]==obj) {
   flag=true
   break
  }
 }
 if (!flag) rolist.push(g.expobj)
}

function zatychka() {
 document.write('<style type="text/css">#unjs {display:none;}</style>')
}

function initvar(c) {
 if (!window.d || "object" != typeof window.d) window.d={}
 c = c || window.c
 if (!c) return
 for (var prop in c) {
  var b=c[prop]
  if (0<b.length)
   d[b]=document.getElementById(b)
 }
}

function xbutt_create() {
 window.xbutt=document.createElement("BUTTON")
 var t=document.createTextNode("x")
 xbutt.appendChild(t)
 xbutt.className='vkl'
 xbutt.onclick=function(){addclass(this.parentNode, hiddclass)}
}

function deselect(obj) {
 obj = obj || document.body
 if (obj.createTextRange) {
  var r = obj.createTextRange()
  r.collapse(false)
  r.select()
 }
 else if (window.getSelection) {
  window.getSelection().collapse(obj, 0)
 }
}

function getRealStyle(obj) {
 var tmp, str = '', arr = [], list = (window.getComputedStyle) ? window.getComputedStyle(obj, null) : obj.currentStyle
 if (!list) return null
 for (var i in list) {
  tmp = "\n<br><strong>" + i + "</strong>: "
//  str += tmp
  try {
  if (i)
   arr.push(tmp + list[i])
//   str += list[i]
  }
  catch(e) {
   arr.push(tmp + e.message)
//   str += e.message
  }
 }
 arr = arr.sort()
 str = arr.join("")
 Dom = window.open("", "dom", "width=500,height=470");
 Dom.document.open();
 Dom.document.write("<h3>"+obj+" "+obj.tagName+"</h3><pre style='width:450px; height:400px; overflow:scroll; font-family:Arial; font-size:10pt;'>" + str);
 Dom.document.close();
// return arr
}
