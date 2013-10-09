var tinymce = null, tinyMCEPopup, tinyMCE;

tinyMCEPopup = {
	init: function() {
		var t = this, w, ti;

		// Find window & API
		w = t.getWin();
		tinymce = w.tinymce;
		tinyMCE = w.tinyMCE;
		t.editor = tinymce.EditorManager.activeEditor;
		t.params = t.editor.windowManager.params;
		t.features = t.editor.windowManager.features;

		// Setup local DOM
		t.dom = t.editor.windowManager.createInstance('tinymce.dom.DOMUtils', document);
		t.editor.windowManager.onOpen.dispatch(t.editor.windowManager, window);
	},

	getWin : function() {
		return (!window.frameElement && window.dialogArguments) || opener || parent || top;
	},

	getParam : function(n, dv) {
		return this.editor.getParam(n, dv);
	},

	close : function() {
		var t = this;

		// To avoid domain relaxing issue in Opera
		function close() {
			t.editor.windowManager.close(window);
			tinymce = tinyMCE = t.editor = t.params = t.dom = t.dom.doc = null; // Cleanup
		}

		if (tinymce.isOpera)
			t.getWin().setTimeout(close, 0);
		else
			close();
	},

	execCommand : function(cmd, ui, val, a) {
		a = a || {};
		a.skip_focus = 1;

		this.restoreSelection();
		return this.editor.execCommand(cmd, ui, val, a);
	},

	getImageURL: function()
	{
		var ed = this.editor;
		var el = ed.selection.getNode();

		if (el.nodeName != 'IMG')
			return '';

		return ed.dom.getAttrib(el, 'src');
	}
};

tinyMCEPopup.init();

jQuery(document).ready(function() {
	var baseURL = "http://movingcart.kr/urls/add";
	var callbackURL = "http://" + window.location.hostname + "/wp-content/plugins/pasio/tinymce/plugins/pasio/confirm.php";
	var redirectURL = baseURL + "?target_url=" + encodeURIComponent(tinyMCEPopup.getImageURL()) + "&callbackURL=" + encodeURIComponent(callbackURL);
	window.location = redirectURL;
});
