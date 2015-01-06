// Todo: make autodate an option in the CiteTemplate object, not a preference

// Global object
if (typeof CiteTB == 'undefined') {
  var CiteTB = {
    "Templates" : {}, // All templates
    "Options" : {}, // Global options
    "UserOptions" : {}, // User options
    "DefaultOptions" : {}, // Script defaults
    "ErrorChecks" : {} // Error check functions
  };
}

// only load on edit, unless its a user JS/CSS page
if ((wgAction == 'edit' || wgAction == 'submit') && !((wgNamespaceNumber == 2 || wgNamespaceNumber == 4) &&
  (wgPageName.indexOf('.js') != -1 || wgPageName.indexOf('.css') != -1 ))) {

appendCSS(".cite-form-td {"+
"height: 0 !important;"+
"padding: 0.1em !important;"+
"}");  

// Default options, these mainly exist so the script won't break if a new option is added
CiteTB.DefaultOptions = {
  "date format" : "<year>-<zmonth>-<zdate>",
  "autodate fields" : [],
  "months" : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
  "modal" : true,
  "autoparse" : false,
  "expandtemplates": false
};
// Get an option - user settings override global which override defaults
CiteTB.getOption = function(opt) {
  if (CiteTB.UserOptions[opt] != undefined) {
    return CiteTB.UserOptions[opt];
  } else if (CiteTB.Options[opt] != undefined) {
    return CiteTB.Options[opt];
  }
  return CiteTB.DefaultOptions[opt];
}

CiteTB.init = function() {
  /* Main stuff, build the actual toolbar structure
   * 1. get the template list, make the dropdown list and set up the template dialog boxes
   * 2. actually build the toolbar:
   *    * A section for cites
   *    ** dropdown for the templates (previously defined)
   *    ** button for named refs with a dialog box
   *    ** button for errorcheck
   * 3. add the whole thing to the main toolbar
  */

  if (typeof $j('div[rel=cites]')[0] != 'undefined') { // Mystery IE bug workaround
    return;
  }
  $j('head').trigger('reftoolbarbase');
  var $target = $j('#wpTextbox1');
  var temlist = {};
  var d = new Date();
  var start = d.getTime();
  for (var t in CiteTB.Templates) {
  	var tem = CiteTB.Templates[t];
    sform = CiteTB.escStr(tem.shortform);
    var actionobj = { 
      type: 'dialog',
      module: 'cite-dialog-'+sform
    };
    var dialogobj = {};
    dialogobj['cite-dialog-'+sform] = {
      resizeme: false,
      titleMsg: 'cite-dialog-'+sform, 
      id: 'citetoolbar-'+sform,
      init: function() {}, 
      html: tem.getInitial(), 
      dialog: {
        width:675,
        open: function() { 
          $j(this).html(CiteTB.getOpenTemplate().getForm());
          $j('.cite-prev-parse').bind( 'click', CiteTB.prevParseClick);
        },
        beforeclose: function() {
          CiteTB.resetForm();
        },
        buttons: {
          'cite-form-submit': function() {
            $j.wikiEditor.modules.toolbar.fn.doAction( $j(this).data( 'context' ), {
              type: 'encapsulate',
              options: {
                peri: ' '
              }
            }, $j(this) );
            var ref = CiteTB.getRef(false, true);
            $j(this).dialog( 'close' );
            $j.wikiEditor.modules.toolbar.fn.doAction( $j(this).data( 'context' ), {
              type: 'encapsulate',
              options: {
                pre: ref
              }
            }, $j(this) );
          },
          'cite-form-showhide': CiteTB.showHideExtra,
          'cite-refpreview': function() {   
            var ref = CiteTB.getRef(false, false);
            var template = CiteTB.getOpenTemplate();
            var div = $j("#citetoolbar-"+CiteTB.escStr(template.shortform));
            div.find('.cite-preview-label').show();
            div.find('.cite-ref-preview').text(ref).show();
            if (CiteTB.getOption('autoparse')) {
              CiteTB.prevParseClick();
            } else {
              div.find('.cite-prev-parse').show();
              div.find('.cite-prev-parsed-label').hide();
              div.find('.cite-preview-parsed').html('');
            }         
          },
          'wikieditor-toolbar-tool-link-cancel': function() {
            $j(this).dialog( 'close' );
          },
          'cite-form-reset': function() {
            CiteTB.resetForm();
          }
        }
      } 
    };
    $target.wikiEditor('addDialog', dialogobj);
    if (!CiteTB.getOption('modal')) {
      //$j('#citetoolbar-'+sform).dialog('option', 'modal', false);
    }
    temlist[sform] = {label: tem.templatename, action: actionobj };  
  }

  var refsection =  {
    'sections': {
      'cites': { 
        type: 'toolbar', 
        labelMsg: 'cite-section-label',
        groups: { 
          'template': {
            tools: {
              'template': {
                type: 'select',
                labelMsg: 'cite-template-list',
                list: temlist
              } 
            }
          },
          'namedrefs': {
            labelMsg: 'cite-named-refs-label',
            tools: {
              'nrefs': {
                type: 'button',
                action: {
                  type: 'dialog',
                  module: 'cite-toolbar-namedrefs'
                },
                icon: '//upload.wikimedia.org/wikipedia/commons/thumb/b/be/Nuvola_clipboard_lined.svg/22px-Nuvola_clipboard_lined.svg.png',
                section: 'cites',
                group: 'namedrefs',
                labelMsg: 'cite-named-refs-button'
              }
            }
          },
          'errorcheck': {
            labelMsg: 'cite-errorcheck-label',
            tools: {
              'echeck': {
                type: 'button',
                action: {
                  type: 'dialog',
                  module: 'cite-toolbar-errorcheck'           
                },
                icon: '//upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Nuvola_apps_korganizer-NO.png/22px-Nuvola_apps_korganizer-NO.png',
                section: 'cites',
                group: 'errorcheck',
                labelMsg: 'cite-errorcheck-button'
              }
            }
          }
        } 
      } 
    } 
  };
  
  var defaultdialogs = { 
    'cite-toolbar-errorcheck': {
      titleMsg: 'cite-errorcheck-label',
      id: 'citetoolbar-errorcheck',
      resizeme: false,
      init: function() {},
      html: '<div id="cite-namedref-loading">'+
        '<img src="//upload.wikimedia.org/wikipedia/commons/4/42/Loading.gif" />'+
        '&nbsp;'+mw.usability.getMsg('cite-loading')+'</div>',
      dialog: {
        width:550,
        open: function() { 
          CiteTB.loadRefs();
        },
        buttons: {
          'cite-errorcheck-submit': function() {
            var errorchecks = $j("input[name='cite-err-test']:checked");
            var errors = [];
            for (var i=0; i<errorchecks.length; i++) {
              errors = errors.concat(CiteTB.ErrorChecks[$j(errorchecks[i]).val()].run());
            }
            CiteTB.displayErrors(errors);
            $j(this).dialog( 'close' );                      
          },
          'wikieditor-toolbar-tool-link-cancel': function() {
            $j(this).dialog( 'close' );
          }
        }
      }
    },
    'cite-toolbar-namedrefs': {
      titleMsg: 'cite-named-refs-title',
      resizeme: false,
      id: 'citetoolbar-namedrefs',
      html: '<div id="cite-namedref-loading">'+
        '<img src="//upload.wikimedia.org/wikipedia/commons/4/42/Loading.gif" />'+
        '&nbsp;'+mw.usability.getMsg('cite-loading')+'</div>',
      init: function() {},
      dialog: {
        width: 550,
        open: function() { 
          CiteTB.loadRefs();
        },
        buttons: {
          'cite-form-submit': function() {
            var refname = $j("#cite-namedref-select").val();
            if (refname == '') {
              return;
            }
            $j.wikiEditor.modules.toolbar.fn.doAction( $j(this).data( 'context' ), {
              type: 'encapsulate',
              options: {
                peri: ' '
              }
            }, $j(this) );
            $j(this).dialog( 'close' );
            $j.wikiEditor.modules.toolbar.fn.doAction( $j(this).data( 'context' ), {
              type: 'encapsulate',
              options: {
                pre: CiteTB.getNamedRef(refname, true)
              }
            }, $j(this) );
          },
          'wikieditor-toolbar-tool-link-cancel': function() {
            $j(this).dialog( 'close' );
          }                   
        }
      }      
    }
  };
  
  $target.wikiEditor('addDialog', defaultdialogs);
  $j('#citetoolbar-namedrefs').unbind('dialogopen');
  if (!CiteTB.getOption('modal')) {
    //$j('#citetoolbar-namedrefs').dialog('option', 'modal', false);
    //$j('#citetoolbar-errorcheck').dialog('option', 'modal', false);
    appendCSS(".ui-widget-overlay {"+
    "display:none !important;"+
    "}");  
  }
  $target.wikiEditor('addToToolbar', refsection);
} 

// Load local data - messages, cite templates, etc.
$j(document).ready( function() {
  switch( wgUserLanguage ) {
    case 'es': // Spanish
      var RefToolbarMessages = importScript('MediaWiki:RefToolbarMessages-es.js');
      break;
    default: // English
      var RefToolbarMessages = importScript('MediaWiki:RefToolbarMessages-en.js');
  }
});

// Setup the main object
CiteTB.mainRefList = [];
CiteTB.refsLoaded = false;

// REF FUNCTIONS
// Actually assemble a ref from user input
CiteTB.getRef = function(inneronly, forinsert) {
  var template = CiteTB.getOpenTemplate();
  var templatename = template.templatename;
  var res = '';
  var refobj = {'shorttag':false};
  if (!inneronly) {
    var group = $j('#cite-'+CiteTB.escStr(template.shortform)+'-group').val();
    var refname = $j('#cite-'+CiteTB.escStr(template.shortform)+'-name').val();
    res += '<ref';
    if (refname) {
      refname = $j.trim(refname);
      res+=' name='+CiteTB.getQuotedString(refname);
      refobj.refname = refname;
    }
    if (group) {
      group = $j.trim(group);
      res+=' group='+CiteTB.getQuotedString(group);
      refobj.refgroup = group;
    }
    res+='>';
  }
  var content ='{{'+templatename;
  for( var i=0; i<template.basic.length; i++ ) {
    var fieldname = template.basic[i].field;
    var field = $j('#cite-'+CiteTB.escStr(template.shortform)+'-'+fieldname).val();
    if (field) {
      content+='|'+fieldname+'=';
      content+= $j.trim(field.replace("|", "{{!}}"));
    }
  }
  if ($j('#cite-form-status').val() != 'closed') {
    for( var i=0; i<template.extra.length; i++ ) {
      var fieldname = template.extra[i].field;
      var field = $j('#cite-'+CiteTB.escStr(template.shortform)+'-'+fieldname).val();
      if (field) {
        content+='|'+fieldname+'=';
        content+= $j.trim(field.replace("|", "{{!}}"));
      }
    }
  }
  content+= '}}';
  res+=content;
  refobj.content = content;
  if (!inneronly) {
    res+= '</ref>';
  }
  if (forinsert) {
    CiteTB.mainRefList.push(refobj);
  }
  return res;
}

// Make a reference to a named ref
CiteTB.getNamedRef = function(refname, forinsert) {
  var inner = 'name=';
  if (forinsert) {
    CiteTB.mainRefList.push( {'shorttag':true, 'refname':refname} );
  }
  return '<ref name='+CiteTB.getQuotedString(refname)+' />';  
}

// Function to load the ref list
CiteTB.loadRefs = function() {
  if (CiteTB.refsLoaded) {
    return;
  }
  CiteTB.getPageText(CiteTB.loadRefsInternal);
}

// Function that actually loads the list from the page text
CiteTB.loadRefsInternal = function(text) { 
  // What this does:             extract first name/group                                     extract second name/group                                          shorttag   inner content
  var refsregex = /< *ref(?: +(name|group) *= *(?:"([^"]*?)"|'([^']*?)'|([^ '"\/\>]*?)) *)? *(?: +(name|group) *= *(?:"([^"]*?)"|'([^']*?)'|([^ '"\/\>]*?)) *)? *(?:\/ *>|>((?:.|\n)*?)< *\/ *ref *>)/gim
  // This should work regardless of the quoting used for names/groups and for linebreaks in the inner content  
  while (true) {
    var ref = refsregex.exec(text);
    if (ref == null) {
      break;
    }
    var refobj = {};
    if (ref[9]) { // Content + short tag check
      //alert('"'+ref[9]+'"');
      refobj['content'] = ref[9]; 
      refobj['shorttag'] = false;
    } else {
      refobj['shorttag'] = true;
    }
    if (ref[1] != '') { // First name/group
      if (ref[2]) {
        refobj['ref'+ref[1]] = ref[2];
      } else if (ref[3]) {
        refobj['ref'+ref[1]] = ref[3];
      } else {
        refobj['ref'+ref[1]] = ref[4];
      }
    }
    if (ref[5] != '') { // Second name/group
      if (ref[6]) {
        refobj['ref'+ref[5]] = ref[6];
      } else if (ref[7]) {
        refobj['ref'+ref[5]] = ref[7];
      } else {
        refobj['ref'+ref[5]] = ref[8];
      }
    }
    CiteTB.mainRefList.push(refobj);
  }
  CiteTB.refsLoaded = true;
  CiteTB.setupErrorCheck();
  CiteTB.setupNamedRefs()
}

// AJAX FUNCTIONS
// Parse some wikitext and hand it off to a callback function
CiteTB.parse = function(text, callback) {
  $j.post( wgServer+wgScriptPath+'/api.php',
    {action:'parse', title:wgPageName, text:text, prop:'text', format:'json'},
    function(data) {
      var html = data['parse']['text']['*'];
      callback(html);
    },
    'json'
  );  
}

// Use the API to expand templates on some text
CiteTB.expandtemplates = function(text, callback) {
  $j.post( wgServer+wgScriptPath+'/api.php',
    {action:'expandtemplates', title:wgPageName, text:text, format:'json'},
    function(data) {
      var restext = data['expandtemplates']['*'];
      callback(restext);
    },
    'json'
  );
}

// Function to get the page text
CiteTB.getPageText = function(callback) {
  var section = $j("input[name='wpSection']").val();
  if ( section != '' ) {
    var postdata = {action:'query', prop:'revisions', rvprop:'content', pageids:wgArticleId, format:'json'};
    if (CiteTB.getOption('expandtemplates')) {
      postdata['rvexpandtemplates'] = '1';
    }
    $j.get( wgServer+wgScriptPath+'/api.php',
      postdata,
      function(data) {
        var pagetext = data['query']['pages'][wgArticleId.toString()]['revisions'][0]['*'];
        callback(pagetext);
      },
      'json'
    );
  } else {
    if (CiteTB.getOption('expandtemplates')) {
      CiteTB.expandtemplates($j('#wpTextbox1').wikiEditor('getContents').text(), callback);
    } else {
      callback($j('#wpTextbox1').wikiEditor('getContents').text());
    }
  }
}

// Autofill a template from an ID (ISBN, DOI, PMID)
CiteTB.initAutofill = function() {
  var elemid = $j(this).attr('id');
  var res = /^cite\-auto\-(.*?)\-(.*)\-(.*)$/.exec(elemid);
  var tem = res[1];
  var field = res[2];
  var autotype = res[3];
  var id = $j('#cite-'+tem+'-'+field).val();
  if (!id) {
    return false;
  }
  var url = 'http://toolserver.org/~alexz/ref/lookup.php?';
  url+=autotype+'='+encodeURIComponent(id);
  url+='&template='+encodeURIComponent(tem);
  var s = document.createElement('script');
  s.setAttribute('src', url);
  s.setAttribute('type', 'text/javascript');
  document.getElementsByTagName('head')[0].appendChild(s);
  return false;
}

// Callback for autofill
//TODO: Autofill the URL, at least for DOI
CiteTB.autoFill = function(data, template, type) {
  var cl = 'cite-'+template+'-';
  $j('.'+cl+'title').val(data.title);
  if ($j('.'+cl+'last1').length != 0) {
    for(var i=0; i<data.authors.length; i++) {
	  if ($j('.'+cl+'last'+(i+1)).length) {
	     $j('.'+cl+'last'+(i+1)).val(data.authors[i][0]);
		 $j('.'+cl+'first'+(i+1)).val(data.authors[i][1]);
	  } else {
	    var coauthors = [];
	    for(var j=i; j<data.authors.length; j++) {
		  coauthors.push(data.authors[j].join(', '));
		}
		$j('.'+cl+'coauthors').val(coauthors.join(', '));
		break;
	  }
	}
  } else if($j('.'+cl+'author1').length != 0) {
    for(var i=0; i<data.authors.length; i++) {
	  if ($j('.'+cl+'author'+(i+1)).length) {
	     $j('.'+cl+'author'+(i+1)).val(data.authors[i].join(', '));
	  } else {
	    var coauthors = [];
	    for(var j=i; j<data.authors.length; j++) {
		  coauthors.push(data.authors[j].join(', '));
		}
		$j('.'+cl+'coauthors').val(coauthors.join(', '));
		break;
	  }
	}
  } else {
    var authors = [];
	for(var i=0; i<data.authors.length; i++) {
	  authors.push(data.authors[i].join(', '));
	}
	$j('.'+cl+'authors').val(authors.join('; '));
  }  
  if (type == 'pmid' || type == 'doi') {
    if (type == 'doi') {
      var DT = new Date(data.date);
      $j('.'+cl+'date').val(CiteTB.formatDate(DT));
    } else {
      $j('.'+cl+'date').val(data.date);
    }
    $j('.'+cl+'journal').val(data.journal);
    $j('.'+cl+'volume').val(data.volume);
    $j('.'+cl+'issue').val(data.issue);
    $j('.'+cl+'pages').val(data.pages);
  } else if (type == 'isbn') {
    $j('.'+cl+'publisher').val(data.publisher);
    $j('.'+cl+'location').val(data.location);
    $j('.'+cl+'year').val(data.year);
    $j('.'+cl+'edition').val(data.edition);
  }
}

// FORM DIALOG FUNCTIONS
// fill the accessdate param with the current date
CiteTB.fillAccessdate = function() {
  var elemid = $j(this).attr('id');
  var res = /^cite\-date\-(.*?)\-(.*)$/.exec(elemid);
  var id = res[1];
  var field = res[2];
  var DT = new Date();
  datestr = CiteTB.formatDate(DT);
  $j('#cite-'+id+'-'+field).val(datestr);
  return false;
}

CiteTB.formatDate = function(DT) {
  var datestr = CiteTB.getOption('date format');
  var zmonth = '';
  var month = DT.getUTCMonth()+1;
  if (month < 10) {
    zmonth = "0"+month.toString();
  } else {
    zmonth = month.toString();
  }
  month = month.toString();
  var zdate = '';
  var date = DT.getUTCDate();
  if (date < 10) {
    zdate = "0"+date.toString();
  } else {
    zdate = date.toString();
  }
  date = date.toString()
  datestr = datestr.replace('<date>', date);
  datestr = datestr.replace('<month>', month);
  datestr = datestr.replace('<zdate>', zdate);
  datestr = datestr.replace('<zmonth>', zmonth);
  datestr = datestr.replace('<monthname>', CiteTB.getOption('months')[DT.getUTCMonth()]);
  datestr = datestr.replace('<year>', DT.getUTCFullYear().toString());
  return datestr;
}

// Function called after the ref list is loaded, to actually set the contents of the named ref dialog
// Until the list is loaded, its just a "Loading" placeholder
CiteTB.setupNamedRefs = function() {
  var names = []
  for( var i=0; i<CiteTB.mainRefList.length; i++) {
    if (!CiteTB.mainRefList[i].shorttag && CiteTB.mainRefList[i].refname) {
      names.push(CiteTB.mainRefList[i]);
    }
  }
  var stuff = $j('<div />')
  $j('#citetoolbar-namedrefs').html( stuff );
  if (names.length == 0) {
    stuff.html(mw.usability.getMsg('cite-no-namedrefs'));
  } else {
    stuff.html(mw.usability.getMsg('cite-namedrefs-intro'));
    var select = $j('<select id="cite-namedref-select">');
    select.append($j('<option value="" />').text(mw.usability.getMsg('cite-named-refs-dropdown')));
    for(var i=0; i<names.length; i++) {
      select.append($j('<option />').text(names[i].refname));
    }
    stuff.after(select);
    select.before('<br />');      
    var prevlabel = $j('<div id="cite-nref-preview-label" style="display:none;" />').html(mw.usability.getMsg('cite-raw-preview'));
    select.after(prevlabel);
    prevlabel.before("<br /><br />");
    prevlabel.after('<div id="cite-namedref-preview" style="padding:0.5em; font-size:110%" />');
    var parselabel = $j('<span id="cite-parsed-label" style="display:none;" />').html(mw.usability.getMsg('cite-parsed-label'));
    $j('#cite-namedref-preview').after(parselabel);
    parselabel.after('<div id="cite-namedref-parsed" style="padding-bottom:0.5em; font-size:110%" />');
    var link = $j('<a href="#" id="cite-nref-parse" style="margin:0 1em 0 1em; display:none; color:darkblue" />');
    link.html(mw.usability.getMsg('cite-form-parse'));
    $j('#cite-namedref-parsed').after(link);
    
    $j("#cite-namedref-select").bind( 'change', CiteTB.namedRefSelectClick);
    $j('#cite-nref-parse').bind( 'click', CiteTB.nrefParseClick);
  }      
}

// Function to get the errorcheck form HTML
CiteTB.setupErrorCheck = function() {
  var form = $j('<div id="cite-errorcheck-heading" />').html(mw.usability.getMsg('cite-errorcheck-heading'));
  var ul = $j("<ul id='cite-errcheck-list' />");
  for (var t in CiteTB.ErrorChecks) {
    test = CiteTB.ErrorChecks[t];
    ul.append(test.getRow());
  }
  form.append(ul);
  $j('#citetoolbar-errorcheck').html(form);
}

// Callback function for parsed preview
CiteTB.fillNrefPreview = function(parsed) {
  $j('#cite-parsed-label').show();
  $j('#cite-namedref-parsed').html(parsed);
}

// Click handler for the named-ref parsed preview
CiteTB.nrefParseClick = function() {
  var choice = $j("#cite-namedref-select").val();
  if (choice == '') {
    $j('#cite-parsed-label').hide();
    $j('#cite-namedref-parsed').text('');
    return false;
  }
  $j('#cite-nref-parse').hide();
  for( var i=0; i<CiteTB.mainRefList.length; i++) {
    if (!CiteTB.mainRefList[i].shorttag && CiteTB.mainRefList[i].refname == choice) {
      CiteTB.parse(CiteTB.mainRefList[i].content, CiteTB.fillNrefPreview);
      return false;
    }
  }  
}

// Click handler for the named-ref dropdown
CiteTB.lastnamedrefchoice = '';
CiteTB.namedRefSelectClick = function() {
  var choice = $j("#cite-namedref-select").val();
  if (CiteTB.lastnamedrefchoice == choice) {
    return;
  }
  CiteTB.lastnamedrefchoice = choice;
  $j('#cite-parsed-label').hide();
  $j('#cite-namedref-parsed').text('');
  if (choice == '') {
    $j('#cite-nref-preview-label').hide();
    $j('#cite-namedref-preview').text('');
    $j('#cite-nref-parse').hide();
    return;
  }
  for( var i=0; i<CiteTB.mainRefList.length; i++) {
    if (!CiteTB.mainRefList[i].shorttag && CiteTB.mainRefList[i].refname == choice) {
      $j('#cite-nref-preview-label').show();
      $j('#cite-namedref-preview').text(CiteTB.mainRefList[i].content);
      if (CiteTB.getOption('autoparse')) {
        CiteTB.nrefParseClick();
      } else {
        $j('#cite-nref-parse').show();
      }
    }
  }
}

// callback function for parsed preview
CiteTB.fillTemplatePreview = function(text) {
  var template = CiteTB.getOpenTemplate();
  var div = $j("#citetoolbar-"+CiteTB.escStr(template.shortform));
  div.find('.cite-prev-parsed-label').show();
  div.find('.cite-preview-parsed').html(text);
}

// Click handler for template parsed preview
CiteTB.prevParseClick = function() {
  var ref = CiteTB.getRef(true, false);
  var template = CiteTB.getOpenTemplate();
  var div = $j("#citetoolbar-"+CiteTB.escStr(template.shortform));
  div.find('.cite-prev-parse').hide();
  CiteTB.parse(ref, CiteTB.fillTemplatePreview);
}

// Show/hide the extra fields in the dialog box
CiteTB.showHideExtra = function() {
  var template = CiteTB.getOpenTemplate();
  var div = $j("#citetoolbar-"+CiteTB.escStr(template.shortform));
  var setting = div.find(".cite-form-status").val();
  if ( setting == 'closed' ) {
    div.find(".cite-form-status").val('open');
    div.find('.cite-extra-fields').show(1, function() {
      // jQuery adds "display:block", which screws things up
      div.find('.cite-extra-fields').attr('style', 'width:100%; background-color:transparent;'); 
    });
  } else {
    div.find(".cite-form-status").val('closed')
    div.find('.cite-extra-fields').hide();
  } 
}

// Resets form fields and previews
CiteTB.resetForm = function() {
  var template = CiteTB.getOpenTemplate();
  var div = $j("#citetoolbar-"+CiteTB.escStr(template.shortform));
  div.find('.cite-preview-label').hide();
  div.find('.cite-ref-preview').text('').hide();
  div.find('.cite-prev-parsed-label').hide();
  div.find('.cite-preview-parsed').html('');
  div.find('.cite-prev-parse').hide();
  var id = CiteTB.escStr(template.shortform);
  $j('#citetoolbar-'+id+' input[type=text]').val('');
}

// STRING UTILITY FUNCTIONS
// Returns a string quoted as necessary for name/group attributes
CiteTB.getQuotedString = function(s) {
  var sp = /\s/.test(s); // spaces
  var sq = /\'/.test(s); // single quotes
  var dq = /\"/.test(s); // double quotes
  if (!sp && !sq && !dq) { // No quotes necessary
    return s;
  } else if (!dq) { // Can use double quotes
    return '"'+s+'"';
  } else if (!sq) { // Can use single quotes
    return "'"+s+"'";
  } else { // Has double and single quotes
    s = s.replace(/\"/g, '\"');
    return '"'+s+'"';
  }
} 
// Fix up strings for output - capitalize first char, replace underscores with spaces
CiteTB.fixStr = function(s) {
  s = s.slice(0,1).toUpperCase() + s.slice(1);
  s = s.replace('_',' ');
  return s;
}
// Escape spaces and quotes for use in HTML classes/ids
CiteTB.escStr = function(s) {
  return s.replace(' ', '-').replace("'", "\'").replace('"', '\"');
}

// MISC FUNCTIONS
// Determine which template form is open, and get the template object for it
CiteTB.getOpenTemplate = function() {
  var dialogs = $j(".ui-dialog-content.ui-widget-content:visible");
  var templatename = $j(dialogs[0]).find(".cite-template").val();
  var template = null;
  return CiteTB.Templates[templatename];
}

// Display the report for the error checks
CiteTB.displayErrors = function(errors) {
  $j('#cite-err-report').remove();
  var table = $j('<table id="cite-err-report" style="width:100%; border:1px solid #A9A9A9; background-color:#FFEFD5; padding:0.25em; margin-top:0.5em" />');
  $j('#editpage-copywarn').before(table);
  var tr1 = $j('<tr style="width:100%" />');
  var th1 = $j('<th style="width:60%; font-size:110%" />').html(mw.usability.getMsg('cite-err-report-heading'));
  var th2 = $j('<th style="text-align:right; width:40%" />');
  im = $j('<img />').attr('src', '//upload.wikimedia.org/wikipedia/commons/thumb/5/55/Gtk-stop.svg/20px-Gtk-stop.svg.png');
  im.attr('alt', mw.usability.getMsg('cite-err-report-close')).attr('title', mw.usability.getMsg('cite-err-report-close'));
  var ad = $j('<a id="cite-err-check-close" />').attr('href', '#');
  ad.append(im);
  th2.append(ad);
  tr1.append(th1).append(th2);
  table.append(tr1);
  $j('#cite-err-check-close').bind('click', function() {  $j('#cite-err-report').remove(); });
  if (errors.length == 0) {
    var tr = $j('<tr style="width:100%;" />');
    var td = $j('<td style="text-align:center; margin:1.5px;" />').html(mw.usability.getMsg('cite-err-report-empty'));
    tr.append(td);
    table.append(tr);
    
    return;
  }
  for(var e in errors) {
    var err = errors[e];
    var tr = $j('<tr style="width:100%;" />');
    var td1 = $j('<td style="border: 1px solid black; margin:1.5px; width:60%" />').html(err.err);
    var td2 = $j('<td style="border: 1px solid black; margin:1.5px; width:40%" />').html(mw.usability.getMsg(err.msg));
    tr.append(td1).append(td2);
    table.append(tr);
  }
}
   
} // End of code loaded only on edit