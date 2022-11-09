$(function(){

    var empId;
    var rowId;

    function SetEmployeeCode(element){

        param = {"Action":"GetApprovedList", "employee":$(element).attr('value')};

        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../overtime/overtime-approval-process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                $('#approvedList').remove();
                $('#approvedOvertimeList').append(data);
            },
            error: function (data){
                // console.log("error: "+ data);	
            }
        });//ajax
    }

    $(document).on('click','.btnApproved',function(e){

        var prid = this.id;
        var apvdOt = $(this).closest('tr').find("td:eq(4) input").val();
        var filot = $('#alertot'+prid).val();
        var upfilot = filot-apvdOt;
        param = {"Action":"ApproveOT",'rowid': this.id,'approvedot': apvdOt,'empId':empId};
        param = JSON.stringify(param);

        // console.log('old'+filot);
        // console.log('new'+otbut);
        // return false;

            swal({
              title: "Are you sure?",
              text: "You want to approve this overtime?",
              icon: "success",
              buttons: true,
              dangerMode: true,
            })
            .then((aprOt) => {
                document.getElementById("myDiv").style.display="block";
              if (aprOt) {
                    $.ajax({
                        type: "POST",
                        url: "../overtime/overtime-approval-process.php",
                        data: {data:param} ,
                        success: function (data){
                            // console.log("success: "+ data);
                            swal({
                            title: "Approved!", 
                            text: "Successfully approved overtime!", 
                            type: "success",
                            icon: "success",
                            }).then(function() {
                                document.getElementById("myDiv").style.display="none";
                                document.getElementById(empId).innerHTML = upfilot;
                                document.getElementById('alertot'+prid).value = upfilot;
                                document.querySelector('#clv'+prid).remove();
                                var otbut = document.getElementById('alertot'+prid).value;
                                if(otbut == 0){
                                   $('#'+empId).remove();
                                }else{

                                }    
                            });  
                        },
                        error: function (data){
                            console.log("error: "+ data);    
                        }
                    });//ajax

              } else {
                document.getElementById("myDiv").style.display="none";
                 swal({text:"You cancel the approval of overtime!",icon:"error"});
              }
            });

    });

    $(document).on('click','.btnFwd',function(e){

        var prid = this.id;
        var apvdOt = $(this).closest('tr').find("td:eq(4) input").val();
        var filot = $('#alertot').val();
        var upfilot = filot-apvdOt;
        var approver = $(this).closest('tr').find("td:eq(5) input").val();
        param = {"Action":"FwdOT",'rowid': this.id,'approvedot': apvdOt,'empId':empId ,'approver':approver};
        param = JSON.stringify(param);

        // console.log(param);
        // return false;

            swal({
              title: "Are you sure?",
              text: "You want to forward this overtime to Sir.Francis Calumba?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((fwdOt) => {
                document.getElementById("myDiv").style.display="block";
              if (fwdOt) {
                                $.ajax({
                                    type: "POST",
                                    url: "../overtime/overtime-approval-process.php",
                                    data: {data:param} ,
                                    success: function (data){
                                        swal({
                                        title: "Forwarded!", 
                                        text: "Successfully forwarded overtime!", 
                                        type: "success",
                                        icon: "success",
                                        }).then(function() {
                                            document.getElementById("myDiv").style.display="none";
                                            document.getElementById(empId).innerHTML = upfilot;
                                            document.getElementById('alertot').value = upfilot;
                                            document.querySelector('#clv'+prid).remove();
                                        });  
                                    },
                                    error: function (data){
                                        console.log("error: "+ data);    
                                    }
                                });//ajax

              } else {
                document.getElementById("myDiv").style.display="none";
                swal({text:"You cancel the forwarding of overtime!",icon:"error"});
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
        param = {"Action":"GetOTDetails",'empId': empId};
        param = JSON.stringify(param);
        
        $.ajax({
            type: "POST",
            url: "../overtime/overtime-approval-process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                $('#employeeOTDetailList').remove();
                $('#employeeOTDetailList_wrapper').remove();
                $('#otDetails').append(data);
                $('#employeeOTDetailList').DataTable({
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

        var prid = rowid;
        var apvdOt = document.getElementById('ac'+prid).value;
        var filot = $('#alertot').val();
        var upfilot = filot-apvdOt;
        param = {"Action":"RejectOT",'rowid': rowid,'empId':empId, "rjctRsn": $('#rejectReason').val()};
        param = JSON.stringify(param);

        if (CheckInput() === true) {
                        swal({
                          title: "Are you sure?",
                          text: "You want to reject this overtime?",
                          icon: "warning",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((rejPayroll) => {
                            document.getElementById("myDiv").style.display="block";
                          if (rejPayroll) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../overtime/overtime-approval-process.php",
                                        data: {data:param} ,
                                        success: function (data){
                                                    swal({
                                                    title: "Rejected!", 
                                                    text: "Successfully rejected overtime!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
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
                                                        document.getElementById(empId).innerHTML = upfilot;
                                                        document.getElementById('alertot').value = upfilot;
                                                        document.querySelector('#clv'+prid).remove();
                                                    }); 
                                        },
                                        error: function (data){
                                            // console.log("error: "+ data);    
                                        }
                                    });//ajax

                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel the rejection of overtime!",icon:"error"});
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
            url: "../overtime/overtime-approval-process.php",
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