
(function() {
	tinymce.create('tinymce.plugins.PasioImage', {
		url: '',
		editor: {},
		editButton : null,

		init: function(ed, url) {
			var t = this, mouse = {};
			var hash_disabled = true;

			t.url = url;
			t.editor = ed;
			t._createButtons();

			function init_movingcart() {
				jQuery.noConflict();
				(function($) {
					var slug = $('#movingcart-sdk').attr('slug');
					if ( !slug ) {
						alert('판매자 코드가 지정되지 않았습니다.');
						return;
					}

					//register toolbar button 2015-08-20
					ed.addButton('movingcart_hash', {
						title : '무빙카트 상품링크(링크할 텍스트 또는 이미지를 드래그하세요)',
						cmd : 'movingcart_hash',
						image : url + '/img/movingcart.png'
					});

					// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('...');
					ed.addCommand('Pasio_Image', function(ui, v) {
						var el = ed.selection.getNode(), vp, H, W, cls = ed.dom.getAttrib(el, 'class');
						var url_id = v;

						if ( cls.indexOf('mceItem') != -1 || cls.indexOf('wpGallery') != -1 || el.nodeName != 'IMG' )
							return;

						vp = tinymce.DOM.getViewPort();
						H = 500 < (vp.h - 70) ? 500 : vp.h - 70;
						W = 900 < vp.w ? 900 : vp.w;

						if ( url_id ) {
							//window.open('http://www.movingcart.kr/urls/edit/' + url_id);
							window.open('https://admin.movingcart.kr/products#/by_url');
							/*ed.windowManager.open({
								file: url + '/addimage.php?url_id=' + url_id + '&' + (new Date()).getTime(),
								width: W+'px',
								height: H+'px',
								inline: true
							});*/
						} else {
							window.open('https://admin.movingcart.kr/urls/add?target_url=' + encodeURIComponent(el.src));
							/*ed.windowManager.open({
								file: url + '/addimage.php?' + (new Date()).getTime(),
								width: W+'px',
								height: H+'px',
								inline: true
							});*/
						}
					});

					ed.addCommand('movingcart_hash', function() {
						if ( hash_disabled )
							return alert('링크할 텍스트 또는 이미지를 드래그해 선택해주세요.');
						/*ed.windowManager.open({
							file : url + '/search.html',
							width : 480,
							height : 600,
							inline : true,
							title : '무빙카트 상품검색'
						}, {
							plugin_url : url // Plugin absolute URL
						});*/
						if ( !slug ) 	return alert('"무빙카트 설정"메뉴에서 판매자 코드를 설정해주세요.');

						tb_show("링크할 무빙카트 상품을 검색", url + "/search.php?slug=" + slug);
					})

					ed.onInit.add(function(ed) {

						//ed.onInit.add(function(ed) {
		                ed.dom.bind(ed.getWin(), 'scroll', function(e) {
		                        ed.plugins.pasio_plugin._hideButtons();
		                });
		                ed.dom.bind(ed.getBody(), 'dragstart', function(e) {
		                        ed.plugins.pasio_plugin._hideButtons();
		                });
		                //});

						ed.dom.bind(ed.getBody(), 'dragstart', function(e) {
							var parent;

							if ( e.target.nodeName == 'IMG' && ( parent = ed.dom.getParent(e.target, 'div.mceTemp') ) ) {
								ed.selection.select(parent);
							}
						});
					});

					// resize the caption <dl> when the image is soft-resized by the user (only possible in Firefox and IE)
					ed.onMouseUp.add(function(ed, e) {
						if ( tinymce.isWebKit || tinymce.isOpera )
							return;

						if ( mouse.x && (e.clientX != mouse.x || e.clientY != mouse.y) ) {
							var n = ed.selection.getNode();

							if ( 'IMG' == n.nodeName ) {
								window.setTimeout(function(){
									var DL = ed.dom.getParent(n, 'dl.wp-caption'), width;

									if ( n.width != mouse.img_w || n.height != mouse.img_h )
										n.className = n.className.replace(/size-[^ "']+/, '');

									if ( DL ) {
										width = ed.dom.getAttrib(n, 'width') || n.width;
										width = parseInt(width, 10);
										ed.dom.setStyle(DL, 'width', 10 + width);
										ed.execCommand('mceRepaint');
									}
								}, 100);
							}
						}
						mouse = {};
					});

					ed.onBeforeExecCommand.add(function(ed, cmd, ui, val) {
						ed.plugins.pasio_plugin._hideButtons();
		            });

		            ed.onSaveContent.add(function(ed, o) {
		            	ed.plugins.pasio_plugin._hideButtons();
		            });

		            ed.onMouseDown.add(function(ed, e) {
		            	if ( e.target.nodeName != 'IMG' )
		            		ed.plugins.pasio_plugin._hideButtons();
		            });

					// show editimage buttons
					ed.onMouseDown.add(function(ed, e) {
						var target = e.target;

						if ( target.nodeName != 'IMG' ) {
							if ( target.firstChild && target.firstChild.nodeName == 'IMG' && target.childNodes.length == 1 )
								target = target.firstChild;
							else
								return;
						}

						if ( ed.dom.getAttrib(target, 'class').indexOf('mceItem') == -1 ) {
							mouse = {
								x: e.clientX,
								y: e.clientY,
								img_w: target.clientWidth,
								img_h: target.clientHeight
							};
							
							ed.plugins.pasio_plugin._showButtons(target, 'siot_pasiobtn_holder');
						}
					});

					ed.onNodeChange.add(function(ed, cm, n, co) {
						hash_disabled = co && n.nodeName != 'A';
					});

/*
					$.getJSON('http://admin.movingcart.kr/urls/lists/' + slug + '.json?callback=?', function(rsp) {
						var url_list = rsp;
						var images = $(ed.getBody()).find('img');
						
						$.each(images, function(idx, img) {
							var src = $(img).attr('src');
							$.each(url_list, function(jdx, u) {
								if ( src == u.Url.target_url || decodeURIComponent(src) == u.Url.target_url ) {
									$(img).data('movingcart-url-id', u.Url.id);

									return false;
								}
							})
						})
					});
*/
				})(jQuery);
			}

			if ( !window.jQuery ) {
				tinymce.ScriptLoader.add('js/jquery.js');
				tinymce.ScriptLoader.loadQueue(function() {
					init_movingcart();
				});
			} else {
				init_movingcart();
			}
		},

		_createButtons : function() {
			var t = this, ed = tinyMCE.activeEditor, DOM = tinymce.DOM;

			DOM.remove('siot_pasiobtn_holder');

			DOM.add(document.body, 'div', {
				id : 'siot_pasiobtn_holder',
				style : 'display:none;'
			});

			editButton = DOM.add('siot_pasiobtn_holder', 'img', {
				src : t.url+'/img/mc_icon.gif',
				id : 'siot_pasiobtn',
				width : '31',
				height : '24',
				title : '무빙카트 상품등록',
				style : 'cursor:pointer'
				//title : ed.getLang('wpeditimage.edit_img')
			});

			DOM.bind(editButton, 'mousedown', function(e) {
				var ed = tinyMCE.activeEditor;
				ed.windowManager.bookmark = ed.selection.getBookmark('simple');
				ed.execCommand("Pasio_Image", true, DOM.getAttrib(editButton, 'movingcart-url-id'));
				ed.plugins.pasio_plugin._hideButtons();
			});
			
		},
		
		_showButtons : function(n, id) {
			var ed = tinyMCE.activeEditor, p1, p2, vp, DOM = tinymce.DOM, X, Y;

			var url_id = jQuery(n).data('movingcart-url-id');
			if ( url_id > -1 ) {
				//DOM.setAttrib(editButton, 'src', this.url + '/img/asdf.png');
				DOM.setAttrib(editButton, 'movingcart-url-id', url_id);
			} else {
				//DOM.setAttrib(editButton, 'src', this.url + '/img/image.png');
			}

			vp = ed.dom.getViewPort(ed.getWin());
			p1 = DOM.getPos(ed.getContentAreaContainer());
			p2 = ed.dom.getPos(n);

			X = Math.max(p2.x - vp.x, 0) + p1.x;
			Y = Math.max(p2.y - vp.y, 0) + p1.y;

			DOM.setStyles(id, {
				'top' : Y+5+'px',
				'left' : X+75+'px',
				'display' : 'block'
			});

			if ( this.mceTout )
				clearTimeout(this.mceTout);

			this.mceTout = setTimeout( function(){ed.plugins.pasio_plugin._hideButtons();}, 5000 );
		},

		_hideButtons : function() {
			if ( !this.mceTout )
				return;

			if ( document.getElementById('siot_pasiobtn_holder') )
				tinymce.DOM.hide('siot_pasiobtn_holder');

			clearTimeout(this.mceTout);
			this.mceTout = 0;
		},

		getInfo : function() {
			return {
				longname : 'Pasio',
				author : 'SIOT',
				authorurl : 'http://siot.do',
				infourl : '',
				version : "0.5"
			};
		}
	});

	tinymce.PluginManager.add('pasio_plugin', tinymce.plugins.PasioImage);
})();
