$(document).ready(function(){
    $('#pricing-charts-tabs a.tab').click(function (e) {
        e.preventDefault();
        var tabName = $(this).data('tab');
        $('#pricing-charts-tabs a.tab').removeClass('active');
        $(this).addClass('active');
        $('.pricing-charts .price').removeClass('active');
        $('.pricing-charts .price.'+tabName).addClass('active');
    });
});