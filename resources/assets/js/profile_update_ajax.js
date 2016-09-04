$('#update_profile_button').on('click', function() {
    $.ajax({
        url: 'mgmt/profile',
        type: 'POST',
        data: {
            _token: $('#_token').val(),
            bio: $('#user_bio').val(),
        },
        success: function(data, stat, xhr) {
            console.log(resp);
        },
    });
});