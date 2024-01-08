jQuery(document).ready(function ($) {
    // Initialize the datepicker
    $(".datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: '+1D',

    });
    $(document.body).on('change', '.datepicker', function (e) {

        var selectedText = $(this).val();
        var selectedDate = new Date(selectedText);
        var now = new Date();
        if (selectedDate < now) {
            alert("Date must be in the future");
            //$(this).val(now.format('yyyy-mm-dd'));
        }

    });
    // Add a click event listener to the next payment button
    $(document.body).on('click', '.next_payment', function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();
        // Show the popup when the button is clicked
        MicroModal.init();
        MicroModal.show('wc_sub_box_next_payment_popup', {
            onShow: function () {
                $(".datepicker").datepicker({
                    dateFormat: 'yy-mm-dd',
                    minDate: '+1D',
                });
            }
        });
    });

    $(document.body).on('click', '.confirm-next-payment', function (e) {

        e.preventDefault();
        e.stopImmediatePropagation();

        // Get the selected date
        var selectedDate = $("#datepicker").val();
        var selectedDateObject = new Date(selectedDate);
        var now = new Date();
        if (selectedDateObject < now) {
            alert("Date must be in the future");
           // $(this).val(now.format('yyyy-mm-dd'));
        } else {
            //Show loader before making the AJAX call
            show_loader();
            // Perform AJAX request to update the next payment date
            $.ajax({
                type: 'POST',
                url: next_payment_object.ajax_url,
                data: {
                    'action': 'handle_confirm_next_payment',
                    'nonce': next_payment_object.nonce,
                    'subscription_id': $('#wc_sub_box_next_payment_popup').attr('data-subscription_id'),
                    'selected_date': $("#datepicker").val(),
                },
                beforeSend: function () {
                    // This function will be called before the AJAX request is sent
                    show_loader();
                },
                success: function (response) {
                    location.reload();
                },
                error: function (error) {
                    // Hide loader on error
                    hide_loader();
                    console.error('Error:', error);
                },
            });
        }
    });

    function show_loader() {
        // Show the loader by setting its display property to 'block'
        $('.wc-sub-box-extra-action-loader-wrapper').show();
    }

    // Function to hide the loader
    function hide_loader() {
        // Hide the loader by setting its display property to 'none'
        $('.wc-sub-box-extra-action-loader-wrapper').hide();
    }
});
