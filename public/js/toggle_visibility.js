$(document).ready(function() {
    $('.toggle-switch').change(function() {
        var id = $(this).attr('id').replace('customSwitches', '');
        var isVisible = $(this).prop('checked');
        var url = $(this).data('url');
        $.ajax({
            url: url,
            type: 'POST',
            data: {id: id, isVisible: isVisible},
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    });
});