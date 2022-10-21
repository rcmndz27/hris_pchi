$(function(){

    $('#loansEntry').click(function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');

    });

    
    function CheckInput(){
    
        var inputValues = [];
    
        inputValues = [
            $('#emp_code'),
            $('#loandec_id'),
            $('#loan_date'),
            $('#loan_amount'),
            $('#loan_balance'),
            $('#loan_totpymt'),
            $('#loan_amort')    
        ];


        var result = (CheckInputValue(inputValues) === '0') ? true : false;
        return result;
    }



    $('#Submit').click(function(){

        if (CheckInput() === true) {
   
            param = {
                'Action': 'InsertLoansEnt',
                'emp_code': $('#emp_code').val(), 
                'loandec_id': $('#loandec_id').val(),
                'loan_date': $('#loan_date').val(),
                'loan_amount': $('#loan_amount').val(),
                'loan_balance': $('#loan_balance').val(),
                'loan_totpymt': $('#loan_totpymt').val(),
                'loan_amort': $('#loan_amort').val(),
                'eMplogName': $('#eMplogName').val()          
            }
    
            param = JSON.stringify(param);

            // console.log(param);
            // return false;

                     swal({
                          title: "Are you sure you want to add this employee loan details?",
                          text: "Please make sure all information are true and correct.",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((appEnt) => {
                          if (appEnt) {
                                    $.ajax({
                                        type: 'POST',
                                        url: '../loans/loansent_process.php',
                                        data: {
                                            data: param
                                        },
                                        success: function (result) {
                                            swal({
                                                title: "Success!", 
                                                text: "Successfully added the employee loans details!", 
                                                icon: "success",
                                            }).then(function() {
                                                location.href = '../loans/loanslist_view.php';
                                            });
                                        },
                                        error: function (result) {
                                            console.log('error: ' + result);
                                        }
                                    }); //ajax
                          } else {
                            swal({text:"You cancel the submission of your employee loan details!",icon:"error"});
                          }
                        });

                }else{
                    swal({text:"Please fill-up all required (*) fields. ",icon:"error"});
                }
            });


        });







