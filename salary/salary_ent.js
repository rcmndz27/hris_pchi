$(function(){

    $('#salaryEntry').click(function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');

    });

   
    function CheckInput(){
    
        var inputValues = [];
    
        inputValues = [
            $('#emp_code'),
            $('#bank_type'),
            $('#pay_type'),
            $('#amount'),
            $('#status')           
        ];


        var result = (CheckInputValue(inputValues) === '0') ? true : false;
        return result;
    }



    $('#Submit').click(function(){

        if (CheckInput() === true) {
   
            param = {
                'Action': 'InsertSalaryEnt',
                'emp_code': $('#emp_code').val(),
                'bank_type': $( "#bank_type option:selected" ).text(),
                'bank_no': $('#bank_no').val(),
                'pay_rate': $("#pay_rate option:selected" ).text(),
                'amount': $('#amount').val(),
                'eMplogName': $('#eMplogName').val(),
                'status': $('#status').val()                    
            }
    
            param = JSON.stringify(param);

            // console.log(param);
            // return false;

                     swal({
                          title: "Are you sure you want to add this employee salary details?",
                          text: "Please make sure all information are true and correct.",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((appEnt) => {
                          if (appEnt) {
                                    $.ajax({
                                        type: 'POST',
                                        url: '../salary/salaryent_process.php',
                                        data: {
                                            data: param
                                        },
                                        success: function (result) {
                                            console.log('success: ' + result);
                                            swal({text:"Successfully added employee salary details!",icon:"success"});
                                            location.reload();
                                        },
                                        error: function (result) {
                                            console.log('error: ' + result);
                                        }
                                    }); //ajax
                          } else {
                            swal({text:"You cancel the submission of your employee salary details!",icon:"error"});
                          }
                        });

                }else{
                    swal({text:"Please fill-up all required (*) fields. ",icon:"error"});
                }
            });


        });







