<?php
	$pid = $_GET['productID'];
	if(!empty($pid)) : ?>
		<h2>등록되었습니다.</h2>
		<script>
			setTimeout(function() {
				tinyMCEPopup.close();
			}, 1000);
		</script>
	<? else :?>
		<h2>상품등록에 실패하였습니다.</h2>
		<script>
			setTimeout(function() {
			}, 1500);
		</script>
	<? endif;?>
