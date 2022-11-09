$(function(){

    var empId;
    var rowId;

    function SetEmployeeCode(element){

        param = {"Action":"GetApprovedList", "employee":$(element).attr('value')};

        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../ob/ob-approval-process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                $('#approvedList').remove();
                $('#approvedObList').append(data);
            },
            error: function (data){
                // console.log("error: "+ data);	
            }
        });//ajax
    }

    $(document).on('click','.btnApproved',function(e){

        var prid = this.id;
        var apvdOb = 1;
        var filob = $('#alertob').val();
        var upfilob = filob-apvdOb;

        param = {"Action":"ApproveOB",'rowid': this.id,'approvedob': apvdOb,'empId':empId};

        param = JSON.stringify(param);

                        swal({
                          title: "Are you sure?",
                          text: "You want to approve this official business?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((savePayroll) => {
                            document.getElementById("myDiv").style.display="block";
                          if (savePayroll) {
                                            $.ajax({
                                                type: "POST",
                                                url: "../ob/ob-approval-process.php",
                                                data: {data:param} ,
                                                success: function (data){
                                                    console.log("success: "+ data);
                                                    swal({
                                                    title: "Approved!", 
                                                    text: "Successfully approved official business!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        document.getElementById("myDiv").style.display="none";
                                                            document.getElementById(empId).innerHTML = upfilob;
                                                            document.getElementById('alertob').value = upfilob;
                                                            document.querySelector('#clv'+prid).remove();
                                                    }); 
                                                },
                                                error: function (data){
                                                    // console.log("error: "+ data);    
                                                }
                                            });//ajax

                          } else {
                            document.getElementById("myDiv").style.display="none";
                             swal({text:"You cancel the approval of official business!",icon:"error"});

                          }
                        });


      

    });

    $(document).on('click','.btnFwd',function(e){

        var prid = this.id;
        var apvdOb = 1;
        var filob = $('#alertob').val();
        var upfilob = filob-apvdOb;
        var approver = $(this).closest('tr').find("td:eq(6) input").val();

        param = {"Action":"FwdOb",'rowid': this.id,'approvedob': apvdOb,'empId':empId,'approver':approver};

        param = JSON.stringify(param);

        // console.log(param);
        // return false;

                        swal({
                          title: "Are you sure?",
                          text: "You want to approve this official business?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((savePayroll) => {
                            document.getElementById("myDiv").style.display="block";
                          if (savePayroll) {
                                            $.ajax({
                                                type: "POST",
                                                url: "../ob/ob-approval-process.php",
                                                data: {data:param} ,
                                                success: function (data){
                                                    console.log("success: "+ data);
                                                    swal({
                                                    title: "Approved!", 
                                                    text: "Successfully approved official business!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        document.getElementById("myDiv").style.display="none";
                                                            document.getElementById(empId).innerHTML = upfilob;
                                                            document.getElementById('alertob').value = upfilob;
                                                            document.querySelector('#clv'+prid).remove();
                                                    }); 
                                                },
                                                error: function (data){
                                                    // console.log("error: "+ data);    
                                                }
                                            });//ajax

                          } else {
                            document.getElementById("myDiv").style.display="none";
                             swal({text:"You cancel the approval of official business!",icon:"error"});

                          }
                        });


      

    });

    $(document).on('click','.btnRejectd',function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');
        rowid = this.id;
    });

    $(document).on('click','.btnPending',function(e){

        empId = this.id;

        param = {"Action":"GetOBDetails",'empId': empId};

        param = JSON.stringify(param);
        
        $.ajax({
            type: "POST",
            url: "../ob/ob-approval-process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                $('#employeeOBDetailList').remove();
                $('#employeeOBDetailList_wrapper').remove();
                $('#obDetails').append(data);
                $('#employeeOBDetailList').DataTable({
                    pageLength : 5,
                    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
                });                
                
                // location.reload();
            },
            error: function (data){
                // console.log("error: "+ data);	
            }
        });//ajax

    });


    
    function CheckInput() {

        var inputValues = [];

        inputValues = [
            
            $('#rejectReason')
            
        ];

        var result = (CheckInputValue(inputValues) === '0') ? true : false;
        return result;
    }


    
    $('#submit').click(function(e){
        e.preventDefault();


        var apvdOb = 1;
        var filob = $('#alertob').val();
        var upfilob = filob-apvdOb;

        param = {"Action":"RejectOB",'rowid': rowid,'empId':empId, "rjctRsn": $('#rejectReason').val()};

        param = JSON.stringify(param);

        if (CheckInput() === true) {

                        swal({
                          title: "Are you sure?",
                          text: "You want to reject this official business?",
                          icon: "error",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((rejOb) => {
                            document.getElementById("myDiv").style.display="block";
                          if (rejOb) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../ob/ob-approval-process.php",
                                        data: {data:param} ,
                                        success: function (data){
                                            console.log("success: "+ data);
                                            document.getElementById("myDiv").style.display="none";
                                            $('#popUpModal').modal('hide');
                                            $('#popUpModal').on('hidden.bs.modal', function (e) {
                                              $(this)
                                                .find("input,textarea,select")
                                                   .val('')
                                                   .end()
                                                .find("input[type=checkbox], input[type=radio]")
                                                   .prop("checked", "")
                                                   .end();
                                            })
                                            document.getElementById(empId).innerHTML = upfilob;
                                            document.getElementById('alertob').value = upfilob;
                                            document.querySelector('#clv'+rowid).remove();
                                        },
                                        error: function (data){
                                            // console.log("error: "+ data);    
                                        }
                                    });//ajax

                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel the rejection of official business!",icon:"error"});
                          }
                        });

                    } else{
                        swal({text:"Kindly fill up blank fields!",icon:"error"});
                    }
                                


    });

    $('#search').click(function(e){
        e.preventDefault();

        param = {"Action":"GetEmployeeList", "employee":$('#employeeSearch').val()};

        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../ob/ob-approval-process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                $('#empList').remove();
                $('#list-box').append(data);

                $("#empList li").bind("click",function(){
                    SetEmployeeCode(this);
                    $('#empList').remove();
                });
            },
            error: function (data){
                // console.log("error: "+ data);	
            }
        });//ajax
    });

    

});