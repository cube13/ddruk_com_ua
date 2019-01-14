/* Simple Table Sorter v2.0 (+ JS Data Filter)
   http://ir2.ru/js_data_filter.aspx 
@require http://ir2.ru/ir2.js 19.08.2010 
@require http://ir2.ru/tabsort.css 23.08.2010 
*/
/* Использование: 

требуются правила CSS: 
.i {font-style:italic;} 
.num {width:3em;}
.str {width:5em;}
.disnone {display:none;}
.disnone_child .disnone_if {display:none;}
.abstopleft {position:absolute; margin: 1em; padding:.2em 1em; border:2px solid red; color: #360; background-color: #ffc; font-size:2em; }

I Сортировщик

1) подключить (через эл-т HEAD) к странице два скрипта 
 (библиотеку http://ir2.ru/ir2.js и этот);
2) сортируемым таблицам (тэгам TABLE) добавить class='sortable' 
 (можно через пробел: class='big superborder sortable');
3) в строке заголовка (при щелчке по которому происходит сортировка) 
 должны быть элементы TH (а не TD);
4) вводится 3 типа данных (str не вводится - он по умолчанию) 
 для сотрировки: num|datru|daten, их следует вписывать в ячейку 
 заголовка как атрибут: <th axis="num">;
5) пользователь может сам определять тип сортировки:
 щелчок с удержанным Shift - Number,
 щелчок с удержанным Ctrl - Date (RU),
 щелчок с удержанным Ctrl+Alt - Date (EN)
6) можно при сортировке добавлять к заголовкам ячеек 
title "Отсортировано по...", если указать в в секции 
"настройки" use_title = true;
7) можно сохранять состояние сортировки для каждой 
таблицы (в cookie), для этого в секции "настройки"
нужно указать use_cookie = true;
8) удалить куки для данной таблицы (сбросить сохранённое
состояние сортировки) можно щелчком по заголовку
с удержанными клавишами Ctrl+Alt+Shift.

II Фильтры

1) для подключения модуля фильтров по значениям колонок таблицы
с явно указанным типом сортировки (axis)
в секции "Настройки" назначить use_inputs = true;
2) для подключения модуля фильтров ко всем заголовкам сортируемых
таблиц без разбора в секции "Настройки" назначить 
use_inputs = true
inputs_force = true;
3) для удаления сообщения с количеством результатов нажать Escape;
4) для поиска в середине полей (строковый тип) добавлять перед
искомой фразой (буквой) пробел.
*/

/* Настройки */
 /* использование всплывающего title */
var use_title = true /* added by Clusta */
 /* сохранение-восстановление предыдущей сортировки */
var use_cookie = false
 /* использовать отбор строк (фильтры) */
var use_inputs = true
 /* использовать фильтры без axis (тип String) */
var inputs_force = false
/* настройки конец */

function scells(row) {
 var sarr=[], cells=row.cells;
 for (var i=0; i<cells.length; i++) {
  var c=striptags(cells[i].innerHTML).toLowerCase();
  sarr[i]=c;
 }
 return sarr;
}

function hideres(e) {
 e = e || window.event
 if (27==e.keyCode && window.result) addclass(result, "disnone")
}

addLoadEvent(preptabs);
function preptabs() {
 document.onkeyup=hideres
 window.verse=false;
 var head, uri=location.pathname, 
  inputs="<br>&gt;= <input class='num' onkeyup='data_filter(this, 1)'> <br>&lt;= <input class='num' onkeyup='data_filter(this, 2)'>",
  input="<br><input class='str' onkeyup='data_filter(this, 3)'>"
 if (use_inputs) {
  window.result=document.createElement("SPAN")
  result.id="num_res"
  result.className="abstopleft"
  document.body.appendChild(result)
 }
 var tex=document.getElementsByTagName("TABLE");
 for (var el=0; el<tex.length; el++) { /*Поиск таблиц и назначение onclick*/
  var elem=tex[el];
  if (1==elem.nodeType && "TABLE"==elem.tagName && / *sortable\b/i.test(elem.className)) {
   elem.sorted=NaN;
   elem.rarr=[el];
   if (use_cookie) {
   /* восттановление предыдущей сортировки */
    
    elem.cookid=new Array(uri+"_tab"+el, "");
    var task=getCookie(elem.cookid[0])
    if (task) {
     var taskarr, cellid
     task=task.split(",")
     for (var i=0; i<task.length; i++) {
      if (task[i]) {
       taskarr=task[i].split(":")
       if (!isNaN(parseInt(taskarr[0]))) {
        cellid=Math.abs(parseInt(taskarr[0]))
        sorttab(elem.rows[0].cells[cellid], elem.rows[0], taskarr[1])
       }
      }
     }
    }
   /* восттановление предыдущей сортировки конец */
   }
   head=elem.rows[0]
   head.onclick=clicktab;
   head.style.cursor="pointer";
/* подготовка отбора элементов */
   if (use_inputs) {
    var c, isaxis=false
    for (var k=0; k<head.cells.length; k++) {
     c=head.cells[k]
     if (c.axis) {
      c.innerHTML += ("num"==c.axis) ? inputs : input
     }
     else if (inputs_force) c.innerHTML += input
    }
   }
/* подготовка отбора элементов конец */
   if (use_title) elem.rows[0].title = "Сортировать"
   for (var j=1; j<elem.rows.length; j++) {
    if (0==j%2) elem.rows[j].style.backgroundColor="#f0f0f0";
    elem.rows[j].onmouseover=overtab; /*"Зебра" и подсветка*/
    elem.rows[j].onmouseout=overtab;
   }
  }
 }
}

var trcolor, overcolor="#ccf";
function overtab(e) {
 e = e || window.event;
 var obj=e.target || e.srcElement;
 var tr=obj.parentNode;
 if (overcolor==tr.style.backgroundColor) {
  tr.style.backgroundColor=trcolor;
 } 
 else {
  trcolor=tr.style.backgroundColor;
  tr.style.backgroundColor=overcolor;
  /*тупой перевод в формат rgb()*/
  overcolor=tr.style.backgroundColor; 
 }
}

function clicktab(e) {
 e = e || window.event;
 prevent(e);
 var sorttype=false;
 if (!e.ctrlKey && e.shiftKey && !e.altKey) sorttype="num";
 if (e.ctrlKey && !e.shiftKey && e.altKey) sorttype="daten";
 if (e.ctrlKey && !e.shiftKey && !e.altKey) sorttype="datru";
 var obj=e.target || e.srcElement;
 /* Если в заголовке сложный код */
 if ("INPUT"==obj.tagName) return;
 if ("TH"!=obj.tagName && "TD"!=obj.tagName) {
  var testobj=findParent(obj, "TH"); //ячейка заголовка
  if (!testobj) { / * обратная совместимость (с TD в заголовке) */
   testobj=findParent(obj, "TD");
   if (this!=testobj.parentNode) testobj=null;
  }
  obj=testobj;
 }
 if (!obj) return;
 if (e.ctrlKey && e.shiftKey && e.altKey) {
  var t=findParent(obj, "TABLE", "sortable");
  if (!t || "object"!=typeof t.cookid) return;
  setCookie(t.cookid[0], "", "", -1) //delete cookie
//  alert(document.cookie)
  location.reload();
  return;
 }
 sorttab(obj, this, sorttype) /* Параметры: ячейка, строка заголовка, тип сортировки */
}

function sorttab(obj, row0, sorttype) {
 var d1=new Date(); //измерять скорость
 if (!sorttype) {
  sorttype=obj && obj.axis;
  sorttype = (sorttype) ? 
   /str|num|datru|daten/i.exec(sorttype)[0] : "str";
 }
/* this - TR[0] */
 var table=findParent(obj, "TABLE"); //таблица
 var i=obj.cellIndex; /*номер сортируемой колонки*/
/* стрелки */
 addclass(obj, "i") /* требуется правило CSS: .i {font-style:italic;} */
 var otext=obj.innerHTML, ol=otext.length
 if (8595==otext.charCodeAt(ol-1)) {
  obj.innerHTML=otext.replace(/ .$/i, " &uarr;")
  if (use_title) obj.title="Отсортировано по убыванию"
  if (use_cookie && "object"==typeof table.cookid) table.cookid[1]+=","+(-i)+":"+sorttype
 }
 else if (8593==otext.charCodeAt(ol-1)) {
  obj.innerHTML=otext.replace(/ .$/i, " &darr;")
  if (use_title) obj.title="Отсортировано по возрастанию"
  if (use_cookie && "object"==typeof table.cookid) table.cookid[1]+=","+i+":"+sorttype
 }
 else {
  obj.innerHTML+=" &darr;"
  if (use_title) obj.title="Отсортировано по возрастанию"
  if (use_cookie && "object"==typeof table.cookid) table.cookid[1]+=","+i+":"+sorttype
 }
 /* удаление предыдущих стрелок */
 if (!isNaN(table.sorted) && table.sorted!=i) {
  var oldotext=row0.cells[table.sorted].innerHTML
  row0.cells[table.sorted].innerHTML=oldotext.substr(0, oldotext.length-2)
 }
 /* удаление предыдущих стрелок конец */
/* стрелки конец */
 if (use_cookie && "object"==typeof table.cookid) {
  var tocut=new RegExp(i+"\\:"+sorttype+"\\,\\-"+i+"\\:"+sorttype+"\\,"+i+"\\:"+sorttype)
  table.cookid[1]=table.cookid[1].replace(tocut, i+":"+sorttype)
//  alert(table.cookid[1])
  setCookie(table.cookid[0], table.cookid[1], "", 0)
 }
 var tbody=table.lastChild; /*TBODY - в нём лежат строки*/
 if (i==table.sorted && sorttype==table.sorttype && "object"==typeof table.rarr) { /*сортировка*/
  table.rarr=table.rarr.reverse();
 }
 else {
  if ("object"!=typeof table.rarr) table.rarr=[]
  for (var j=1; j<table.rows.length; j++) {
  /*создание массива строк таблицы и определение типа сортировки*/
   var c=table.rows[j] && table.rows[j].cells[i];
//   table.rows[j].cells[0].innerHTML+="'"+j
   /*ячейки может не быть в кривой таблице*/
   var cval=(c)?c.innerHTML:"";
   /* "Универсальный" массив, лишний тормоз при объёме
   rarr[j]={val:table.rows[j], sortcells:scells(table.rows[j])}; */
   var tmp, newval=0, sortcell=striptags(cval).toLowerCase();
   switch (sorttype) {
    case "datru":
     tmp=sortcell.split("."); 
     for (var x=tmp.length-1; x>=0; x--) {
      newval+=parseInt(tmp[x]) * Math.pow(100, x)
     }
     sortcell = (isNaN(newval)) ? Infinity : newval; 
     break
    case "daten":
     tmp=sortcell.split(/\.|\//); 
     if (tmp[2]) newval+=parseInt(tmp[2]) * Math.pow(100, 2);
     if (tmp[0]) newval+=parseInt(tmp[0]) * Math.pow(100, 1);
     if (tmp[1]) newval+=parseInt(tmp[1]);
     sortcell = (isNaN(newval)) ? Infinity : newval; 
     break
    case "num":
     sortcell = Number(sortcell);
     sortcell = (isNaN(sortcell)) ? Infinity : sortcell; 
     break
    default:
     sortcell=String(sortcell)
     break
   }
   table.sorttype=sorttype;
   table.rarr[j-1]={val:table.rows[j], sortcell:sortcell};
   //table.deleteRow(j); /*Не нужно!*/
  } /*создание массива конец*/
  table.rarr=table.rarr.sort(cmprow);
 } 
 table.sorted=i; /*сортировка конец*/
 /*добавить строки отсортированного массива в таблицу*/
 for (var l=0; l<table.rarr.length; l++) {
  if (table.rarr[l] && table.rarr[l].val) {
   if (l%2) table.rarr[l].val.style.backgroundColor="f0f0f0";
   else table.rarr[l].val.style.backgroundColor="fff";
   tbody.appendChild(table.rarr[l].val);
  }
  //строка добавляется в таблицу, удаляясь из той же таблицы
  //(массив rarr - только упорядоченные ссылки на строки)
 }
 
 /*var d3=new Date(); alert(d3-d1); d1=d3; //раскомментировать для учёта времени*/
}

function cmprow(a, b) {
 a=a.sortcell, b=b.sortcell;
 try { 
  /* verse нужно, если восстановливать сорт. из куки 
   - на практике это оказалось не так */
  if (a > b) return (verse)?-1:1;
  if (a < b) return (verse)?1:-1;
 }
 catch(e){return 0;}
 return 0;
}

/******************** JS Data Filter *******************/

function data_filter(obj, n) {
 var invis_class="disnone_if", q, q_int, qr, qrx, row, 
  val, val_int, c=obj.parentNode, counter=0
  pflag=false, t=findParent(obj, "TABLE"), cid=c.cellIndex,
  fields=c && c.getElementsByTagName("INPUT"),
  field1=fields && fields[0], val1=field1 && field1.value, val1_int=parseInt(val1),
  field2=fields && fields[1], val2=field2 && field2.value, val2_int=parseInt(val2)
 window.result=document.getElementById("num_res")
 result.style.top=getTopLeft(c).top-100
 result.style.left=getTopLeft(c).left+10
 if (!val1 && !val2) {
  delclass(t, "disnone_child")
  return
 }
 if (obj.axis && obj.axis==obj.value && !field2) return
 if (window.pworking) return
 obj.axis=obj.value
 q=obj.value.toLocaleLowerCase()
 q_int=parseInt(q)
 qr=q.substr(1)
 qrx="/^"+qr+"/"
 window.pworking=true
 for (var i=1; i<t.rows.length; i++) {
  row=t.rows[i]
  val=striptags(row.cells[cid].innerHTML).toLocaleLowerCase()
  val_int=parseInt(val)
  switch (n) {
   case 1:
    if ((isNaN(q_int) || val_int>=q_int) && (isNaN(val2_int) || val_int<=val2_int)) {
     delclass(row, invis_class)
     counter++
     pflag = true
    }
    else addclass(row, invis_class)
   break
   case 2:
    if ((isNaN(q_int) || val_int<=q_int) && (isNaN(val1_int) || val_int>=val1_int)) {
     delclass(row, invis_class)
     counter++
     pflag = true
    }
    else addclass(row, invis_class)
   break
   default:
    if (0==q.indexOf(" ")) {
     if (val.indexOf(qr)>-1) {
      delclass(row, invis_class)
      counter++
      pflag = true
     }
     else addclass(row, invis_class)
    }
    else {
     if (0==val.indexOf(q)) {
      delclass(row, invis_class)
      counter++
      pflag = true
     }
     else addclass(row, invis_class)
    }
   break
  }
 }
 result.innerHTML=counter
 if (pflag) {
  delclass(result, "disnone")
  obj.style.color="#000"
  addclass(t, "disnone_child")
 }
 else {
  obj.style.color="#c00"
  delclass(t, "disnone_child")
 }
 window.pworking=false
}

