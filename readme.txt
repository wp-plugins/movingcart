=== Plugin Name ===
Contributors: movingcart
Donate link: http://www.movingcart.kr
Tags: movingcart, commerce, woocommerce
Requires at least: 3.0.1
Tested up to: 3.8.0
Stable tag: 1.1.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

다른 플러그인을 설치할 필요없이, 신용카드결제까지 가능한 쇼핑몰로 바뀝니다.

== Description ==

워드프레스에 팝업 레이어 형태로 쇼핑몰 기능을 삽입해주는 플러그인입니다. 무빙카트 플러그인 설치 후, 상품 이미지를 이용해 상품등록을 하게되면, 등록된 상품이미지위에 구매하기 버튼이 생성되어 쇼핑몰 기능을 이용하실 수 있습니다. 
상품 이미지를 이용한 상품 등록은 워드프레스 관리자 페이지에서 쉽게 가능합니다. (보다 자세한 내용은 http://www.movingcart.kr/pages/usage#!/wordpress 를 참고하세요.)
판매할 상품은 이미지 URL주소를 기준으로 상품 가격, 판매 옵션 등 정보가 등록되어야 합니다. 

http://wp.movingcart.kr 에서 무빙카트 플러그인이 설치된 워드프레스를 체험하실 수 있습니다.


*   무빙카트 서비스( http://www.movingcart.kr ) 에서 판매자 회원가입을 합니다.
*   무빙카트 플러그인을 다운받아 워드프레스에 설치합니다.
*   무빙카트 설정 메뉴에서 판매자 회원가입 후 발급되는 판매자 코드를 저장합니다.
*   판매할 상품 이미지를 선택해 등록하시면 됩니다. 등록되는 이미지 위에 구매하기 버튼이 생성됩니다.
*	팝업창 형태로 쇼핑몰 서비스가 제공이되며, 구매자는 장바구니, 주문조회 등의 기능을 모두 사용할 수 있습니다.

woocommerce플러그인은 연동될 수 있는 테마가 정해져있지만, 
무빙카트 플러그인은 어떠한 워드프레스 테마에도 적용될 수 있습니다. 
특히, 테마의 레이아웃(디자인)을 변형시키지 않으면서 구매하기 버튼이 생성되어, 별도의 HTML코딩이나 shortcode를 사용할 필요가 없이 바로 사용 가능합니다.

"상품을 대표하는 이미지"를 기준으로 판매할 상품 정보가 등록되며, 등록된 "상품을 대표하는 이미지"에는 구매하기 버튼이 생성됩니다.


== Installation ==

무빙카트 플러그인 설치, http://www.movingcart.kr 에서 판매자 회원가입, 판매자 코드 저장이 필요합니다.


1. 다운받은 movingcart.zip파일을 `/wp-content/plugins/` 디렉토리에 복사합니다.
2. unzip movingcart.zip으로 압축 파일을 해제하면 movingcart폴더가 생성됩니다.
3. 워드프레스 관리자페이지에서 'Plugins'메뉴를 통해 무빙카트 플러그인을 활성화합니다. 
4. http://www.movingcart.kr 에서 판매자 회원가입 후 발급받은 판매자 코드(마이페이지에서 확인 가능)를 복사합니다. 
5. 워드프레스 관리자페이지에서 '무빙카트 설정'메뉴를 통해 판매자코드를 입력 후 저장합니다.

== Frequently Asked Questions ==
= 서비스 주소 =
http://www.movingcart.kr
= 페이스북 =
https://www.facebook.com/movingcart
= 블로그 =
http://movingcart.tistory.com

= 고객센터 =
070-8658-8870 / movingcart@siot.do

== Screenshots ==
1. 무빙카트 플러그인을 사용한 워드프레스 샘플 사이트입니다. ( http://wp.movingcart.kr )
2. 키워드 "movingcart"로 무빙카트 워드프레스 플러그인 검색
3. http://www.movingcart.kr 에서 판매자 회원가입 후 발급된 판매자 코드를 플러그인 설정에 저장. 게시물 작성 에디터에서 판매할 상품 이미지 등록(아이콘)
4. 등록할 상품 이미지에 대한 판매 정보(가격, 수량, 옵션 등)들을 등록. 워드프레스를 새로고침하면 등록된 이미지에 구매하기 버튼이 생성됨.
5. 특성이미지 선택 창에서도 상품 이미지를 등록할 수 있습니다.(적용된 테마에 따라 특성이미지나 첨부 이미지가 화면에 표시되는 경우, 이 방법으로 구매하기 버튼을 부착할 수 있습니다.)


== Changelog ==
= 1.1.2 =
* XSS공격 보안 강화

= 1.1.1 =
* theme에 따라 wp_footer()를 호출하지 않는 theme들이 있어서 무빙카트 설치 스크립트가 실행되지 않는 경우가 있었습니다. wp_footer()를 호출하지 않는 theme에서도 무빙카트 스크립트가 동작할 수 있도록 수정되었습니다.

= 1.1.0 =
* 오른쪽 하단의 "무빙카트 위젯" 표시여부 옵션 추가. "무빙카트 위젯"은 구매자가 어디서든 장바구니/주문내역을 확인하고 싶을 때 클릭하는 위젯입니다.
* "무빙카트 위젯"을 숨기고, 메인 메뉴/사이드바 메뉴 등을 통해 동일한 기능을 제공할 수 있도록 <xmp><a href="#open_movingcart">장바구니</a></xmp> 태그를 지원합니다. 
* 메뉴, 사이드바 등에 <xmp><a href="#open_movingcart">장바구니</a></xmp> 태그를 삽입하면 됩니다.

= 1.0 =
* 최초 배포
* http://wp.movingcart.kr 에 적용된 버전


== Arbitrary section ==


== A brief Markdown Example ==

