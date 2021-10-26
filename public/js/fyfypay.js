jQuery( function( $ ) {
  $(document).on('click', '.token-button', function() {
    const token = $(this).attr('data-token');
    $('select[name="fyfypay_payment_gateway_token"]').val(token);
    $('.token-button').removeClass('selected');
    $(this).addClass('selected');
  });
});
