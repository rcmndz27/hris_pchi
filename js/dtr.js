$(function(){


    $("#search").click(function(e){

         e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var dfrom = $("#dateFrom").val();
        var dto = $("#dateTo").val();

        if($('#dateTo').val()== '' ){

            swal({text:"Kindly fill up blank fields!",icon:"warning"});
            document.getElementById("myDiv").style.display="none";

       }else{   

        param = {
          Action: "GetAttendanceList",

          datefrom: $("#dateFrom").val(),
          dateto: $("#dateTo").val(),
          empcode: $("#empCode").val(),
        };
        
        param = JSON.stringify(param);
        
        $.ajax({
            type: "POST",
            url: "../controller/dtrProcess.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                    $('#dtrList').remove();
                    $('#dtrList_wrapper').remove();
                    $('#dtrViewList').append(data);
                    $('#dtrList').DataTable({
                        pageLength : 12,
                        lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                        dom: 'Bfrtip',
                        buttons: [
                            'pageLength',
                            {
                                extend: 'excel',
                                title: 'My Attendance from '+dfrom+' to '+dto,
                                text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                                init: function(api, node, config) {
                                    $(node).removeClass('dt-button')
                                 },
                                 className: 'btn bg-transparent btn-sm'
                            },
                            {
                                extend: 'pdf',
                                title: 'My Attendance from '+dfrom+' to '+dto,
                                text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                                init: function(api, node, config) {
                                    $(node).removeClass('dt-button')
                                 },
                                 className: 'btn bg-transparent'
                            }
                        ]                        
                    });                    
                // XLSXExport();
                document.getElementById("myDiv").style.display="none";
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none";
                // console.log("error: "+ data);	
            }
        });

        }

    });   
    
    

});