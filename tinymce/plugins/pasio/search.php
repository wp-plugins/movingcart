<style type="text/css">
#mc-search-results {border-top: 1px solid #dfdfdf;border-left: 1px solid #dfdfdf;border-right: 1px solid #dfdfdf;}
#mc-search-results li {padding: 6px 10px;border-bottom: 1px solid #dfdfdf;}
</style>    
<div id="movingcart-hash-dialog">
    <div>
        <p>연결하실 무빙카트 상품을 검색해주세요</p>
        <div>
            <div class="link-search-wrapper">
                <label>
                    <span class="search-label">상품명</span>
                    <input type="search" class="link-search-field" autocomplete="off" id="mc-search-title"/>
                    <a class="button" id="mc-search">검색</a>
                </label>
            </div>
            <ul id="mc-search-results" class="query-results"></ul>
        </div>
    </div>
    <div class="submitbox">
        <div>
            <a class="button-primary" id="mc-link-apply">적용하기</a>
        </div>
    </div>
</div>
<script type="text/javascript">
jQuery(function($) {
    var slug    = '<?= trim( $_GET["slug"] )?>',
        results = $('#mc-search-results');

    $('#mc-search').click(function() {
        var keyword = $('#mc-search-title').val();

        if ( !keyword ) return alert('상품명을 입력해주세요.');

        $.getJSON('https://admin.movingcart.kr/urls/lists/' + slug + '.json?keyword=' + keyword + '&callback=?', function(items) {
            results.empty();

            $.each(items, function(idx, it) {
                results.append('<li><a data-hash="#/mvct/url/' + it.Url.id + '">' + it.Url.title_text + '</a></li>');
            });

            attachHashHandler();
        });

        return false;
    });

    function attachHashHandler() {
        $('#mc-search-results li a').click(function() {
            var ed = tinyMCE.activeEditor, e, b;
            var attrs = {href : $(this).data('hash')};

            e = ed.dom.getParent(ed.selection.getNode(), 'A');

            if (e == null) {
                ed.getDoc().execCommand("unlink", false, null);
                ed.execCommand("mceInsertLink", false, "#mce_temp_url#", {skip_undo : 1});

                tinymce.each(ed.dom.select("a"), function(n) {
                        if (ed.dom.getAttrib(n, 'href') == '#mce_temp_url#') {
                                e = n;
                                ed.dom.setAttribs(e, attrs);
                        }
                });

                // Sometimes WebKit lets a user create a link where
                // they shouldn't be able to. In this case, CreateLink
                // injects "#mce_temp_url#" into their content. Fix it.
                if ( tinymce.isWebKit && $(e).text() == '#mce_temp_url#' ) {
                        ed.dom.remove(e);
                        e = null;
                }
            } else {
                ed.dom.setAttribs(e, attrs);
            }

            // Move the caret if selection was not an image.
            if ( e && (e.childNodes.length != 1 || e.firstChild.nodeName != 'IMG') ) {
                    ed.selection.select(e);
                    ed.selection.collapse(0);
            }

            ed.execCommand("mceEndUndoLevel");
            ed.focus();
            tb_remove();
        });
    }
})
</script>