$(function () {

    $('#table-patients')
        .on('click', '.assign-patient', function () {
            var url = $(this).attr('data-url');
            var name = $(this).attr('data-name');
            bootbox.confirm("You are adding patient <strong>"+name+"</strong> to your list. <br>Please Confirm.", function (result) {
                $.get(url, function(){
                    window.location.reload(true);
                });
            });
            return false;
        })
        .on('click', '.unassign-patient', function () {
            var url = $(this).attr('data-url');
            var name = $(this).attr('data-name');
            bootbox.confirm("You are removing patient <strong>"+name+"</strong> from your list. <br>Please Confirm.", function (result) {
                $.get(url, function(){
                    window.location.reload(true);
                });
            });
            return false;
        })
        .on('click', '.reassign-patient', function () {
            var url = $(this).attr('data-url');
            var name = $(this).attr('data-name');
            bootbox.confirm("Patient <strong>"+name+"</strong> is in another Doctor list. You are reassigning him to your own list. <br>Please Confirm", function (result) {
                $.get(url, function(){
                    window.location.reload(true);
                });
            });
            return false;
        });

});