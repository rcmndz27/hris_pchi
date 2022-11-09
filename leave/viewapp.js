$(function(){


    $("#searchLeaveApp").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var status = $('#status').val();
 
        param = {
          Action: "GetAppLeave",
          date_from: df,
          date_to: dt,
          status : status
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                document.getElementById("myDiv").style.display="none"; 
                $("#LeaveListTabs").html(data);
                $('#LeaveListTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                }); 
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });  


    $("#searchLeaveRep").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";


        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val()
        var empCode = 'PCH'+$('#empCode').val();
 
        param = {
          Action: "GetRepLeave",
          date_from: df,
          date_to: dt,
          empCode : empCode
        };
        
        param = JSON.stringify(param);

        // console.log(param);
        // return false;

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                document.getElementById("myDiv").style.display="none"; 
                $("#LeaveListRepTabs").html(data);
                $('#LeaveListRepTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                }); 
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });  


   $("#searchOtApp").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var status = $('#status').val();
 
        param = {
          Action: "GetAppOt",
          date_from: df,
          date_to: dt,
          status : status
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#OtListTabs").html(data);
                $('#OtListTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                }); 
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });   


    $("#searchOtRep").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var empCode = 'PCH'+$('#empCode').val();
 
        param = {
          Action: "GetRepOt",
          date_from: df,
          date_to: dt,
          empCode : empCode
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#OtListRepTabs").html(data);
                $('#OtListRepTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                }); 
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });       

    $("#searchWfhApp").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var status = $('#status').val();
 
        param = {
          Action: "GetAppWfh",
          date_from: df,
          date_to: dt,
          status : status
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#WfhListTabs").html(data);
                $('#WfhListTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                }); 
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    


    $("#searchWfhRep").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var empCode = 'PCH'+$('#empCode').val();
 
        param = {
          Action: "GetRepWfh",
          date_from: df,
          date_to: dt,
          empCode: empCode
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#WfhListRepTabs").html(data);
                $('#WfhListRepTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                }); 
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    




    $("#searchObApp").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var status = $('#status').val();
 
        param = {
          Action: "GetAppOb",
          date_from: df,
          date_to: dt,
          status:status
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#ObListTabs").html(data);
                $('#ObListTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                });
            },
            error: function (data){              
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    


    $("#searchObRep").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var empCode = 'PCH'+$('#empCode').val();
 
        param = {
          Action: "GetRepOb",
          date_from: df,
          date_to: dt,
          empCode : empCode
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#ObListRepTabs").html(data);
                $('#ObListRepTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                });
            },
            error: function (data){              
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    }); 



    $("#searchDtrcApp").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var status = $('#status').val();
 
        param = {
          Action: "GetAppDtrc",
          date_from: df,
          date_to: dt,
          status : status
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#DtrcListTabs").html(data);
                $('#DtrcListTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                });
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    



    $("#searchDtrcRep").click(function(e){

        e.preventDefault();
        document.getElementById("myDiv").style.display="block";

        var df = $('#dateFrom').val();
        var dt = $('#dateTo').val();
        var empCode = 'PCH'+$('#empCode').val();
 
        param = {
          Action: "GetRepDtrc",
          date_from: df,
          date_to: dt,
          empCode : empCode
        };
        
        param = JSON.stringify(param);

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                // console.log("success: "+ data);
                document.getElementById("myDiv").style.display="none"; 
                $("#DtrcListRepTabs").html(data);
                $('#DtrcListRepTab').DataTable({
                    pageLength : 12,
                    lengthMenu: [[12, 24, 36, -1], [12, 24, 36, 'All']],
                    dom: 'Bfrtip',
                    buttons: [
                        'pageLength',
                        {
                            extend: 'excel',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/excel.png" title="Export to Excel">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent btn-sm'
                        },
                        {
                            extend: 'pdf',
                            title: 'Leave List', 
                            text: '<img class="btnExcel" src="../img/expdf.png" title="Export to PDF">',
                            init: function(api, node, config) {
                                $(node).removeClass('dt-button')
                                },
                                className: 'btn bg-transparent'
                        }
                    ] ,
                    "bPaginate": true,
                    "bLengthChange": false,
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false                       
                });
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });                  

});