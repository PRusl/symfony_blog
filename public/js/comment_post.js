$(document).ready(function() {
    $('#comment_form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            method: 'POST',
            url: $(e.currentTarget).attr('action'),
            data: {
                author: $('.form_comment_author').val(),
                content : $('.form_comment_content').val(),
            },
        }).done(function(data) {
            var $newRow = $('.comment_row:first').clone();
            $newRow.children('.comment_author').html(data.author);
            $newRow.children('.comment_created').html(data.created_at);
            $newRow.children('.comment_content').html(data.content);
            $newRow.appendTo('.comment_list_body');

            $('.form_comment_content').val("");
        });
    });
});