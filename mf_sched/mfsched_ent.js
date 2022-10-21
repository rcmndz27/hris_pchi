$(function(){

    $('#mfschedEntry').click(function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');

    });

   
    // function CheckInput(){
    
    //     var inputValues = [];
    
    //     inputValues = [
    //         $('#sched_from'),
    //         $('#sched_to'),
    //         $('#co_type')
    //     ];


    //     var result = (CheckInputValue(inputValues) === '0') ? true : false;
    //     return result;
    // }



$(document).on('click','#sunCheck',function(e){
if(this.checked){
    $("#time_in_from_sun").prop('readonly', true);
    $("#time_in_to_sun").prop('readonly', true);
    $("#time_out_from_sun").prop('readonly', true);
    $("#time_out_to_sun").prop('readonly', true);
    $("#working_hours_sun").prop('readonly', true);
}else{
    $("#time_in_from_sun").prop('readonly', false);
    $("#time_in_to_sun").prop('readonly', false);
    $("#time_out_from_sun").prop('readonly', false);
    $("#time_out_to_sun").prop('readonly', false);
    $("#working_hours_sun").prop('readonly', false);
}
});

$(document).on('click','#monCheck',function(e){
if(this.checked){
    $("#time_in_from_mon").prop('readonly', true);
    $("#time_in_to_mon").prop('readonly', true);
    $("#time_out_from_mon").prop('readonly', true);
    $("#time_out_to_mon").prop('readonly', true);
    $("#working_hours_mon").prop('readonly', true);
    document.getElementById('time_in_from_mon').value = null;
    document.getElementById('time_in_to_mon').value = null;
    document.getElementById('time_out_from_mon').value = null;
    document.getElementById('time_out_to_mon').value = null;
    document.getElementById('working_hours_mon').value = null;
}else{
    $("#time_in_from_mon").prop('readonly', false);
    $("#time_in_to_mon").prop('readonly', false);
    $("#time_out_from_mon").prop('readonly', false);
    $("#time_out_to_mon").prop('readonly', false);
    $("#working_hours_mon").prop('readonly', false);
    document.getElementById('time_in_from_mon').value = document.getElementById('time_in_from_mon').defaultValue;
    document.getElementById('time_in_to_mon').value = document.getElementById('time_in_to_mon').defaultValue;
    document.getElementById('time_out_from_mon').value = document.getElementById('time_out_from_mon').defaultValue;
    document.getElementById('time_out_to_mon').value = document.getElementById('time_out_to_mon').defaultValue;
    document.getElementById('working_hours_mon').value = document.getElementById('working_hours_mon').defaultValue;
}
});       

$(document).on('click','#tueCheck',function(e){
if(this.checked){
    $("#time_in_from_tue").prop('readonly', true);
    $("#time_in_to_tue").prop('readonly', true);
    $("#time_out_from_tue").prop('readonly', true);
    $("#time_out_to_tue").prop('readonly', true);
    $("#working_hours_tue").prop('readonly', true);
    document.getElementById('time_in_from_tue').value = null;
    document.getElementById('time_in_to_tue').value = null;
    document.getElementById('time_out_from_tue').value = null;
    document.getElementById('time_out_to_tue').value = null;
    document.getElementById('working_hours_tue').value = null;
}else{
    $("#time_in_from_tue").prop('readonly', false);
    $("#time_in_to_tue").prop('readonly', false);
    $("#time_out_from_tue").prop('readonly', false);
    $("#time_out_to_tue").prop('readonly', false);
    $("#working_hours_tue").prop('readonly', false);
    document.getElementById('time_in_from_tue').value = document.getElementById('time_in_from_tue').defaultValue;
    document.getElementById('time_in_to_tue').value = document.getElementById('time_in_to_tue').defaultValue;
    document.getElementById('time_out_from_tue').value = document.getElementById('time_out_from_tue').defaultValue;
    document.getElementById('time_out_to_tue').value = document.getElementById('time_out_to_tue').defaultValue;
    document.getElementById('working_hours_tue').value = document.getElementById('working_hours_tue').defaultValue;
}
}); 

$(document).on('click','#wedCheck',function(e){
if(this.checked){
    $("#time_in_from_wed").prop('readonly', true);
    $("#time_in_to_wed").prop('readonly', true);
    $("#time_out_from_wed").prop('readonly', true);
    $("#time_out_to_wed").prop('readonly', true);
    $("#working_hours_wed").prop('readonly', true);
    document.getElementById('time_in_from_wed').value = null;
    document.getElementById('time_in_to_wed').value = null;
    document.getElementById('time_out_from_wed').value = null;
    document.getElementById('time_out_to_wed').value = null;
    document.getElementById('working_hours_wed').value = null;
}else{
    $("#time_in_from_wed").prop('readonly', false);
    $("#time_in_to_wed").prop('readonly', false);
    $("#time_out_from_wed").prop('readonly', false);
    $("#time_out_to_wed").prop('readonly', false);
    $("#working_hours_wed").prop('readonly', false);
    document.getElementById('time_in_from_wed').value = document.getElementById('time_in_from_wed').defaultValue;
    document.getElementById('time_in_to_wed').value = document.getElementById('time_in_to_wed').defaultValue;
    document.getElementById('time_out_from_wed').value = document.getElementById('time_out_from_wed').defaultValue;
    document.getElementById('time_out_to_wed').value = document.getElementById('time_out_to_wed').defaultValue;
    document.getElementById('working_hours_wed').value = document.getElementById('working_hours_wed').defaultValue;
}
});   


$(document).on('click','#thuCheck',function(e){
if(this.checked){
    $("#time_in_from_thu").prop('readonly', true);
    $("#time_in_to_thu").prop('readonly', true);
    $("#time_out_from_thu").prop('readonly', true);
    $("#time_out_to_thu").prop('readonly', true);
    $("#working_hours_thu").prop('readonly', true);
    document.getElementById('time_in_from_thu').value = null;
    document.getElementById('time_in_to_thu').value = null;
    document.getElementById('time_out_from_thu').value = null;
    document.getElementById('time_out_to_thu').value = null;
    document.getElementById('working_hours_thu').value = null;
}else{
    $("#time_in_from_thu").prop('readonly', false);
    $("#time_in_to_thu").prop('readonly', false);
    $("#time_out_from_thu").prop('readonly', false);
    $("#time_out_to_thu").prop('readonly', false);
    $("#working_hours_thu").prop('readonly', false);
    document.getElementById('time_in_from_thu').value = document.getElementById('time_in_from_thu').defaultValue;
    document.getElementById('time_in_to_thu').value = document.getElementById('time_in_to_thu').defaultValue;
    document.getElementById('time_out_from_thu').value = document.getElementById('time_out_from_thu').defaultValue;
    document.getElementById('time_out_to_thu').value = document.getElementById('time_out_to_thu').defaultValue;
    document.getElementById('working_hours_thu').value = document.getElementById('working_hours_thu').defaultValue;
}
});  

$(document).on('click','#friCheck',function(e){
if(this.checked){
    $("#time_in_from_fri").prop('readonly', true);
    $("#time_in_to_fri").prop('readonly', true);
    $("#time_out_from_fri").prop('readonly', true);
    $("#time_out_to_fri").prop('readonly', true);
    $("#working_hours_fri").prop('readonly', true);
    document.getElementById('time_in_from_fri').value = null;
    document.getElementById('time_in_to_fri').value = null;
    document.getElementById('time_out_from_fri').value = null;
    document.getElementById('time_out_to_fri').value = null;
    document.getElementById('working_hours_fri').value = null;
}else{
    $("#time_in_from_fri").prop('readonly', false);
    $("#time_in_to_fri").prop('readonly', false);
    $("#time_out_from_fri").prop('readonly', false);
    $("#time_out_to_fri").prop('readonly', false);
    $("#working_hours_fri").prop('readonly', false);
    document.getElementById('time_in_from_fri').value = document.getElementById('time_in_from_fri').defaultValue;
    document.getElementById('time_in_to_fri').value = document.getElementById('time_in_to_fri').defaultValue;
    document.getElementById('time_out_from_fri').value = document.getElementById('time_out_from_fri').defaultValue;
    document.getElementById('time_out_to_fri').value = document.getElementById('time_out_to_fri').defaultValue;
    document.getElementById('working_hours_fri').value = document.getElementById('working_hours_fri').defaultValue;
}
});  


$(document).on('click','#satCheck',function(e){
if(this.checked){
    $("#time_in_from_sat").prop('readonly', true);
    $("#time_in_to_sat").prop('readonly', true);
    $("#time_out_from_sat").prop('readonly', true);
    $("#time_out_to_sat").prop('readonly', true);
    $("#working_hours_sat").prop('readonly', true);
}else{
    $("#time_in_from_sat").prop('readonly', false);
    $("#time_in_to_sat").prop('readonly', false);
    $("#time_out_from_sat").prop('readonly', false);
    $("#time_out_to_sat").prop('readonly', false);
    $("#working_hours_sat").prop('readonly', false);
}
});    


$('#Submit').click(function(){


param = {
    'Action': 'InsertMfschedEnt',
    'schedule_name': $('#schedule_name').val(),
    'time_in_from_sun': $('#time_in_from_sun').val(),
    'time_in_to_sun': $('#time_in_to_sun').val(),
    'time_out_from_sun': $('#time_out_from_sun').val(),
    'time_out_to_sun': $('#time_out_to_sun').val(),
    'working_hours_sun': $('#working_hours_sun').val(),
    'time_in_from_mon': $('#time_in_from_mon').val(),
    'time_in_to_mon': $('#time_in_to_mon').val(),
    'time_out_from_mon': $('#time_out_from_mon').val(),
    'time_out_to_mon': $('#time_out_to_mon').val(),
    'working_hours_mon': $('#working_hours_mon').val(),
    'time_in_from_tue': $('#time_in_from_tue').val(),
    'time_in_to_tue': $('#time_in_to_tue').val(),
    'time_out_from_tue': $('#time_out_from_tue').val(),
    'time_out_to_tue': $('#time_out_to_tue').val(),
    'working_hours_tue': $('#working_hours_tue').val(),
    'time_in_from_wed': $('#time_in_from_wed').val(),
    'time_in_to_wed': $('#time_in_to_wed').val(),
    'time_out_from_wed': $('#time_out_from_wed').val(),
    'time_out_to_wed': $('#time_out_to_wed').val(),
    'working_hours_wed': $('#working_hours_wed').val(),
    'time_in_from_thu': $('#time_in_from_thu').val(),
    'time_in_to_thu': $('#time_in_to_thu').val(),
    'time_out_from_thu': $('#time_out_from_thu').val(),
    'time_out_to_thu': $('#time_out_to_thu').val(),
    'working_hours_thu': $('#working_hours_thu').val(),
    'time_in_from_fri': $('#time_in_from_fri').val(),
    'time_in_to_fri': $('#time_in_to_fri').val(),
    'time_out_from_fri': $('#time_out_from_fri').val(),
    'time_out_to_fri': $('#time_out_to_fri').val(),
    'working_hours_fri': $('#working_hours_fri').val(),
    'time_in_from_sat': $('#time_in_from_sat').val(),
    'time_in_to_sat': $('#time_in_to_sat').val(),
    'time_out_from_sat': $('#time_out_from_sat').val(),
    'time_out_to_sat': $('#time_out_to_sat').val(),
    'working_hours_sat': $('#working_hours_sat').val(),
    'empcode': $('#empcode').val()                             
}

param = JSON.stringify(param);

// console.log(param);
// return false;

         swal({
              title: "Are you sure ?",
              text: "You want to add this schedule.",
              icon: "success",
              buttons: true,
              dangerMode: true,
            })
            .then((appEnt) => {
              if (appEnt) {
                    $.ajax({
                        type: 'POST',
                        url: '../mf_sched/mfschedent_process.php',
                        data: {
                            data: param
                        },
                        success: function (result) {
                             console.log('success: ' + result);
                            // swal({
                            // title: "Success!", 
                            // text: "Successfully added new schedule!", 
                            // type: "success",
                            // icon: "success",
                            // }).then(function() {
                            //     location.href = '../mf_sched/mfschedlist_view.php';
                            // }); 
                        },
                        error: function (result) {
                            console.log('error: ' + result);
                        }
                    }); 
              } else {
                swal({text:"You cancel the addition of new schedule",icon:"error"});
              }
        });

});


});







