$(function(){

    $(document).on('focusin', '.js-url-src', function(){
        $(this).data('val', $(this).val());
    }).on('change', '.js-url-src', function(){
        var prev = slugify($(this).data('val'));
        var curr = slugify($(this).val());

        $dst = $('.js-url-dst');

        var slug = $dst.val();
        if (!slug || slug.length == 0 || slug == prev) {
            $dst.val(curr);
        }
    });

});