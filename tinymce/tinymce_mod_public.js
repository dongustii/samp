tinyMCE.init({
		theme : "advanced",
		editor_selector: "teljes_leiras",
		
		content_css: "my_content.css",
		
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect", 
		theme_advanced_buttons2 : "cut,copy,paste,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,preview,|,forecolor,backcolor", 
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,advhr,|,visualchars,nonbreaking,blockquote", 
		
		theme_advanced_toolbar_location : "top", 
		theme_advanced_toolbar_align : "left", 
		theme_advanced_statusbar_location : "bottom", 
		theme_advanced_resizing : false, 
		
		mode : "textareas",
		plugins : "safari,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,imagemanager,filemanager",
		theme_advanced_buttons3_add : "fullpage"
		
		});