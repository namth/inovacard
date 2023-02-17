function activateModal() {
    // initialize modal element
    var modalEl = document.getElementById('create_card_form').cloneNode(true);
    modalEl.style.margin = '30vh auto';
    modalEl.style.backgroundColor = '#fff';
    modalEl.style.display = 'block';
    modalEl.style.float = 'inherit';

    // show modal
    mui.overlay('on', modalEl);
}

function setClipboard(value) {
    var tempInput = document.createElement("input");
    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
    tempInput.value = value;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    return false;
}

jQuery(document).ready(function ($) {
    /* Xu ly khi bam vao nut copy */
    $('.file_item .copy').click(function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var copylink = $(this);
        setClipboard(url);
        copylink.html('<i class="fa fa-check-circle" aria-hidden="true"></i>');
        copylink.addClass('success_copied');

        /* Restore link */
        setTimeout(function(){
            copylink.html('<i class="fa fa-files-o" aria-hidden="true"></i>');
            copylink.removeClass('success_copied');
        }, 4000);
    });

    /* Xu ly khi bam vao nut xoa file */
    $('.delete').click(function(){
        var postid = $(this).data('postid');
        var fileid = $(this).data('fileid');
        var fields = $(this).data('fields');
        var file_item = $(this).parents().eq(1);
        console.log(file_item);

        $.ajax({
            type: "POST",
            url: AJAX.ajax_url,
            data: {
                action: "deleteAttachmentByID",
                fileid: fileid,
                postid: postid,
                fields: fields
            },
            beforeSend: function() {
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(xhr.responseText);
                console.log(thrownError);
            },
            success: function (resp) {
                // console.log(resp);
                if (resp) {
                    file_item.remove();
                }
            },
        });
    });

    // Target all classed with ".lined"
    $("textarea.html").linedtextarea();
});