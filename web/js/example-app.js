$(function(){
    $('#add-user').click(function(){
        $('#add-user-window').show();
    });

    $('.add-balance').click(function(){
        let userID = $(this).attr('attr-user');
        $('#examplereport-user').val(userID);
        $('#add-balance-window').show();
    });

    $('.add-window-close').click(function(){
        $('.modal').hide();
    });

    $('#btn-filter-clear').click(function(){
        //$('.form-control').val('');
        $('#filter-send-type').val('filter-clear')
        $(this).parents('form').submit();
    });
   
    $('.toggle').click(function(){
        let changeStatusTo;
        let iserID = $(this).find('input').attr('attr-user');
        let balanceButton = $('.add-balance[attr-user="'+ iserID +'"]');
        if($(this).hasClass('off')){
            changeStatusTo = 'active';
            buttonVisibility = 'visible';
        }else{
            changeStatusTo = 'blocked';
            buttonVisibility = 'hidden';
        }
      
        $.ajax({
            url: 'index.php?r=example-app/changestatus',
            data: {
                send_type: 'change-status',
                new_status: changeStatusTo,
                user_id: iserID
            },
            type: 'POST',
            success: function(result){
                if(result == 1){
                   balanceButton.css('visibility',  buttonVisibility);
                }else console.log('Ошибка записи в базу');
            },
            error: function(jqXHR, status){
                console.log(status);
            }
        });
    });

    $('.btn-del-report').click(function(){
        let reportID = $(this).attr('attr-report-id');

        $.ajax({
            url: 'index.php?r=example-app/a',
            data: {
                send_type: 'del-report',
                report_id: reportID
            },
            type: 'POST',
            success: function(result){
                console.log(result);
                if(result == 1){
                   location.reload();
                }else console.log('Ошибка записи в базу');
            },
            error: function(jqXHR, status, a){
                console.log(status);
                console.log(a);
            }
        });
    });
})
    