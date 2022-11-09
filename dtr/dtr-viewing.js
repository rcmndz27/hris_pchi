$(function(){

$('#search').click(function(e){
    e.preventDefault();
    var empc = $('#empCode').val();
    document.getElementById("myDiv").style.display="block";
    if($('#empCode').val()== '' || $('#dateTo').val()== '' ){
            swal({text:"Kindly fill up blank fields!",icon:"warning"});
       }else{    
            param = {
                "Action":"GetEmployeeAttendannce",
                "dateFrom":$('#dateFrom').val(),
                "dateTo":$('#dateTo').val(),
                "empCodeParam":$('#empCode').val()
            };
            
            param = JSON.stringify(param);
            $.ajax({
                type: "POST",
                url: "../dtr/dtr-viewing-process.php",
                data: {data:param} ,
                success: function (data){
                    // console.log("success: "+ data);
                    $('#empDtrList').remove();      
                    $('#empDtrList_wrapper').remove();
                    $('#dtrViewList').append(data);                  
                    $('#empDtrList').DataTable({
                        pageLength : 12,
                        lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                        dom: 'Bfrtip',
                        buttons: [
                            'pageLength',
                            {
                                extend: 'excel',
                                title: 'Attendance'+empc,
                                text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                                init: function(api, node, config) {
                                    $(node).removeClass('dt-button')
                                 },
                                 className: 'btn bg-transparent btn-sm'
                            },
                            {
                                extend: 'pdf',
                                title: 'Attendance'+empc,
                                text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                                init: function(api, node, config) {
                                    $(node).removeClass('dt-button')
                                 },
                                 className: 'btn bg-transparent'
                            }
                        ]                        
                    });  
                    document.getElementById("myDiv").style.display="none";
                },
                error: function (data){
                    document.getElementById("myDiv").style.display="none";
                    // console.log("error: "+ data);	
                }
            });//ajax
          }

    });
    
});