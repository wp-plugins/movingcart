<?php
/*
 Plugin Name: MovingCart
 Plugin URI: http://wp.movingcart.kr
 Description: 워드프레스(Wordpress)에 다른 e-commerce 플러그인을 설치할 필요없이, 신용카드결제까지 가능한 쇼핑몰로 바뀝니다.
 Version: 1.2.0
 Author: SIOT
 Author URI: http://www.movingcart.kr
 License: GPL2
*/
if(!class_exists('PasioImagePlugin')) {
	
	class PasioImagePlugin {

		private $script_url = 'http://www.movingcart.kr/js/movingcart.js';
		private $admin_script_url = 'http://www.movingcart.kr/js/movingcart-wadmin.js';
	
		public function __construct() {
			register_activation_hook(__FILE__, array( &$this, 'pasio_plugin_install') );
			//register_deactivation_hook( __FILE__, array( &$this, 'pasio_plugin_remove' ) );
			register_uninstall_hook( __FILE__, array( &$this, 'pasio_plugin_remove' ) );

			add_action('admin_enqueue_scripts', array( &$this, 'pasio_admin_script_enqueue') );
			add_action('wp_enqueue_scripts', array(&$this, 'pasio_script_add'), 99);

			add_filter('clean_url', array(&$this, 'add_slug_to_script'), 11, 1);
			
			if( is_admin() ) {
				add_action('init', array( &$this, 'pasio_addbuttons') );
				add_action('admin_menu', array( &$this, 'pasio_admin_menu') );

				add_filter('attachment_fields_to_edit', array(&$this, 'pasio_attachments_fields_to_edit'), 10, 2);
				add_filter('admin_head_media_upload_gallery_form', array(&$this, 'movingcart_gallery_head'));
				add_action('admin_head_media_upload_library_form', array(&$this, 'movingcart_gallery_head'));
			}
		}
	
		function pasio_plugin_install() {
		}
 
		function pasio_plugin_remove() {
			delete_option('pasio_slug');
		}
		
		function pasio_script_add() {
			wp_deregister_script( 'pasio-script' );
			//wp_register_script( 'pasio-script', $this->script_url, array(), false, true);
			// ver 1.1.1 wp_footer()를 호출하지 않는 theme들이 있어서 아래 방식으로 다시 변경(head에 script붙이는 방식)
			wp_register_script( 'pasio-script', $this->script_url);
			wp_enqueue_script( 'pasio-script' );
		}

		function add_slug_to_script($url) {
			if ( strpos($url, $this->script_url) === false && strpos($url, $this->admin_script_url) === false ) {
				return $url;
			}

			$slug = get_option('pasio_slug');
			$show_widget = get_option('show_widget');

			if ( "N" == $show_widget ) {
				return "$url' id='movingcart-sdk' slug='$slug' widget='false";
			}

			return "$url' id='movingcart-sdk' slug='$slug";
		}

		function movingcart_gallery_head() { 
			$slug = get_option('pasio_slug'); 
			ob_start();
		?>
			<script type="text/javascript">
			if ( typeof Number.prototype.format == 'undefined' ) {
				Number.prototype.format = function(){
				    if(this==0) return 0;
				 
				    var reg = /(^[+-]?\d+)(\d{3})/;
				    var n = (this + '');
				 
				    while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
				 
				    return n;
				};
			}
			
			if ( typeof String.prototype.format == 'undefined' ) {
				// 문자열 타입에서 쓸 수 있도록 format() 함수 추가
				String.prototype.format = function(){
				    var num = parseFloat(this);
				    if( isNaN(num) ) return "0";
				 
				    return num.format();
				};
			}

			jQuery.noConflict();
			(function($) {
				$(function() {
					var slug = '<?=$slug?>';
					$.getJSON('http://movingcart.kr/urls/lists/' + slug + '.json?callback=?', function(rsp) {
						var url_list = rsp;
						var fields = $('.movingcart_product_info');

						$.each(fields, function(idx, field) {
							var $field = $(field);
							var url = $field.data('url');
							var url_id = -1;
							$.each(url_list, function(jdx, u) {
								if ( url == u.Url.target_url || decodeURIComponent(url) == u.Url.target_url ) {
									$field.find('span.name').html(u.Url.title_text);
									$field.find('span.desc').html(u.Url.description);
									$field.find('span.price').html(u.Url.price.format());

									url_id = u.Url.id;
									return false;
								}
							})

							if ( url_id > -1 ) {
								$field.find('.movingcart-item-info').show();
								$field.find('.movingcart-item-action a.add').hide();
								$field.find('.movingcart-item-action a.edit').attr('href', 'http://www.movingcart.kr/urls/edit/' + url_id);
							} else {
								$field.find('.movingcart-item-action a.edit').hide();
							}
						});
					});
				});
			})(jQuery);
			</script>
		<? 
		ob_end_flush();
		}

		function pasio_attachments_fields_to_edit($form_fields, $post) {
			if ( !isset($form_fields['pasio_url_register_link']) ) {
				$file = wp_get_attachment_url($post->ID);

				$form_fields['pasio_url_register_link'] = array(
                        		'label'      => '무빙카트 판매상품정보',
                        		'input'      => 'html',
                        		'html'       => sprintf('<div class="movingcart_product_info" data-url="%s">
                        									<div class="movingcart-item-info" style="display:none">
                        										<div>
                        											<label>이름 : </label><span class="name"></span>
                        										</div>
                        										<div>
                        											<label>상세 : </label><span class="desc"></span>
                        										</div>
                        										<div>
                        											<label>가격 : </label><span class="price"></span>
                        										</div>
                        									</div>
                        									<div class="movingcart-item-action">
                    											<a target="_blank" href="http://www.movingcart.kr/urls/add?target_url=%s" class="button urlnone add">판매이미지등록</a>
                    											<a target="_blank" href="http://www.movingcart.kr/urls/add?target_url=%s" class="button urlnone edit">정보수정</a>
                    										</div>
                        								</div>', esc_attr($file), urlencode(esc_attr($file)), urlencode(esc_attr($file))),
                        		//'value'      => wp_get_attachment_url($post->ID),
                        		'helps'      => 'MovingCart를 통해 판매상품정보를 관리할 수 있습니다.'
                );
			}

			return $form_fields;
		}

		function pasio_addbuttons() {
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
				return;
			
			if ( get_user_option('rich_editing') == 'true') {
				add_filter('mce_external_plugins', array(&$this, 'add_pasio_tinymce_plugin'));
				add_filter('mce_buttons', array(&$this, 'add_movingcart_tinymce_toolbar'));
			}
		}
		 
		function add_pasio_tinymce_plugin($plugin_array) {
			$plugin_array['pasio_plugin'] = plugins_url(basename(dirname(__FILE__)).'/tinymce/plugins/pasio/pasio_editor.js');
			return $plugin_array;
		}

		function add_movingcart_tinymce_toolbar($buttons) {
			array_push($buttons, 'movingcart_hash');
   			return $buttons;
		}

		function pasio_admin_script_enqueue($hook) {
			if( 'post.php' != $hook && 'post-new.php' != $hook )
				return;

			wp_deregister_script( 'pasio-storage-script' );
			wp_register_script( 'pasio-storage-script', plugins_url(basename(dirname(__FILE__)).'/js/storage.js') );
			wp_enqueue_script( 'pasio-storage-script' );

			wp_deregister_script( 'pasio-admin-script' );
			wp_register_script( 'pasio-admin-script', $this->admin_script_url);
			wp_enqueue_script( 'pasio-admin-script' );

			wp_register_style( 'custom_wp_admin_css', plugins_url(basename(dirname(__FILE__)).'/css/default.css'), false );
			wp_enqueue_style( 'custom_wp_admin_css' );

		}

		function pasio_admin_menu() {
			//add_options_page('Plugin Admin Options', 'My Plugin Settings', 'manage_options', 'my-first', 'plugin_admin_options_page');
			add_menu_page( '무빙카트 설정', '무빙카트 설정', 'administrator', dirname(__FILE__), array(&$this, 'pasio_admin_page'), plugin_dir_url( __FILE__ ) . '/img/icon_16.png' );
			//add_action('admin_init', array(&$this, 'register_pasio_settings'));
		}
		
		function pasio_admin_page() { 
			ob_start();
		?>
			<div class="wrap">
				<h2>무빙카트 설정페이지</h2>
				<p>
					<h3>무빙카트 판매자 정보 설정</h3>
					<form method="post" action="">
						<table class="form-table">
							<tbody>
								<tr valign="top">
									<th scope="row" style="width:100px;"><label for="pasio_slug">판매자 코드</label></th>
									<td><input class="regular-text" name="pasio_slug" type="text" id="pasio_slug" value="<?=get_option('pasio_slug')?>" /></td>
									<td><a target="_blank" href="http://www.movingcart.kr/users/my">판매자코드 알아보기</a></td>
								</tr>
								<tr valign="top">
									<th scope="row" style="width:100px;">무빙카트 위젯 <br/>보여주기</th>
									<td>
										<label for="show_widget_y"><input name="show_widget" type="radio" id="show_widget_y" value="Y" <?= get_option('show_widget')!='N'?'checked':'' ?>/>보여주기</label><br/>
										<label for="show_widget_n"><input name="show_widget" type="radio" id="show_widget_n" value="N" <?= get_option('show_widget')=='N'?'checked':'' ?>/>감추기</label>
									</td>
									<td><img src="http://buy.movingcart.kr/img/external/mc_widget_bar.png"/><br/>웹사이트 오른쪽 하단에 보여지는 빨간 위젯(구매자가 장바구니/주문내역을 확인해볼 수 있는 버튼)의 표시 여부를 결정합니다. <br/>관리자 페이지의 "외모 > 메뉴"에서 "링크 메뉴"의 URL값을 #open_movingcart로 저장하시면 동일한 기능을 하는 메뉴가 생성됩니다.<br/><strong><xmp><a href="#open_movingcart">장바구니</a></xmp></strong>태그를 통해서도 동일한 기능을 제공할 수 있습니다.</td>
								</tr>
							</tbody>
						</table>
						
						<?php wp_nonce_field('pasio-options', 'pasio-user-info'); ?>
						<input type="hidden" name="action" value="update_pasio" />
						<input class="button-primary" type="submit" name="pasio-options" value="저장하기" />
					</form>
				</p>
			</div>
		<?php 
		ob_end_flush();
		}
	}
	
	new PasioImagePlugin();
}

if (isset($_POST['action']) && $_POST['action'] == "update_pasio"){
	require_once(ABSPATH .'wp-includes/pluggable.php');
	if (wp_verify_nonce($_POST['pasio-user-info'],'pasio-options')){
		update_option('pasio_slug',$_POST['pasio_slug']);
		update_option('show_widget',$_POST['show_widget']);
	}else{ ?>
		<div class="error">update failed</div><?php
	}
}