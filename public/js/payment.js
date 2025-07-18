$(document).ready(function() {
    // Handle payment button click
    $('.pay-btn').click(function(e) {
        e.preventDefault();

        const tagihanId = $(this).data('tagihan-id');
        const amount = $(this).data('amount');
        const invoice = $(this).data('invoice');
        const paket = $(this).data('paket');

        // Show loading modal
        $('#loadingModal').modal('show');

        // Process payment
        processPayment(tagihanId);
    });

    function processPayment(tagihanId) {
        $.ajax({
            url: `/payment/process/${tagihanId}`,
            method: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#loadingModal').modal('hide');

                if (response.success) {
                    // Open Midtrans Snap
                    snap.pay(response.payment_data.snap_token, {
                        onSuccess: function(result) {
                            console.log('Payment Success:', result);
                            handlePaymentSuccess(result);
                        },
                        onPending: function(result) {
                            console.log('Payment Pending:', result);
                            handlePaymentPending(result);
                        },
                        onError: function(result) {
                            console.log('Payment Error:', result);
                            handlePaymentError(result);
                        },
                        onClose: function() {
                            console.log('Payment popup closed');
                            // Redirect to tagihan page
                            window.location.href = '/tagihan';
                        }
                    });
                } else {
                    showError(response.message);
                }
            },

        });
    }

    function handlePaymentSuccess(result) {
        // Redirect to finish URL with order_id
        const finishUrl = `/payment/finish?order_id=${result.order_id}`;
        window.location.href = finishUrl;
    }

    function handlePaymentPending(result) {
        // Redirect to pending URL with order_id
        const pendingUrl = `/payment/pending?order_id=${result.order_id}`;
        window.location.href = pendingUrl;
    }

    function handlePaymentError(result) {
        // Redirect to error URL with order_id
        const errorUrl = `/payment/error?order_id=${result.order_id}`;
        window.location.href = errorUrl;
    }

    function showError(message) {
        $('#errorMessage').text(message);
        $('#errorModal').modal('show');
    }
});