/*
 * [customSwal 自定义倒计时弹框]
 * @param  {[type]} countdown [倒计时时间]
 * @param  {[type]} title     [标题]
 * @param  {[type]} text      [内容]
 * @return {[type]}           [description]
 */
function customSwal(countdown, title, text, callback)
{
    var confirmButtonText = countdown + 's 确定取消';
    var confirmButtonTextAfter = '　确定取消　';
    var timer = null;

    swal({
        title: title,
        text: text,
        type: "warning",
        allowOutsideClick: false,
        showConfirmButton: true,
        confirmButtonText: confirmButtonText,
        showCloseButton: true,
        showCancleButton: true,
        cancleButtonText: 'cancel',
        closeOnConfirm: false
    }, callback);

    $('.sweet-alert button.confirm')
    .attr('disabled', true)
    .css({
        'background': '#c2c2c2 !important',
        'cursor': 'default'
    });

    timer = setInterval(function()
    {
        countdown--;

        if (countdown <= 0)
        {
            $('.sweet-alert button.confirm').css({
                'background': '#1ab394 !important',
                'cursor': 'pointer'
            })
            .removeAttr('disabled')
            .text(confirmButtonTextAfter);;
            clearInterval(timer);
        }
        else
        {
            $('.sweet-alert button.confirm')
            .attr('disabled', true)
            .css({
                'background': '#c2c2c2 !important',
                'cursor': 'default'
            })
            .text(confirmButtonText.replace(/5/, countdown));
        }
    }, 1000);

    if ($('#close').length <= 0)
    {
        $('.sweet-alert').append('<span id="close">&times;</span>');
    }

    $('#close').click(function(){
        $('.sweet-alert, .sweet-overlay').hide();
        clearInterval(timer);
    });
}