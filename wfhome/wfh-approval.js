$(function(){

    var empId;
    var rowId;
   

    function SetEmployeeCode(element){

        param = {"Action":"GetApprovedList", "employee":$(element).attr('value')};

        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../wfhome/wfh-approval-process.php",
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
        var apvdWfh = 1;
        var filwfh = $('#alertwfh').val();
        var upfilwfh = filwfh-apvdWfh;

        param = {"Action":"ApproveWfh",'rowid':this.id,'empId':empId,};

        param = JSON.stringify(param);

                   swal({
                          title: "Are you sure?",
                          text: "You want to approve this work from home?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((approveWfh) => {
                            document.getElementById("myDiv").style.display="block";
                          if (approveWfh) {
                                      $.ajax({
                                            type: "POST",
                                            url: "../wfhome/wfh-approval-process.php",
                                            data: {data:param} ,
                                            success: function (data){
                                                console.log("success: "+ data);
                                                    swal({
                                                    title: "Approved!", 
                                                    text: "Successfully approved work from home!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        document.getElementById("myDiv").style.display="none";
                                                        document.getElementById(empId).innerHTML = upfilwfh;
                                                        document.getElementById('alertwfh').value = upfilwfh;
                                                        document.querySelector('#clv'+prid).remove();
                                                    }); 
                                            },
                                            error: function (data){
                                                // console.log("error: "+ data);    
                                            }
                                        });//ajax
                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel the approval of work from home!",icon:"error"});
                          }
                        });

                });

    $(document).on('click','.btnFwd',function(e){

        var prid = this.id;
        var apvdWfh = 1;
        var filwfh = $('#alertwfh').val();
        var upfilwfh = filwfh-apvdWfh;
        var approver = $(this).closest('tr').find("td:eq(5) input").val();

        param = {"Action":"FwdWfh",'rowid':this.id,'empId':empId,'approver':approver};

        param = JSON.stringify(param);

        // console.log(param);
        // return false;

                   swal({
                          title: "Are you sure?",
                          text: "You want to forward this work from home to Sir. Francis Calumba?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((fwdWfh) => {
                        document.getElementById("myDiv").style.display="block";
                          if (fwdWfh) {
                                      $.ajax({
                                            type: "POST",
                                            url: "../wfhome/wfh-approval-process.php",
                                            data: {data:param} ,
                                            success: function (data){
                                                console.log("success: "+ data);
                                                document.getElementById("myDiv").style.display="none";
                                                    swal({
                                                    title: "Forwarded!", 
                                                    text: "Successfully forwarded work from home!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        document.getElementById("myDiv").style.display="none";
                                                        document.getElementById(empId).innerHTML = upfilwfh;
                                                        document.getElementById('alertwfh').value = upfilwfh;
                                                        document.querySelector('#clv'+prid).remove();
                                                    }); 
                                            },
                                            error: function (data){
                                                // console.log("error: "+ data);    
                                            }
                                        });//ajax
                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel the forwarding of work from home!",icon:"error"});
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

        param = {"Action":"GetWfhDetails",'empId': empId};

        param = JSON.stringify(param);
        
        $.ajax({
            type: "POST",
            url: "../wfhome/wfh-approval-process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                $('#employeeWfhDetailList').remove();
                $('#employeeWfhDetailList_wrapper').remove();
                $('#wfhDetails').append(data);
                $('#employeeWfhDetailList').DataTable({
                    pageLength : 5,
                    lengthMenu: [[5, 10, 20, -1], [5, 10, 20, 'All']]
                });

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

        var apvdWfh = 1;
        var filwfh = $('#alertwfh').val();
        var upfilwfh = filwfh-apvdWfh;

        param = {"Action":"RejectWfh",'rowid': rowid,'empId':empId, "rjctRsn": $('#rejectReason').val()};

        param = JSON.stringify(param);

                
            if (CheckInput() === true) {

                    swal({
                          title: "Are you sure?",
                          text: "You want to reject this work from home?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((approveWfh) => {
                            document.getElementById("myDiv").style.display="block";
                          if (approveWfh) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../wfhome/wfh-approval-process.php",
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
                                                        document.getElementById(empId).innerHTML = upfilwfh;
                                                        document.getElementById('alertwfh').value = upfilwfh;
                                                        document.querySelector('#clv'+rowid).remove();
                                        },
                                        error: function (data){
                                            // console.log("error: "+ data);    
                                        }
                                    });//ajax

                    

                                } else {
                                    document.getElementById("myDiv").style.display="none";
                                    swal({text:"You cancel the approval of work from home!",icon:"error"});
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
            url: "../wfhome/wfh-approval-process.php",
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