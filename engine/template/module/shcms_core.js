//������ ��� ��������� ����������
$(document).ready(function() {
 var wbbOpt = {
  lang: "ru"
 }
 $('#editor').wysibb({
        smileList: 
        [
            {title:CURLANG.sm1, img: '<img src="/engine/template/smilies/2.gif" class="sm">', bbcode:":)"},
            {title:CURLANG.sm8, img: '<img src="/engine/template/smilies/3.gif" class="sm">', bbcode:":("},
            {title:CURLANG.sm1, img: '<img src="/engine/template/smilies/1.gif" class="sm">', bbcode:":D"},
            {title:CURLANG.sm3, img: '<img src="/engine/template/smilies/6.gif" class="sm">', bbcode:":so:"},
            {title:CURLANG.sm4, img: '<img src="/engine/template/smilies/11.gif" class="sm">', bbcode:":P"},
            {title:CURLANG.sm5, img: '<img src="/engine/template/smilies/9.gif" class="sm">', bbcode:":ir:"},
            {title:CURLANG.sm6, img: '<img src="/engine/template/smilies/12.gif" class="sm">', bbcode:":cry:"},
            {title:CURLANG.sm7, img: '<img src="/engine/template/smilies/13.gif" class="sm">', bbcode:":rage:"},
            {title:CURLANG.sm9, img: '<img src="/engine/template/smilies/23.gif" class="sm">', bbcode:":think:"},
            {title:CURLANG.sm10, img: '<img src="/engine/template/smilies/14.gif" class="sm">', bbcode:":B"},
            {title:CURLANG.sm11, img: '<img src="/engine/template/smilies/25.gif" class="sm">', bbcode:":roul:"},
            {title:CURLANG.sm12, img: '<img src="/engine/template/smilies/27.gif" class="sm">', bbcode:":o"},
            {title:CURLANG.sm13, img: '<img src="/engine/template/smilies/31.gif" class="sm">', bbcode:":appl:"},
            {title:CURLANG.sm14, img: '<img src="/engine/template/smilies/32.gif" class="sm">', bbcode:":idnk:"},
            {title:CURLANG.sm15, img: '<img src="/engine/template/smilies/45.gif" class="sm">', bbcode:":E"},
            {title:CURLANG.sm16, img: '<img src="/engine/template/smilies/37.gif" class="sm">', bbcode:":alc:"}		
			
			
        ]
    });
 $('#editor2').wysibb({
        smileList: 
        [
            {title:CURLANG.sm1, img: '<img src="/engine/template/smilies/2.gif" class="sm">', bbcode:":)"},
            {title:CURLANG.sm8, img: '<img src="/engine/template/smilies/3.gif" class="sm">', bbcode:":("},
            {title:CURLANG.sm1, img: '<img src="/engine/template/smilies/1.gif" class="sm">', bbcode:":D"},
            {title:CURLANG.sm3, img: '<img src="/engine/template/smilies/6.gif" class="sm">', bbcode:":so:"},
            {title:CURLANG.sm4, img: '<img src="/engine/template/smilies/11.gif" class="sm">', bbcode:":P"},
            {title:CURLANG.sm5, img: '<img src="/engine/template/smilies/9.gif" class="sm">', bbcode:":ir:"},
            {title:CURLANG.sm6, img: '<img src="/engine/template/smilies/12.gif" class="sm">', bbcode:":cry:"},
            {title:CURLANG.sm7, img: '<img src="/engine/template/smilies/13.gif" class="sm">', bbcode:":rage:"},
            {title:CURLANG.sm9, img: '<img src="/engine/template/smilies/23.gif" class="sm">', bbcode:":think:"},
            {title:CURLANG.sm10, img: '<img src="/engine/template/smilies/14.gif" class="sm">', bbcode:":B"},
            {title:CURLANG.sm11, img: '<img src="/engine/template/smilies/25.gif" class="sm">', bbcode:":roul:"},
            {title:CURLANG.sm12, img: '<img src="/engine/template/smilies/27.gif" class="sm">', bbcode:":o"},
            {title:CURLANG.sm13, img: '<img src="/engine/template/smilies/31.gif" class="sm">', bbcode:":appl:"},
            {title:CURLANG.sm14, img: '<img src="/engine/template/smilies/32.gif" class="sm">', bbcode:":idnk:"},
            {title:CURLANG.sm15, img: '<img src="/engine/template/smilies/45.gif" class="sm">', bbcode:":E"},
            {title:CURLANG.sm16, img: '<img src="/engine/template/smilies/37.gif" class="sm">', bbcode:":alc:"}		
			
			
        ]
    });	
});

$(".reveal").mousedown(function() {
    $(".pwd").replaceWith($('.pwd').clone().attr('type', 'text'));
})
.mouseup(function() {
	$(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
})
.mouseout(function() {
	$(".pwd").replaceWith($('.pwd').clone().attr('type', 'password'));
});


(function ($) {
    $.fn.extend({
        limiter: function (minLimit, maxLimit, elem) {
            $(this).on("keydown keyup focus keypress", function (e) {
                setCount(this, elem, e);
            });

            function setCount(src, elem, e) {
                var chars = src.value.length;
                if (chars == maxLimit) {
                    //e.preventDefault();
                     elem.html(maxLimit - chars);
                    elem.addClass('maxLimit');
                    return false;
                     
                } else if (chars > maxLimit) {
                    src.value = src.value.substr(0, maxLimit);
                    chars = maxLimit;
                    elem.addClass('maxLimit');
                } else {
                    elem.removeClass('maxLimit');
                }
                if (chars < minLimit) {
                    elem.addClass('minLimit');
                } else {
                    elem.removeClass('minLimit');
                }
                elem.html(maxLimit - chars);
            }
            setCount($(this)[0], elem);
        }
    });
})(jQuery);



var elem1 = $("#chars_login");
var elem2 = $("#chars_t");
$("#login_r").limiter(3, 26, elem1);
$("#editor").limiter(35, 100, elem2);
