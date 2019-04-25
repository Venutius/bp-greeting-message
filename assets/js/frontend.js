jQuery(document).ready(function (e) {
    var data = [
        [0, 4, "night"],
        [5, 11, "morning"],
        [12, 17, "afternoon"],
        [18, 22, "evening"],
        [23, 24, "night"]
    ];

    var hr = new Date().getHours();

    if (jQuery('.bpgm-message').length) {
        jQuery('.bpgm-message').hide();
    }

    for (var i = 0; i < data.length; i++) {
        if (hr >= data[i][0] && hr <= data[i][1]) {
            if (data[i][2] in bp_gm_values.greeting) {
                greeting_time = data[i][2];

                bpgm_message = '';
                bpgm_icon = '';

                if (bp_gm_values.greeting[greeting_time]['message'] != undefined) {
                    bpgm_message = bp_gm_values.greeting[greeting_time]['message'];
                }

                if (bp_gm_values.user_name != undefined) {
                    bpgm_message = bpgm_message + ' ' + bp_gm_values.user_name + ' !';
                }

                if (bp_gm_values.greeting[greeting_time]['icon'] != undefined) {
                    bpgm_icon = bp_gm_values.greeting[greeting_time]['icon'];
                }

                if (jQuery('.bpgm-message').length) {
                    jQuery('.bpgm-message').text(bpgm_message);
                }
                if (jQuery('.bpgm-right').length) {
                    jQuery('.bpgm-right').html(bpgm_icon);
                }
                if (jQuery('.bpgm-message').length) {
                    jQuery('.bpgm-message').show();
                }
            }
        }
    }
});

jQuery(window).load(function () {
    if (jQuery('.bpgm-custom-message').length) {
        if (jQuery('.bpgm-message').is(':empty') && jQuery('.bpgm-custom-message').is(':empty')) {
            jQuery('.bpgm-container').hide();
        }
    } else {
        if (jQuery('.bpgm-message').is(':empty')) {
            jQuery('.bpgm-container').hide();
        }
    }
});