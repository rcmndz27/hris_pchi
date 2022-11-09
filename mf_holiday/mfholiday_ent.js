$(function(){

    $('#temp_id').hide();

    $('#mfholidayEntry').click(function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');

    });


    $('#holidayterm').change(function(){

        var ht = $('#holidayterm').val();

        if(ht == 'Permanent'){
            $('#temp_id').hide();
            document.getElementById('expired_date').value = null;
        }else{
            $('#temp_id').show();
        }

    });



   
    function CheckInput(){
    
        var inputValues = [];
    
        inputValues = [
            $('#holidaydate'),
            $('#holidaytype'),
            $('#holidaydescs'),
            $('#holidayterm'),
            $('#status')
        ];


        var result = (CheckInputValue(inputValues) === '0') ? true : false;
        return result;
    }



    $('#Submit').click(function(){


        if (CheckInput() === true) {


   
            param = {
                'Action': 'InsertMfholidayEnt',
                'holidaydate': $('#holidaydate').val(),
                'holidaytype': $('#holidaytype').children("option:selected").val(),
                'holidaydescs': $('#holidaydescs').val(),
                'holidayterm': $('#holidayterm').val(),
                'expired_date': $('#expired_date').val(),
                'eMplogName': $('#eMplogName').val(),
                'status': $('#status').children("option:selected").val()                   
            }
    
            param = JSON.stringify(param);

            // console.log(param);
            // return false;

                     swal({
                          title: "Are you sure ?",
                          text: "You want to add this holiday.",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((appEnt) => {
                          if (appEnt) {
                                    $.ajax({
                                        type: 'POST',
                                        url: '../mf_holiday/mfholidayent_process.php',
                                        data: {
                                            data: param
                                        },
                                        success: function (result) {
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully added the holiday details!", 
                                            type: "success",
                                            icon: "success",
                                            }).then(function() {
                                                location.href = '../mf_holiday/mfholidaylist_view.php';
                                            }); 
                                        },
                                        error: function (result) {
                                            // console.log('error: ' + result);
                                        }
                                    }); //ajax
                          } else {
                            swal({text:"You cancel the addition of the holiday!",icon:"error"});
                          }
                        });

                }else{
                    swal({text:"Please fill-up all required (*) fields. ",icon:"error"});
                }
            });


        });







