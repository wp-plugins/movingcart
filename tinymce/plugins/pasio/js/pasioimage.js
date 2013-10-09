var tinymce = null, tinyMCEPopup, tinyMCE, pasioImage;

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

pasioImage = {
	
	I : function(e) {
		return document.getElementById(e);
	},

	init : function() {
		this.I('img_url').value = tinyMCEPopup.getImageURL();
	},
	
	cart_position : function(cart, w, h, x, y) {
		var _x, _y;
		if(x < 0) {
			_x = w + x - cart.width();
		} else {
			_x = x;
		}
		
		if(y < 0) {
			_y = h + y - cart.height();
		} else {
			_y = y;
		}
		
		cart.css({left:_x, top:_y});
	}
};

jQuery(document).ready(function() {
	pasioImage.init();
});
