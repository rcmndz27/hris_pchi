$(function(){

    var empId;
    var rowId;

    $(document).on('click','.btnApproved',function(e){

        var prid = this.id;
        var apvdDtrc = 1;
        var fildtrc = $('#alertdtrc').val();
        var upfildtrc = fildtrc-apvdDtrc;
            
        param = {"Action":"ApproveDtrCorrect",'rowid':this.id,'empId':empId,};

        param = JSON.stringify(param);

                   swal({
                          title: "Are you sure?",
                          text: "You want to approve this dtr correction?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((approveDtrc) => {
                            document.getElementById("myDiv").style.display="block";
                          if (approveDtrc) {
                                      $.ajax({
                                            type: "POST",
                                            url: "../dtrcorrect/dtrcorrect-approval-process.php",
                                            data: {data:param} ,
                                            success: function (data){
                                                console.log("success: "+ data);
                                                    swal({
                                                    title: "Approved!", 
                                                    text: "Successfully approved dtr correction!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        document.getElementById("myDiv").style.display="none";
                                                        document.getElementById(empId).innerHTML = upfildtrc;
                                                        document.getElementById('alertdtrc').value = upfildtrc;
                                                        document.querySelector('#clv'+prid).remove();
                                                    }); 
                                            },
                                            error: function (data){
                                                // console.log("error: "+ data);    
                                            }
                                        });//ajax
                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel the approval of dtr correction!",icon:"error"});
                          }
                        });

                });



    $(document).on('click','.btn btn-primary btn-sm',function(e){

        var prid = this.id;
        var apvdDtrc = 1;
        var fildtrc = $('#alertdtrc').val();
        var upfildtrc = fildtrc-apvdDtrc;
        var approver = $(this).closest('tr').find("td:eq(5) input").val();
            
        param = {"Action":"FwdDtrCorrect",'rowid':this.id,'empId':empId,'approver':approver};

        param = JSON.stringify(param);

        // console.log(param);
        // return false;

                   swal({
                          title: "Are you sure?",
                          text: "You want to forwarded this dtr correction to Sir.Francis Calumba?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((approveDtrc) => {
                            document.getElementById("myDiv").style.display="block";
                          if (approveDtrc) {
                                      $.ajax({
                                            type: "POST",
                                            url: "../dtrcorrect/dtrcorrect-approval-process.php",
                                            data: {data:param} ,
                                            success: function (data){
                                                console.log("success: "+ data);
                                                document.getElementById("myDiv").style.display="none";
                                                    swal({
                                                    title: "Approved!", 
                                                    text: "Successfully forwarded dtr correction!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        document.getElementById(empId).innerHTML = upfildtrc;
                                                        document.getElementById('alertdtrc').value = upfildtrc;
                                                        document.querySelector('#clv'+prid).remove();
                                                    }); 
                                            },
                                            error: function (data){
                                                // console.log("error: "+ data);    
                                            }
                                        });//ajax
                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel the forwarding of dtr correction!",icon:"error"});
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

        param = {"Action":"GetDtrCorrectDetails",'empId': empId};

        param = JSON.stringify(param);
        
        $.ajax({
            type: "POST",
            url: "../dtrcorrect/dtrcorrect-approval-process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                $("#employeedtrcDetailList").remove();
                $("#dtrcDetails").append(data);
                // location.reload();
            },
            error: function (data){
                // console.log("error: "+ data);	
            }
        });//ajax

    });
    
    $('#submit').click(function(e){

        e.preventDefault();
        var apvdDtrc = 1;
        var fildtrc = $('#alertdtrc').val();
        var upfildtrc = fildtrc-apvdDtrc;
        param = {"Action":"RejectDtrCorrect",'rowid': rowid,'empId':empId, "rjctRsn": $('#rejectReason').val()};

        param = JSON.stringify(param);


                    swal({
                          title: "Are you sure?",
                          text: "You want to reject this dtr correction?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((rjcDtrc) => {
                            document.getElementById("myDiv").style.display="block";
                          if (rjcDtrc) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../dtrcorrect/dtrcorrect-approval-process.php",
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
                                                        document.getElementById(empId).innerHTML = upfildtrc;
                                                        document.getElementById('alertdtrc').value = upfildtrc;
                                                        document.querySelector('#clv'+rowid).remove();
                                        },
                                        error: function (data){
                                            // console.log("error: "+ data);    
                                        }
                                    });//ajax

                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel the approval of dtr correction!",icon:"error"});
                          }
                });

    });

    

});