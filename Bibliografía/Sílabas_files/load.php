(function($,mw){mw.loader.using('mediawiki.util',function(){if($.client.profile().name=='msie'){var oldWidth;var docEl=document.documentElement;var doFixIEScroll=function(){docEl.style.overflowX=(docEl.scrollWidth-docEl.clientWidth<4)?"hidden":"";};var fixIEScroll=function(){if(!oldWidth||docEl.clientWidth>oldWidth){doFixIEScroll();}else{setTimeout(doFixIEScroll,1);}oldWidth=docEl.clientWidth;};document.attachEvent("onreadystatechange",fixIEScroll);document.attachEvent("onresize",fixIEScroll);mw.util.addCSS('@media print { sup, sub, p { line-height: normal; } }');mw.util.addCSS('div.overflowbugx { overflow-x: scroll !important; overflow-y: hidden !important; } div.overflowbugy { overflow-y: scroll !important; overflow-x: hidden !important; }');mw.util.addCSS('.iezoomfix div, .iezoomfix table { zoom: 1;}');if($.client.profile().versionBase=='8'){$('.hlist').find('dd:last-child, dt:last-child, li:last-child').addClass('hlist-last-child');}if($.client.profile().versionBase<'8'){$('.hlist'
).find('dt + dd, dt + dt').prev().append('<b>:</b> ');$('.hlist').find('dd + dd, dd + dt, li + li').prev().append('<b>·</b> ');$('.hlist').find('dl dl, ol ol, ul ul').prepend('( ').append(') ');}}if(navigator.appVersion.search(/windows nt 5/i)!=-1){mw.util.addCSS('.IPA {font-family: "Lucida Sans Unicode", "Arial Unicode MS";} '+'.Unicode {font-family: "Arial Unicode MS", "Lucida Sans Unicode";}');}if(mw.config.get('wgArticleId')===0&&mw.config.get('wgNamespaceNumber')===2){var titleParts=mw.config.get('wgPageName').split('/');if(titleParts.length==2){var userSkinPage=titleParts[0]+'/'+mw.config.get('skin');if(titleParts[1]==='skin.js'){window.location.href=mw.util.wikiGetlink(userSkinPage+'.js');}else if(titleParts[1]==='skin.css'){window.location.href=mw.util.wikiGetlink(userSkinPage+'.css');}}}var extraCSS=mw.util.getParamValue("withCSS");if(extraCSS&&extraCSS.match(/^MediaWiki:[^&<>=%]*\.css$/)){importStylesheet(extraCSS);}var extraJS=mw.util.getParamValue("withJS");if(extraJS&&
extraJS.match(/^MediaWiki:[^&<>=%]*\.js$/)){importScript(extraJS);}if($.inArray(mw.config.get('wgAction'),['edit','submit'])>-1){$(document).ready(function(){if(document.location.search.indexOf("undo=")!=-1&&document.getElementsByName('wpAutoSummary')[0]){document.getElementsByName('wpAutoSummary')[0].value='1';}});importScript('MediaWiki:Edittools.javascript');}else if(mw.config.get('wgPageName')=='Especial:Seguimiento'){mw.loader.load(mw.config.get('wgServer')+mw.config.get('wgScript')+'?title=MediaWiki:Common.js/seguimiento.js&action=raw&ctype=text/javascript&smaxage=21600&maxage=86400');}importScript('MediaWiki:Wikibugs.js');window.wma_settings={buttonImage:"//upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Erioll_world.svg/15px-Erioll_world.svg.png"};mw.loader.load('//meta.wikimedia.org/w/index.php?title=MediaWiki:Wikiminiatlas.js&action=raw&ctype=text/javascript&smaxage=21600&maxage=86400');window.osm_proj_map='mapa';window.osm_proj_lang='es';mw.loader.load(
'//meta.wikimedia.org/w/index.php?title=MediaWiki:OSM.js&action=raw&ctype=text/javascript&smaxage=21600&maxage=86400');if($.inArray(mw.config.get('wgPageName'),['Wikipedia:Portada','Wikipedia Discusión:Portada'])>-1){$(document).ready(function(){mw.util.addPortletLink('p-lang','//es.wikipedia.org/wiki/Anexo:Wikipedias','Lista completa','interwiki-completelist','Lista completa de Wikipedias');});}var paginasSinRE=["Wikipedia:Tablón_de_anuncios_de_los_bibliotecarios/Portal/Plantillas/Fusión_de_historiales/precarga","Wikipedia:Tablón_de_anuncios_de_los_bibliotecarios/Portal/Plantillas/Permisos/precarga","Wikipedia:Bot/Solicitudes/Precarga"];if($.inArray(mw.util.getParamValue('preload'),paginasSinRE)>-1){$(document).ready(function(){$('#wpSummary').hide();$('#wpSummaryLabel').hide();});}if(mw.config.get('wgCanonicalSpecialPageName')=="Search"){var searchEngines={mediawiki:{ShortName:"Búsqueda interna",Template:mw.config.get('wgScript')+"?search={searchTerms}"},exalead:{ShortName:
"Exalead",Template:"http://www.exalead.com/search/wikipedia/results/?q={searchTerms}&language=es"},google:{ShortName:"Google",Template:"http://www.google.es/search?as_sitesearch=es.wikipedia.org&hl={language}&q={searchTerms}"},wikiwix:{ShortName:"Wikiwix",Template:"http://es.wikiwix.com/index.php?action={searchTerms}&lang={language}"},wlive:{ShortName:"Bing",Template:"http://www.bing.com/search?q={searchTerms}&q1=site:http://es.wikipedia.org"},yahoo:{ShortName:"Yahoo!",Template:"http://es.search.yahoo.com/search?p={searchTerms}&vs=es.wikipedia.org"},globalwpsearch:{ShortName:"Global WP",Template:"http://vs.aka-online.de/cgi-bin/globalwpsearch.pl?timeout=120&search={searchTerms}"}};function changeSearchEngine(selectedId,searchTerms){var currentId=document.getElementById("searchengineChoices").currentChoice;if(selectedId==currentId){return;}document.getElementById("searchengineChoices").currentChoice=selectedId;var radio=document.getElementById('searchengineRadio-'+selectedId);radio.
checked="checked";var engine=searchEngines[selectedId];var p=engine.Template.indexOf('?');var params=engine.Template.substr(p+1);var form;if(document.forms.search){form=document.forms.search;}else{form=document.getElementById("powersearch");}form.setAttribute("action",engine.Template.substr(0,p));var l=(""+params).split("&"),idx;for(idx=0;idx<l.length;idx++){var p2=l[idx].split("=");var pValue=p2[1];if(pValue=="{language}"){}else if(pValue=="{searchTerms}"){var input;input=document.getElementById("searchText");input.name=p2[0];}else{var input2=document.getElementById("searchengineextraparam");input2.name=p2[0];input2.value=pValue;}}}function externalSearchEngines(){if(typeof SpecialSearchEnhanced2Disabled!='undefined'){return;}var mainNode=document.getElementById("powersearch");if(!mainNode){mainNode=document.getElementById("search");}if(!mainNode){return;}var beforeNode=document.getElementById("mw-search-top-table");if(!beforeNode){return;}beforeNode=beforeNode.nextSibling;if(!
beforeNode){return;}var firstEngine="mediawiki";var choices=document.createElement("div");choices.setAttribute("id","searchengineChoices");choices.style.textAlign="center";var lsearchbox=document.getElementById("searchText");if(!lsearchbox){return;}var initValue=lsearchbox.value;var space="",id;for(id in searchEngines){var engine=searchEngines[id];if(engine.ShortName){if(space){choices.appendChild(space);}space=document.createTextNode(" ");var attr={type:"radio",name:"searchengineselect",value:id,onFocus:"changeSearchEngine(this.value)",id:"searchengineRadio-"+id};var html="<input",a;for(a in attr){html+=" "+a+"='"+attr[a]+"'";}html+=" />";var span=document.createElement("span");span.innerHTML=html;choices.appendChild(span);var label=document.createElement("label");label.htmlFor="searchengineRadio-"+id;if(engine.Template.indexOf('http')==0){var lienMoteur=document.createElement("a");lienMoteur.href=engine.Template.replace("{searchTerms}",initValue).replace("{language}","es");lienMoteur
.appendChild(document.createTextNode(engine.ShortName));label.appendChild(lienMoteur);}else{label.appendChild(document.createTextNode(engine.ShortName));}choices.appendChild(label);}}mainNode.insertBefore(choices,beforeNode);var input=document.createElement("input");input.id="searchengineextraparam";input.type="hidden";mainNode.insertBefore(input,beforeNode);changeSearchEngine(firstEngine,initValue);}$(document).ready(externalSearchEngines);}if(document.getElementById("cierraPadre")){$(document).ready(function(){document.getElementById("cierraPadre").childNodes[0].onclick=function(){document.getElementById("cierraPadre").style.cursor='pointer';document.getElementById("cierraPadre").parentNode.style.display='none';return false;};});}var autoCollapse=2;var collapseCaption="ocultar";var expandCaption="mostrar";window.collapseTable=function(tableIndex){var Button=document.getElementById("collapseButton"+tableIndex),Table=document.getElementById("collapsibleTable"+tableIndex),i;if(!Table||!
Button){return false;}var Rows=Table.rows;if(Button.firstChild.data==collapseCaption){for(i=1;i<Rows.length;i++){Rows[i].style.display="none";}Button.firstChild.data=expandCaption;}else{for(i=1;i<Rows.length;i++){Rows[i].style.display=Rows[0].style.display;}Button.firstChild.data=collapseCaption;}};function createCollapseButtons(){var tableIndex=0,NavigationBoxes={},Tables=document.getElementsByTagName("table"),i;for(i=0;i<Tables.length;i++){if($(Tables[i]).hasClass('collapsible')){var HeaderRow=Tables[i].getElementsByTagName("tr")[0];if(!HeaderRow){continue;}var Header=HeaderRow.getElementsByTagName("th")[0];if(!Header){continue;}NavigationBoxes[tableIndex]=Tables[i];Tables[i].setAttribute("id","collapsibleTable"+tableIndex);var Button=document.createElement("span");var ButtonLink=document.createElement("a");var ButtonText=document.createTextNode(collapseCaption);Button.className="collapseButton";ButtonLink.style.color=Header.style.color;ButtonLink.setAttribute("id","collapseButton"+
tableIndex);ButtonLink.setAttribute("href","#");addHandler(ButtonLink,"click",new Function("evt","collapseTable("+tableIndex+" ); return killEvt( evt );"));ButtonLink.appendChild(ButtonText);Button.appendChild(document.createTextNode("["));Button.appendChild(ButtonLink);Button.appendChild(document.createTextNode("]"));Header.insertBefore(Button,Header.firstChild);tableIndex++;}}for(i=0;i<tableIndex;i++){if($(NavigationBoxes[i]).hasClass('collapsed')||(tableIndex>=autoCollapse&&$(NavigationBoxes[i]).hasClass('autocollapse'))){window.collapseTable(i);}else if($(NavigationBoxes[i]).hasClass('innercollapse')){var element=NavigationBoxes[i];while(element=element.parentNode){if($(element).hasClass('outercollapse')){window.collapseTable(i);break;}}}}}$(document).ready(createCollapseButtons);var NavigationBarHide='['+collapseCaption+']';var NavigationBarShow='['+expandCaption+']';var NavigationBarShowDefault=0;window.toggleNavigationBar=function(indexNavigationBar){var NavToggle=document.
getElementById("NavToggle"+indexNavigationBar),NavFrame=document.getElementById("NavFrame"+indexNavigationBar),NavChild;if(!NavFrame||!NavToggle){return false;}if(NavToggle.firstChild.data==NavigationBarHide){for(NavChild=NavFrame.firstChild;NavChild!=null;NavChild=NavChild.nextSibling){if($(NavChild).hasClass('NavContent')||$(NavChild).hasClass('NavPic')){NavChild.style.display='none';}}NavToggle.firstChild.data=NavigationBarShow;}else if(NavToggle.firstChild.data==NavigationBarShow){for(NavChild=NavFrame.firstChild;NavChild!=null;NavChild=NavChild.nextSibling){if($(NavChild).hasClass('NavContent')||$(NavChild).hasClass('NavPic')){NavChild.style.display='block';}}NavToggle.firstChild.data=NavigationBarHide;}};function createNavigationBarToggleButton(){var indexNavigationBar=0,divs=document.getElementsByTagName("div"),NavFrame,NavChild,i;for(i=0;NavFrame=divs[i];i++){if($(NavFrame).hasClass('NavFrame')){indexNavigationBar++;var NavToggle=document.createElement("a");NavToggle.className=
'NavToggle';NavToggle.setAttribute('id','NavToggle'+indexNavigationBar);NavToggle.setAttribute('href','javascript:toggleNavigationBar('+indexNavigationBar+');');var isCollapsed=$(NavFrame).hasClass('collapsed');for(NavChild=NavFrame.firstChild;NavChild!=null&&!isCollapsed;NavChild=NavChild.nextSibling){if($(NavChild).hasClass('NavPic')||$(NavChild).hasClass('NavContent')){if(NavChild.style.display=='none'){isCollapsed=true;}}}if(isCollapsed){for(NavChild=NavFrame.firstChild;NavChild!=null;NavChild=NavChild.nextSibling){if($(NavChild).hasClass('NavPic')||$(NavChild).hasClass('NavContent')){NavChild.style.display='none';}}}var NavToggleText=document.createTextNode(isCollapsed?NavigationBarShow:NavigationBarHide),j;NavToggle.appendChild(NavToggleText);for(j=0;j<NavFrame.childNodes.length;j++){if($(NavFrame.childNodes[j]).hasClass('NavHead')){NavToggle.style.color=NavFrame.childNodes[j].style.color;NavFrame.childNodes[j].appendChild(NavToggle);}}NavFrame.setAttribute('id','NavFrame'+
indexNavigationBar);}}}$(document).ready(createNavigationBarToggleButton);function LinkFA(){if(document.getElementById("p-lang")){var InterwikiLinks=document.getElementById("p-lang").getElementsByTagName("li"),InterwikiLinksLargo=InterwikiLinks.length,i;for(i=0;i<InterwikiLinksLargo;i++){if(document.getElementById(InterwikiLinks[i].className+"-fa")){InterwikiLinks[i].className+=" destacado";InterwikiLinks[i].title="Este es un artículo destacado en esta Wikipedia.";}else if(document.getElementById(InterwikiLinks[i].className+"-ga")){InterwikiLinks[i].className+=" bueno";InterwikiLinks[i].title="Este es un artículo bueno en esta Wikipedia.";}}}}$(document).ready(LinkFA);$(function(){var newSectionLink=$('#ca-addsection a');if(newSectionLink.length){var link=newSectionLink.clone();link.removeAttr('accesskey').attr('title',function(index,oldTitle){return oldTitle.replace(/\s*\[.*\]\s*$/,'');});link.css({"text-transform":"lowercase"});var lastEditsectionLink=$('span.editsection:last a');
lastEditsectionLink.after(link);lastEditsectionLink.after('&#32;·&#32;');}});function iProject(){var elementos=[],i;if(document.getElementsByClassName){elementos=document.getElementsByClassName("interProject");}else{var els=document.getElementsByTagName("span"),elsLen=els.length,j;for(i=0,j=0;i<elsLen;i++){if("interProject"==els[i].className){elementos[j]=els[i];j++;}}}if(elementos.length>0){mw.util.addCSS('#interProject {display: none; speak: none;} #p-tb .pBody {padding-right: 0;}');var portal=document.createElement('div');portal.setAttribute("class","portlet portal");var tit=document.createElement('h3');tit.setAttribute("lang","es");tit.appendChild(document.createTextNode('Otros proyectos'));portal.appendChild(tit);var IPY=document.createElement('div');IPY.setAttribute('class',"pBody body");var ul=document.createElement('ul');IPY.appendChild(ul);for(i=0;i<elementos.length;i++){var li=document.createElement('li');li.innerHTML=elementos[i].innerHTML;ul.appendChild(li);}portal.
appendChild(IPY);if(document.getElementById("p-tb").nextSibling){document.getElementById("p-tb").parentNode.insertBefore(portal,document.getElementById("p-tb").nextSibling);}else{document.getElementById("p-tb").parentNode.appendChild(portal);}}}$(document).ready(iProject);});mw.loader.using('jquery.tablesorter',function(){var ts=$.tablesorter,i,j;ts.formatDigit=function(s){var i=parseFloat(s.replace(/[. ]/g,'').replace(/,/g,'.').replace("\u2212",'-'));return(isNaN(i))?0:i;};ts.formatDigitSI=function(s){var i=parseFloat(s.replace(/[\u00a0\u202f ]/g,'').replace(/,/g,'.').replace("\u2212",'-'));return(isNaN(i))?0:i;};ts.numberRegexCustom=new RegExp("^[-+\u2212]?"+"(?:"+"([0-9]{1,3}[\\.])+?[0-9]{1,3}"+"|"+"[0-9]{1,4}"+")([\\,][0-9]+)?[\\s\\xa0]*%?$","i");ts.numberRegexCustomSI=new RegExp("^[-+\u2212]?"+"(?:"+"([0-9]{1,3}[\u00a0\u202f ])+?[0-9]{1,3}"+"|"+"[0-9]{1,4}"+")([\\.\\,][0-9]+)?[\\s\\xa0]*%?$","i");var regex=[];ts.monthNames=[[],[]];for(i=1;i<13;i++){ts.monthNames[0][i]=mw.config.
get('wgMonthNames')[i].toLowerCase();ts.monthNames[1][i]=mw.config.get('wgMonthNamesShort')[i].toLowerCase().replace('.','');regex.push($.escapeRE(ts.monthNames[0][i]));regex.push($.escapeRE(ts.monthNames[1][i]));}regex=regex.join('|');ts.fechaRegexCustom=[];ts.fechaRegexCustom[0]=new RegExp("^\\d\\d?\\sde\\s("+regex+")\\sde\\s\\d{2,4}$");ts.fechaRegexCustom[1]=new RegExp("^(\\d\\d[\\/\\.\\-]\\d\\d[\\/\\.\\-]\\d\\d\\d\\d|\\d\\d[\\/\\.\\-]\\d\\d[\\/\\.\\-]\\d\\d)$");$.tablesorter.addParser({id:'numberSI',is:function(s,table){return $.tablesorter.numberRegexCustomSI.test($.trim(s));},format:function(s){return $.tablesorter.formatDigitSI(s);},type:'numeric'});$.tablesorter.addParser({id:'numberCustom',is:function(s,table){return $.tablesorter.numberRegexCustom.test($.trim(s));},format:function(s){return $.tablesorter.formatDigit(s);},type:'numeric'});ts.addParser({id:'fecha',is:function(s){return(ts.fechaRegexCustom[0].test(s)||ts.fechaRegexCustom[1].test(s));},format:function(s,table){s=
$.trim(s.toLowerCase());for(i=1,j=0;i<13&&j<2;i++){s=s.replace(ts.monthNames[j][i],i);if(i==12){j++;i=0;}}s=s.replace(/( de |[\-\.\,' ])/g,'/');s=s.replace(/\/\//g,'/');s=s.replace(/\/\//g,'/');s=s.split('/');if(s[0]&&s[0].length==1){s[0]='0'+s[0];}if(s[1]&&s[1].length==1){s[1]='0'+s[1];}var y;if(!s[2]){s[2]=2000;}else if((y=parseInt(s[2],10))<100){if(y<30){s[2]=2000+y;}else{s[2]=1900+y;}}var d=s.shift();s.push(s.shift());s.push(d);return parseInt(s.join(''),10);},type:'numeric'});});(function(){var letras=[["áàâäãāăåą","a"],["æ","ae"],["ćĉčç","c"],["ďḑđð","d"],["éèêëẽěēĕę","e"],["ĝḡğģǥ","g"],["ĥḧḩħ","h"],["íìÎîïĩīĭįı","i"],["ĵ","j"],["ķ","k"],["ĺľļł","l"],["ńňņ","n"],["ñ","n~"],["óòôöõōŏǫőø","o"],["œ","oe"],["ŕřŗ","r"],["śŝšş","s"],["ß","ss"],["ťţŧ","t"],["úùûüũūŭůųű","u"],["ṽ","v"],["ŵẅ","w"],["ẍ","x"],["ýŷÿỹ","y"],["źẑžƶ","z"]];var hash={};for(var i=0;i<letras.length;i++){
var arr=letras[i][0].split("");var dest=letras[i][1];for(var j=0;j<arr.length;j++){hash[arr[j]]=dest;}}mw.config.set('tableSorterCollation',hash);})();})(jQuery,mediaWiki);;mw.loader.state({"site":"ready"});
/* cache key: eswiki:resourceloader:filter:minify-js:7:591e1bba606c5fc0584c0ca6f59e6b77 */