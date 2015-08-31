tinyMCE.importThemeLanguagePack('dolphinet');var TinyMCE_DolphinetTheme = {
	_autoImportCSSClasses : true,
	_resizer : {},
	_buttons : [
		['bold', '{$lang_bold_img}', 'lang_bold_desc', 'Bold'],
		['italic', '{$lang_italic_img}', 'lang_italic_desc', 'Italic'],
		['underline', '{$lang_underline_img}', 'lang_underline_desc', 'Underline'],
		['strikethrough', 'strikethrough.gif', 'lang_striketrough_desc', 'Strikethrough'],
		['justifyleft', 'justifyleft.gif', 'lang_justifyleft_desc', 'JustifyLeft'],
		['justifycenter', 'justifycenter.gif', 'lang_justifycenter_desc', 'JustifyCenter'],
		['justifyright', 'justifyright.gif', 'lang_justifyright_desc', 'JustifyRight'],
		['justifyfull', 'justifyfull.gif', 'lang_justifyfull_desc', 'JustifyFull'],
		['bullist', 'bullist.gif', 'lang_bullist_desc', 'InsertUnorderedList'],
		['numlist', 'numlist.gif', 'lang_numlist_desc', 'InsertOrderedList'],
		['undo', 'undo.gif', 'lang_undo_desc', 'Undo'],
		['redo', 'redo.gif', 'lang_redo_desc', 'Redo'],
		['link', 'link.gif', 'lang_link_desc', 'mceLink', true],
		['unlink', 'unlink.gif', 'lang_unlink_desc', 'unlink'],
		['cleanup', 'cleanup.gif', 'lang_cleanup_desc', 'mceCleanup'],
		['removeformat', 'removeformat.gif', 'lang_theme_removeformat_desc', 'removeformat']
	],

	_buttonMap : 'bold,italic,underline,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink,cleanup,removeformat',

	getControlHTML : function(button_name) {
		var i, x;

		// Lookup button in button list
		for (i=0; i<TinyMCE_DolphinetTheme._buttons.length; i++) {
			var but = TinyMCE_DolphinetTheme._buttons[i];

			if (but[0] == button_name)
				return tinyMCE.getButtonHTML(but[0], but[2], '{$themeurl}/images/' + but[1], but[3], (but.length > 4 ? but[4] : false), (but.length > 5 ? but[5] : null));
		}

		// Custom controlls other than buttons
		switch (button_name) {
			case "|":
			case "separator":
				return '<img src="{$themeurl}/images/separator.gif" width="2" height="20" class="mceSeparatorLine" />';

			case "spacer":
				return '<img src="{$themeurl}/images/separator.gif" width="2" height="15" border="0" class="mceSeparatorLine" style="vertical-align: middle" />';

			case "rowseparator":
				return '<br />';
		}

		return "";
	},

	/**
	 * Theme specific execcommand handling.
	 */
	execCommand : function(editor_id, element, command, user_interface, value) {
		switch (command) {
			case "mceLink":
				var inst = tinyMCE.getInstanceById(editor_id);
				var doc = inst.getDoc();
				var selectedText = "";

				if (tinyMCE.isMSIE) {
					var rng = doc.selection.createRange();
					selectedText = rng.text;
				} else
					selectedText = inst.getSel().toString();

				if (!tinyMCE.linkElement) {
					if ((tinyMCE.selectedElement.nodeName.toLowerCase() != "img") && (selectedText.length <= 0))
						return true;
				}

				var href = "", target = "", title = "", onclick = "", action = "insert", style_class = "";

				if (tinyMCE.selectedElement.nodeName.toLowerCase() == "a")
					tinyMCE.linkElement = tinyMCE.selectedElement;

				// Is anchor not a link
				if (tinyMCE.linkElement != null && tinyMCE.getAttrib(tinyMCE.linkElement, 'href') == "")
					tinyMCE.linkElement = null;

				if (tinyMCE.linkElement) {
					href = tinyMCE.getAttrib(tinyMCE.linkElement, 'href');
					target = tinyMCE.getAttrib(tinyMCE.linkElement, 'target');
					title = tinyMCE.getAttrib(tinyMCE.linkElement, 'title');
					onclick = tinyMCE.getAttrib(tinyMCE.linkElement, 'onclick');
					style_class = tinyMCE.getAttrib(tinyMCE.linkElement, 'class');

					// Try old onclick to if copy/pasted content
					if (onclick == "")
						onclick = tinyMCE.getAttrib(tinyMCE.linkElement, 'onclick');

					onclick = tinyMCE.cleanupEventStr(onclick);

					href = eval(tinyMCE.settings['urlconverter_callback'] + "(href, tinyMCE.linkElement, true);");

					// Use mce_href if defined
					mceRealHref = tinyMCE.getAttrib(tinyMCE.linkElement, 'mce_href');
					if (mceRealHref != "") {
						href = mceRealHref;

						if (tinyMCE.getParam('convert_urls'))
							href = eval(tinyMCE.settings['urlconverter_callback'] + "(href, tinyMCE.linkElement, true);");
					}

					action = "update";
				}

				var template = new Array();

				template['file'] = 'link.htm';
				template['width'] = 310;
				template['height'] = 200;

				// Language specific width and height addons
				template['width'] += tinyMCE.getLang('lang_insert_link_delta_width', 0);
				template['height'] += tinyMCE.getLang('lang_insert_link_delta_height', 0);

				if (inst.settings['insertlink_callback']) {
					var returnVal = eval(inst.settings['insertlink_callback'] + "(href, target, title, onclick, action, style_class);");
					if (returnVal && returnVal['href'])
						TinyMCE_DolphinetTheme._insertLink(returnVal['href'], returnVal['target'], returnVal['title'], returnVal['onclick'], returnVal['style_class']);
				} else {
					tinyMCE.openWindow(template, {href : href, target : target, title : title, onclick : onclick, action : action, className : style_class, inline : "yes"});
				}

				return true;

		}

		return false;
	},

	/**
	 * Editor instance template function.
	 */
	getEditorTemplate : function(settings, editorId) {
		function removeFromArray(in_array, remove_array) {
			var outArray = new Array();
			
			for (var i=0; i<in_array.length; i++) {
				skip = false;

				for (var j=0; j<remove_array.length; j++) {
					if (in_array[i] == remove_array[j]) {
						skip = true;
					}
				}

				if (!skip) {
					outArray[outArray.length] = in_array[i];
				}
			}

			return outArray;
		}

		function addToArray(in_array, add_array) {
			for (var i=0; i<add_array.length; i++) {
				in_array[in_array.length] = add_array[i];
			}

			return in_array;
		}

		var template = new Array();
		var deltaHeight = 0;
		var resizing = tinyMCE.getParam("theme_dolphinet_resizing", false);
		var path = tinyMCE.getParam("theme_dolphinet_path", true);
		var statusbarHTML = '<div id="{$editor_id}_path" class="mceStatusbarPathText" style="display: ' + (path ? "block" : "none") + '">&#160;</div><div id="{$editor_id}_resize" class="mceStatusbarResize" style="display: ' + (resizing ? "block" : "none") + '" onmousedown="tinyMCE.themes.advanced._setResizing(event,\'{$editor_id}\',true);"></div><br style="clear: both" />';
		var layoutManager = tinyMCE.getParam("theme_dolphinet_layout_manager", "SimpleLayout");

		// Setup style select options -- MOVED UP FOR EXTERNAL TOOLBAR COMPATABILITY!
		var styleSelectHTML = '<option value="">{$lang_theme_style_select}</option>';
		if (settings['theme_dolphinet_styles']) {
			var stylesAr = settings['theme_dolphinet_styles'].split(';');
			
			for (var i=0; i<stylesAr.length; i++) {
				var key, value;

				key = stylesAr[i].split('=')[0];
				value = stylesAr[i].split('=')[1];

				styleSelectHTML += '<option value="' + value + '">' + key + '</option>';
			}

			TinyMCE_DolphinetTheme._autoImportCSSClasses = false;
		}

		switch(layoutManager) {
			case "SimpleLayout" : //the default TinyMCE Layout (for backwards compatibility)...
				var toolbarHTML = "";
				var toolbarLocation = tinyMCE.getParam("theme_dolphinet_toolbar_location", "bottom");
				var toolbarAlign = tinyMCE.getParam("theme_dolphinet_toolbar_align", "center");
				var pathLocation = tinyMCE.getParam("theme_dolphinet_path_location", "none"); // Compatiblity
				var statusbarLocation = tinyMCE.getParam("theme_dolphinet_statusbar_location", pathLocation);
				var defVals = {
					theme_dolphinet_buttons1 : "bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,bullist,numlist,separator,undo,redo,separator,link,unlink,cleanup,removeformat,pasteword"
				};

				// Add accessibility control
				toolbarHTML += '<a href="#" accesskey="q" title="' + tinyMCE.getLang("lang_toolbar_focus") + '"';

				if (!tinyMCE.getParam("accessibility_focus"))
					toolbarHTML += ' onfocus="tinyMCE.getInstanceById(\'' + editorId + '\').getWin().focus();"';

				toolbarHTML += '></a>';

				// Render rows
				for (var i=1; i<100; i++) {
					var def = defVals["theme_dolphinet_buttons" + i];

					var buttons = tinyMCE.getParam("theme_dolphinet_buttons" + i, def == null ? '' : def, true, ',');
					if (buttons.length == 0)
						break;

					buttons = removeFromArray(buttons, tinyMCE.getParam("theme_dolphinet_disable", "", true, ','));
					buttons = addToArray(buttons, tinyMCE.getParam("theme_dolphinet_buttons" + i + "_add", "", true, ','));
					buttons = addToArray(tinyMCE.getParam("theme_dolphinet_buttons" + i + "_add_before", "", true, ','), buttons);

					for (var b=0; b<buttons.length; b++)
						toolbarHTML += tinyMCE.getControlHTML(buttons[b]);

					if (buttons.length > 0) {
						toolbarHTML += "<br />";
						deltaHeight -= 23;
					}
				}

				// Add accessibility control
				toolbarHTML += '<a href="#" accesskey="z" onfocus="tinyMCE.getInstanceById(\'' + editorId + '\').getWin().focus();"></a>';

				// Setup template html
				template['html'] = '<table class="mceEditor" border="0" cellpadding="0" cellspacing="0" width="{$width}" height="{$height}" style="width:{$width}px;height:{$height}px"><tbody>';

				if (toolbarLocation == "top") {
					template['html'] += '<tr><td class="mceToolbarTop" align="' + toolbarAlign + '" height="1" nowrap="nowrap">' + toolbarHTML + '</td></tr>';
				}

				if (statusbarLocation == "top") {
					template['html'] += '<tr><td class="mceStatusbarTop" height="1">' + statusbarHTML + '</td></tr>';
					deltaHeight -= 23;
				}

				template['html'] += '<tr><td align="center"><span id="{$editor_id}"></span></td></tr>';

				if (toolbarLocation == "bottom") {
					template['html'] += '<tr><td class="mceToolbarBottom" align="' + toolbarAlign + '" height="1">' + toolbarHTML + '</td></tr>';
				}

				// External toolbar changes
				if (toolbarLocation == "external") {
					var bod = document.body;
					var elm = document.createElement ("div");

					toolbarHTML = tinyMCE.replaceVar(toolbarHTML, 'style_select_options', styleSelectHTML);
					toolbarHTML = tinyMCE.applyTemplate(toolbarHTML, {editor_id : editorId});

					elm.className = "mceToolbarExternal";
					elm.id = editorId+"_toolbar";
					elm.innerHTML = '<table width="100%" border="0" align="center"><tr><td align="center">'+toolbarHTML+'</td></tr></table>';
					bod.appendChild (elm);
					// bod.style.marginTop = elm.offsetHeight + "px";

					deltaHeight = 0;
					tinyMCE.getInstanceById(editorId).toolbarElement = elm;

					//template['html'] = '<div id="mceExternalToolbar" align="center" class="mceToolbarExternal"><table width="100%" border="0" align="center"><tr><td align="center">'+toolbarHTML+'</td></tr></table></div>' + template["html"];
				} else {
					tinyMCE.getInstanceById(editorId).toolbarElement = null;
				}

				if (statusbarLocation == "bottom") {
					template['html'] += '<tr><td class="mceStatusbarBottom" height="1">' + statusbarHTML + '</td></tr>';
					deltaHeight -= 23;
				}

				template['html'] += '</tbody></table>';
				//"SimpleLayout"
			break;

			case "RowLayout" : //Container Layout - containers defined in "theme_dolphinet_containers" are rendered from top to bottom.
				template['html'] = '<table class="mceEditor" border="0" cellpadding="0" cellspacing="0" width="{$width}" height="{$height}" style="width:{$width}px;height:{$height}px"><tbody>';

				var containers = tinyMCE.getParam("theme_dolphinet_containers", "", true, ",");
				var defaultContainerCSS = tinyMCE.getParam("theme_dolphinet_containers_default_class", "container");
				var defaultContainerAlign = tinyMCE.getParam("theme_dolphinet_containers_default_align", "center");

				//Render Containers:
				for (var i = 0; i < containers.length; i++)
				{
					if (containers[i] == "mceEditor") //Exceptions for mceEditor and ...
						template['html'] += '<tr><td align="center" class="mceEditor_border"><span id="{$editor_id}"></span></td></tr>';
					else if (containers[i] == "mceElementpath" || containers[i] == "mceStatusbar") // ... mceElementpath:
					{
						var pathClass = "mceStatusbar";

						if (i == containers.length-1)
						{
							pathClass = "mceStatusbarBottom";
						}
						else if (i == 0)
						{
							pathClass = "mceStatusbar";
						}
						else
						{
							deltaHeight-=2;
						}

						template['html'] += '<tr><td class="' + pathClass + '" height="1">' + statusbarHTML + '</td></tr>';
						deltaHeight -= 22;
					} else { // Render normal Container
						var curContainer = tinyMCE.getParam("theme_dolphinet_container_"+containers[i], "", true, ',');
						var curContainerHTML = "";
						var curAlign = tinyMCE.getParam("theme_dolphinet_container_"+containers[i]+"_align", defaultContainerAlign);
						var curCSS = tinyMCE.getParam("theme_dolphinet_container_"+containers[i]+"_class", defaultContainerCSS);

						for (var j=0; j<curContainer.length; j++) {
							curContainerHTML += tinyMCE.getControlHTML(curContainer[j]);
						}

						if (curContainer.length > 0) {
							curContainerHTML += "<br />";
							deltaHeight -= 23;
						}

						template['html'] += '<tr><td class="' + curCSS + '" align="' + curAlign + '" height="1">' + curContainerHTML + '</td></tr>';
					}
				}

				template['html'] += '</tbody></table>';
				//RowLayout
			break;

			case "CustomLayout" : //User defined layout callback...
				var customLayout = tinyMCE.getParam("theme_dolphinet_custom_layout","");

				if (customLayout != "" && eval("typeof(" + customLayout + ")") != "undefined") {
					template = eval(customLayout + "(template);");
				}
			break;
		}

		if (resizing)
			template['html'] += '<span id="{$editor_id}_resize_box" class="mceResizeBox"></span>';

		template['html'] = tinyMCE.replaceVar(template['html'], 'style_select_options', styleSelectHTML);
		template['delta_width'] = 0;
		template['delta_height'] = deltaHeight;

		return template;
	},

	initInstance : function(inst) {
		if (tinyMCE.getParam("theme_dolphinet_resizing", false)) {
			if (tinyMCE.getParam("theme_dolphinet_resizing_use_cookie", true)) {
				var w = TinyMCE_DolphinetTheme._getCookie("TinyMCE_" + inst.editorId + "_width");
				var h = TinyMCE_DolphinetTheme._getCookie("TinyMCE_" + inst.editorId + "_height");

				TinyMCE_DolphinetTheme._resizeTo(inst, w, h, tinyMCE.getParam("theme_dolphinet_resize_horizontal", true));
			}
		}

		inst.addShortcut('ctrl', 'k', 'lang_link_desc', 'mceLink');
	},

	/**
	 * Node change handler.
	 */
	handleNodeChange : function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection, setup_content) {
		function selectByValue(select_elm, value, first_index) {
			first_index = typeof(first_index) == "undefined" ? false : true;

			if (select_elm) {
				for (var i=0; i<select_elm.options.length; i++) {
					var ov = "" + select_elm.options[i].value;

					if (first_index && ov.toLowerCase().indexOf(value.toLowerCase()) == 0) {
						select_elm.selectedIndex = i;
						return true;
					}

					if (ov == value) {
						select_elm.selectedIndex = i;
						return true;
					}
				}
			}

			return false;
		};

		function getAttrib(elm, name) {
			return elm.getAttribute(name) ? elm.getAttribute(name) : "";
		};

		// No node provided
		if (node == null)
			return;

		// Update path
		var pathElm = document.getElementById(editor_id + "_path");
		var inst = tinyMCE.getInstanceById(editor_id);
		var doc = inst.getDoc();

		if (pathElm) {
			// Get node path
			var parentNode = node;
			var path = new Array();
			
			while (parentNode != null) {
				if (parentNode.nodeName.toUpperCase() == "BODY") {
					break;
				}

				// Only append element nodes to path
				if (parentNode.nodeType == 1 && tinyMCE.getAttrib(parentNode, "class").indexOf('mceItemHidden') == -1) {
					path[path.length] = parentNode;
				}

				parentNode = parentNode.parentNode;
			}

			// Setup HTML
			var html = "";
			for (var i=path.length-1; i>=0; i--) {
				var nodeName = path[i].nodeName.toLowerCase();
				var nodeData = "";

				if (nodeName == "b") {
					nodeName = "strong";
				}

				if (nodeName == "i") {
					nodeName = "em";
				}

				if (nodeName == "span") {
					var cn = tinyMCE.getAttrib(path[i], "class");
					if (cn != "" && cn.indexOf('mceItem') == -1)
						nodeData += "class: " + cn + " ";

					var st = tinyMCE.getAttrib(path[i], "style");
					if (st != "") {
						st = tinyMCE.serializeStyle(tinyMCE.parseStyle(st));
						nodeData += "style: " + st + " ";
					}
				}

				if (nodeName == "font") {
					if (tinyMCE.getParam("convert_fonts_to_spans"))
						nodeName = "span";

					var face = tinyMCE.getAttrib(path[i], "face");
					if (face != "")
						nodeData += "font: " + face + " ";

					var size = tinyMCE.getAttrib(path[i], "size");
					if (size != "")
						nodeData += "size: " + size + " ";

					var color = tinyMCE.getAttrib(path[i], "color");
					if (color != "")
						nodeData += "color: " + color + " ";
				}

				if (getAttrib(path[i], 'id') != "") {
					nodeData += "id: " + path[i].getAttribute('id') + " ";
				}

				var className = tinyMCE.getVisualAidClass(tinyMCE.getAttrib(path[i], "class"), false);
				if (className != "" && className.indexOf('mceItem') == -1)
					nodeData += "class: " + className + " ";

				if (getAttrib(path[i], 'src') != "") {
					var src = tinyMCE.getAttrib(path[i], "mce_src");

					if (src == "")
						 src = tinyMCE.getAttrib(path[i], "src");

					nodeData += "src: " + src + " ";
				}

				if (getAttrib(path[i], 'href') != "") {
					var href = tinyMCE.getAttrib(path[i], "mce_href");

					if (href == "")
						 href = tinyMCE.getAttrib(path[i], "href");

					nodeData += "href: " + href + " ";
				}

				if (nodeName == "img" && tinyMCE.getAttrib(path[i], "class").indexOf('mceItemFlash') != -1) {
					nodeName = "flash";
					nodeData = "src: " + path[i].getAttribute('title');
				}

				if (nodeName == "a" && (anchor = tinyMCE.getAttrib(path[i], "name")) != "") {
					nodeName = "a";
					nodeName += "#" + anchor;
					nodeData = "";
				}

				if (getAttrib(path[i], 'name').indexOf("mce_") != 0) {
					var className = tinyMCE.getVisualAidClass(tinyMCE.getAttrib(path[i], "class"), false);
					if (className != "" && className.indexOf('mceItem') == -1) {
						nodeName += "." + className;
					}
				}

				var cmd = 'tinyMCE.execInstanceCommand(\'' + editor_id + '\',\'mceSelectNodeDepth\',false,\'' + i + '\');';
				html += '<a title="' + nodeData + '" href="javascript:' + cmd + '" onclick="' + cmd + 'return false;" onmousedown="return false;" target="_self" class="mcePathItem">' + nodeName + '</a>';

				if (i > 0) {
					html += " &raquo; ";
				}
			}

			pathElm.innerHTML = '<a href="#" accesskey="x"></a>' + tinyMCE.getLang('lang_theme_path') + ": " + html + '&#160;';
		}

		// Reset old states
		tinyMCE.switchClass(editor_id + '_justifyleft', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_justifyright', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_justifycenter', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_justifyfull', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_bold', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_italic', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_underline', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_strikethrough', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_bullist', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_numlist', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_sub', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_sup', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_anchor', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_link', 'mceButtonDisabled');
		tinyMCE.switchClass(editor_id + '_unlink', 'mceButtonDisabled');
		tinyMCE.switchClass(editor_id + '_outdent', 'mceButtonDisabled');
		tinyMCE.switchClass(editor_id + '_image', 'mceButtonNormal');
		tinyMCE.switchClass(editor_id + '_hr', 'mceButtonNormal');

		if (node.nodeName == "A" && tinyMCE.getAttrib(node, "class").indexOf('mceItemAnchor') != -1)
			tinyMCE.switchClass(editor_id + '_anchor', 'mceButtonSelected');

		// Get link
		var anchorLink = tinyMCE.getParentElement(node, "a", "href");

		if (anchorLink || any_selection) {
			tinyMCE.switchClass(editor_id + '_link', anchorLink ? 'mceButtonSelected' : 'mceButtonNormal');
			tinyMCE.switchClass(editor_id + '_unlink', anchorLink ? 'mceButtonSelected' : 'mceButtonNormal');
		}

		// Handle visual aid
		tinyMCE.switchClass(editor_id + '_visualaid', visual_aid ? 'mceButtonSelected' : 'mceButtonNormal');

		if (undo_levels != -1) {
			tinyMCE.switchClass(editor_id + '_undo', 'mceButtonDisabled');
			tinyMCE.switchClass(editor_id + '_redo', 'mceButtonDisabled');
		}

		// Within li, blockquote
		if (tinyMCE.getParentElement(node, "li,blockquote"))
			tinyMCE.switchClass(editor_id + '_outdent', 'mceButtonNormal');

		// Has redo levels
		if (undo_index != -1 && (undo_index < undo_levels-1 && undo_levels > 0))
			tinyMCE.switchClass(editor_id + '_redo', 'mceButtonNormal');

		// Has undo levels
		if (undo_index != -1 && (undo_index > 0 && undo_levels > 0))
			tinyMCE.switchClass(editor_id + '_undo', 'mceButtonNormal');

		// Select class in select box
		var selectElm = document.getElementById(editor_id + "_styleSelect");
		
		if (selectElm) {
			TinyMCE_DolphinetTheme._setupCSSClasses(editor_id);

			classNode = node;
			breakOut = false;
			var index = 0;

			do {
				if (classNode && classNode.className) {
					for (var i=0; i<selectElm.options.length; i++) {
						if (selectElm.options[i].value == classNode.className) {
							index = i;
							breakOut = true;
							break;
						}
					}
				}
			} while (!breakOut && classNode != null && (classNode = classNode.parentNode) != null);

			selectElm.selectedIndex = index;
		}

		// Handle align attributes
		alignNode = node;
		breakOut = false;
		do {
			if (!alignNode.getAttribute || !alignNode.getAttribute('align'))
				continue;

			switch (alignNode.getAttribute('align').toLowerCase()) {
				case "left":
					tinyMCE.switchClass(editor_id + '_justifyleft', 'mceButtonSelected');
					breakOut = true;
				break;

				case "right":
					tinyMCE.switchClass(editor_id + '_justifyright', 'mceButtonSelected');
					breakOut = true;
				break;

				case "middle":
				case "center":
					tinyMCE.switchClass(editor_id + '_justifycenter', 'mceButtonSelected');
					breakOut = true;
				break;

				case "justify":
					tinyMCE.switchClass(editor_id + '_justifyfull', 'mceButtonSelected');
					breakOut = true;
				break;
			}
		} while (!breakOut && (alignNode = alignNode.parentNode) != null);

		// Div justification
		var div = tinyMCE.getParentElement(node, "div");
		if (div && div.style.textAlign == "center")
			tinyMCE.switchClass(editor_id + '_justifycenter', 'mceButtonSelected');

		// Do special text
		if (!setup_content) {
			// , "JustifyLeft", "_justifyleft", "JustifyCenter", "justifycenter", "JustifyRight", "justifyright", "JustifyFull", "justifyfull", "InsertUnorderedList", "bullist", "InsertOrderedList", "numlist", "InsertUnorderedList", "bullist", "Outdent", "outdent", "Indent", "indent", "subscript", "sub"
			var ar = new Array("Bold", "_bold", "Italic", "_italic", "Strikethrough", "_strikethrough", "superscript", "_sup", "subscript", "_sub");
			for (var i=0; i<ar.length; i+=2) {
				if (inst.queryCommandState(ar[i]))
					tinyMCE.switchClass(editor_id + ar[i+1], 'mceButtonSelected');
			}

			if (inst.queryCommandState("Underline") && (node.parentNode == null || node.parentNode.nodeName != "A"))
				tinyMCE.switchClass(editor_id + '_underline', 'mceButtonSelected');
		}

		// Handle elements
		do {
			switch (node.nodeName) {
				case "UL":
					tinyMCE.switchClass(editor_id + '_bullist', 'mceButtonSelected');
				break;

				case "OL":
					tinyMCE.switchClass(editor_id + '_numlist', 'mceButtonSelected');
				break;

				case "HR":
					 tinyMCE.switchClass(editor_id + '_hr', 'mceButtonSelected');
				break;

				case "IMG":
				if (getAttrib(node, 'name').indexOf('mce_') != 0) {
					tinyMCE.switchClass(editor_id + '_image', 'mceButtonSelected');
				}
				break;
			}
		} while ((node = node.parentNode) != null);
	},

	// Private theme internal functions

	// This function auto imports CSS classes into the class selection droplist
	_setupCSSClasses : function(editor_id) {
		var i, selectElm;

		if (!TinyMCE_DolphinetTheme._autoImportCSSClasses)
			return;

		selectElm = document.getElementById(editor_id + '_styleSelect');

		if (selectElm && selectElm.getAttribute('cssImported') != 'true') {
			var csses = tinyMCE.getCSSClasses(editor_id);
			if (csses && selectElm)	{
				for (i=0; i<csses.length; i++)
					selectElm.options[selectElm.options.length] = new Option(csses[i], csses[i]);
			}

			// Only do this once
			if (csses != null && csses.length > 0)
				selectElm.setAttribute('cssImported', 'true');
		}
	},

	_setCookie : function(name, value, expires, path, domain, secure) {
		var curCookie = name + "=" + escape(value) +
			((expires) ? "; expires=" + expires.toGMTString() : "") +
			((path) ? "; path=" + escape(path) : "") +
			((domain) ? "; domain=" + domain : "") +
			((secure) ? "; secure" : "");

		document.cookie = curCookie;
	},

	_getCookie : function(name) {
		var dc = document.cookie;
		var prefix = name + "=";
		var begin = dc.indexOf("; " + prefix);

		if (begin == -1) {
			begin = dc.indexOf(prefix);

			if (begin != 0)
				return null;
		} else
			begin += 2;

		var end = document.cookie.indexOf(";", begin);

		if (end == -1)
			end = dc.length;

		return unescape(dc.substring(begin + prefix.length, end));
	},

	_resizeTo : function(inst, w, h, set_w) {
		var editorContainer = document.getElementById(inst.editorId + '_parent');
		var tableElm = editorContainer.firstChild;
		var iframe = inst.iframeElement;

		if (w == null || w == "null") {
			set_w = false;
			w = 0;
		}

		if (h == null || h == "null")
			return;

		w = parseInt(w);
		h = parseInt(h);

		if (tinyMCE.isGecko) {
			w += 2;
			h += 2;
		}

		var dx = w - tableElm.clientWidth;
		var dy = h - tableElm.clientHeight;

		w = w < 1 ? 30 : w;
		h = h < 1 ? 30 : h;

		if (set_w)
			tableElm.style.width = w + "px";

		tableElm.style.height = h + "px";

		iw = iframe.clientWidth + dx;
		ih = iframe.clientHeight + dy;

		iw = iw < 1 ? 30 : iw;
		ih = ih < 1 ? 30 : ih;

		if (tinyMCE.isGecko) {
			iw -= 2;
			ih -= 2;
		}

		if (set_w)
			iframe.style.width = iw + "px";

		iframe.style.height = ih + "px";

		// Is it to small, make it bigger again
		if (set_w) {
			var tableBodyElm = tableElm.firstChild;
			var minIframeWidth = tableBodyElm.scrollWidth;
			if (inst.iframeElement.clientWidth < minIframeWidth) {
				dx = minIframeWidth - inst.iframeElement.clientWidth;

				inst.iframeElement.style.width = (iw + dx) + "px";
			}
		}
	},

	/**
	 * Handles resizing events.
	 */
	_resizeEventHandler : function(e) {
		var resizer = TinyMCE_DolphinetTheme._resizer;

		// Do nothing
		if (!resizer.resizing)
			return;

		e = typeof(e) == "undefined" ? window.event : e;

		var dx = e.screenX - resizer.downX;
		var dy = e.screenY - resizer.downY;
		var resizeBox = resizer.resizeBox;
		var editorId = resizer.editorId;

		switch (e.type) {
			case "mousemove":
				var w, h;

				w = resizer.width + dx;
				h = resizer.height + dy;

				w = w < 1 ? 1 : w;
				h = h < 1 ? 1 : h;

				if (resizer.horizontal)
					resizeBox.style.width = w + "px";

				resizeBox.style.height = h + "px";
				break;

			case "mouseup":
				TinyMCE_DolphinetTheme._setResizing(e, editorId, false);
				TinyMCE_DolphinetTheme._resizeTo(tinyMCE.getInstanceById(editorId), resizer.width + dx, resizer.height + dy, resizer.horizontal);

				// Expire in a month
				if (tinyMCE.getParam("theme_dolphinet_resizing_use_cookie", true)) {
					var expires = new Date();
					expires.setTime(expires.getTime() + 3600000 * 24 * 30);

					// Set the cookies
					TinyMCE_DolphinetTheme._setCookie("TinyMCE_" + editorId + "_width", "" + (resizer.horizontal ? resizer.width + dx : ""), expires);
					TinyMCE_DolphinetTheme._setCookie("TinyMCE_" + editorId + "_height", "" + (resizer.height + dy), expires);
				}
				break;
		}
	},

	/**
	 * Starts/stops the editor resizing.
	 */
	_setResizing : function(e, editor_id, state) {
		e = typeof(e) == "undefined" ? window.event : e;

		var resizer = TinyMCE_DolphinetTheme._resizer;
		var editorContainer = document.getElementById(editor_id + '_parent');
		var editorArea = document.getElementById(editor_id + '_parent').firstChild;
		var resizeBox = document.getElementById(editor_id + '_resize_box');
		var inst = tinyMCE.getInstanceById(editor_id);

		if (state) {
			// Place box over editor area
			var width = editorArea.clientWidth;
			var height = editorArea.clientHeight;

			resizeBox.style.width = width + "px";
			resizeBox.style.height = height + "px";

			resizer.iframeWidth = inst.iframeElement.clientWidth;
			resizer.iframeHeight = inst.iframeElement.clientHeight;

			// Hide editor and show resize box
			editorArea.style.display = "none";
			resizeBox.style.display = "block";

			// Add event handlers, only once
			if (!resizer.eventHandlers) {
				if (tinyMCE.isMSIE)
					tinyMCE.addEvent(document, "mousemove", TinyMCE_DolphinetTheme._resizeEventHandler);
				else
					tinyMCE.addEvent(window, "mousemove", TinyMCE_DolphinetTheme._resizeEventHandler);

				tinyMCE.addEvent(document, "mouseup", TinyMCE_DolphinetTheme._resizeEventHandler);

				resizer.eventHandlers = true;
			}

			resizer.resizing = true;
			resizer.downX = e.screenX;
			resizer.downY = e.screenY;
			resizer.width = parseInt(resizeBox.style.width);
			resizer.height = parseInt(resizeBox.style.height);
			resizer.editorId = editor_id;
			resizer.resizeBox = resizeBox;
			resizer.horizontal = tinyMCE.getParam("theme_dolphinet_resize_horizontal", true);
		} else {
			resizer.resizing = false;
			resizeBox.style.display = "none";
			editorArea.style.display = tinyMCE.isMSIE && !tinyMCE.isOpera ? "block" : "table";
			tinyMCE.execCommand('mceResetDesignMode');
		}
	},

	_insertImage : function(src, alt, border, hspace, vspace, width, height, align, title, onmouseover, onmouseout) {
		tinyMCE.execCommand('mceBeginUndoLevel');

		if (src == "")
			return;

		if (!tinyMCE.imgElement && tinyMCE.isSafari) {
			var html = "";

			html += '<img src="' + src + '" alt="' + alt + '"';
			html += ' border="' + border + '" hspace="' + hspace + '"';
			html += ' vspace="' + vspace + '" width="' + width + '"';
			html += ' height="' + height + '" align="' + align + '" title="' + title + '" onmouseover="' + onmouseover + '" onmouseout="' + onmouseout + '" />';

			tinyMCE.execCommand("mceInsertContent", false, html);
		} else {
			if (!tinyMCE.imgElement && tinyMCE.selectedInstance) {
				if (tinyMCE.isSafari)
					tinyMCE.execCommand("mceInsertContent", false, '<img src="' + tinyMCE.uniqueURL + '" />');
				else
					tinyMCE.selectedInstance.contentDocument.execCommand("insertimage", false, tinyMCE.uniqueURL);

				tinyMCE.imgElement = tinyMCE.getElementByAttributeValue(tinyMCE.selectedInstance.contentDocument.body, "img", "src", tinyMCE.uniqueURL);
			}
		}

		if (tinyMCE.imgElement) {
			var needsRepaint = false;
			var msrc = src;

			src = eval(tinyMCE.settings['urlconverter_callback'] + "(src, tinyMCE.imgElement);");

			if (tinyMCE.getParam('convert_urls'))
				msrc = src;

			if (onmouseover && onmouseover != "")
				onmouseover = "this.src='" + eval(tinyMCE.settings['urlconverter_callback'] + "(onmouseover, tinyMCE.imgElement);") + "';";

			if (onmouseout && onmouseout != "")
				onmouseout = "this.src='" + eval(tinyMCE.settings['urlconverter_callback'] + "(onmouseout, tinyMCE.imgElement);") + "';";

			// Use alt as title if it's undefined
			if (typeof(title) == "undefined")
				title = alt;

			if (width != tinyMCE.imgElement.getAttribute("width") || height != tinyMCE.imgElement.getAttribute("height") || align != tinyMCE.imgElement.getAttribute("align"))
				needsRepaint = true;

			tinyMCE.setAttrib(tinyMCE.imgElement, 'src', src);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'mce_src', msrc);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'alt', alt);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'title', title);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'align', align);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'border', border, true);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'hspace', hspace, true);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'vspace', vspace, true);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'width', width, true);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'height', height, true);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'onmouseover', onmouseover);
			tinyMCE.setAttrib(tinyMCE.imgElement, 'onmouseout', onmouseout);

			// Fix for bug #989846 - Image resize bug
			if (width && width != "")
				tinyMCE.imgElement.style.pixelWidth = width;

			if (height && height != "")
				tinyMCE.imgElement.style.pixelHeight = height;

			if (needsRepaint)
				tinyMCE.selectedInstance.repaint();
		}

		tinyMCE.execCommand('mceEndUndoLevel');
	},

	_insertLink : function(href, target, title, onclick, style_class) {
		tinyMCE.execCommand('mceBeginUndoLevel');

		if (tinyMCE.selectedInstance && tinyMCE.selectedElement && tinyMCE.selectedElement.nodeName.toLowerCase() == "img") {
			var doc = tinyMCE.selectedInstance.getDoc();
			var linkElement = tinyMCE.getParentElement(tinyMCE.selectedElement, "a");
			var newLink = false;

			if (!linkElement) {
				linkElement = doc.createElement("a");
				newLink = true;
			}

			var mhref = href;
			var thref = eval(tinyMCE.settings['urlconverter_callback'] + "(href, linkElement);");
			mhref = tinyMCE.getParam('convert_urls') ? href : mhref;

			tinyMCE.setAttrib(linkElement, 'href', thref);
			tinyMCE.setAttrib(linkElement, 'mce_href', mhref);
			tinyMCE.setAttrib(linkElement, 'target', target);
			tinyMCE.setAttrib(linkElement, 'title', title);
			tinyMCE.setAttrib(linkElement, 'onclick', onclick);
			tinyMCE.setAttrib(linkElement, 'class', style_class);

			if (newLink) {
				linkElement.appendChild(tinyMCE.selectedElement.cloneNode(true));
				tinyMCE.selectedElement.parentNode.replaceChild(linkElement, tinyMCE.selectedElement);
			}

			return;
		}

		if (!tinyMCE.linkElement && tinyMCE.selectedInstance) {
			if (tinyMCE.isSafari) {
				tinyMCE.execCommand("mceInsertContent", false, '<a href="' + tinyMCE.uniqueURL + '">' + tinyMCE.selectedInstance.selection.getSelectedHTML() + '</a>');
			} else
				tinyMCE.selectedInstance.contentDocument.execCommand("createlink", false, tinyMCE.uniqueURL);

			tinyMCE.linkElement = tinyMCE.getElementByAttributeValue(tinyMCE.selectedInstance.contentDocument.body, "a", "href", tinyMCE.uniqueURL);

			var elementArray = tinyMCE.getElementsByAttributeValue(tinyMCE.selectedInstance.contentDocument.body, "a", "href", tinyMCE.uniqueURL);

			for (var i=0; i<elementArray.length; i++) {
				var mhref = href;
				var thref = eval(tinyMCE.settings['urlconverter_callback'] + "(href, elementArray[i]);");
				mhref = tinyMCE.getParam('convert_urls') ? href : mhref;

				tinyMCE.setAttrib(elementArray[i], 'href', thref);
				tinyMCE.setAttrib(elementArray[i], 'mce_href', mhref);
				tinyMCE.setAttrib(elementArray[i], 'target', target);
				tinyMCE.setAttrib(elementArray[i], 'title', title);
				tinyMCE.setAttrib(elementArray[i], 'onclick', onclick);
				tinyMCE.setAttrib(elementArray[i], 'class', style_class);
			}

			tinyMCE.linkElement = elementArray[0];
		}

		if (tinyMCE.linkElement) {
			var mhref = href;
			href = eval(tinyMCE.settings['urlconverter_callback'] + "(href, tinyMCE.linkElement);");
			mhref = tinyMCE.getParam('convert_urls') ? href : mhref;

			tinyMCE.setAttrib(tinyMCE.linkElement, 'href', href);
			tinyMCE.setAttrib(tinyMCE.linkElement, 'mce_href', mhref);
			tinyMCE.setAttrib(tinyMCE.linkElement, 'target', target);
			tinyMCE.setAttrib(tinyMCE.linkElement, 'title', title);
			tinyMCE.setAttrib(tinyMCE.linkElement, 'onclick', onclick);
			tinyMCE.setAttrib(tinyMCE.linkElement, 'class', style_class);
		}

		tinyMCE.execCommand('mceEndUndoLevel');
	}
};

tinyMCE.addTheme("dolphinet", TinyMCE_DolphinetTheme);

// Add default buttons maps for advanced theme and all internal plugins
tinyMCE.addButtonMap(TinyMCE_DolphinetTheme._buttonMap);