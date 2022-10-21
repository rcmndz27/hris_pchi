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

        // console.log(param);
        // return false;

        $.ajax({
            type: "POST",
            url: "../leave/viewappleave_process.php",
            data: {data:param} ,
            success: function (data){
                document.getElementById("myDiv").style.display="none"; 
                $("#LeaveListTab").html(data);
                // XLSXExport();
                // $(".fa-file-export").remove();
                // $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });  


    function XLSXExportRep(){
        $("#LeaveListRepTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' Leaves'
        });
    }



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
                $("#LeaveListRepTab").html(data);
                XLSXExportRep();
                $(".fa-file-export").remove();
                $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
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
                $("#OtListTab").html(data);
                // XLSXExportOt();
                // $(".fa-file-export").remove();
                // $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });   

 function XLSXExportOtRep(){
        $("#OtListRepTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' OT'
        });
    }


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
                $("#OtListRepTab").html(data);
                XLSXExportOtRep();
                $(".fa-file-export").remove();
                $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });       

 function XLSXExportWfh(){
        $("#WfhListTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' Wfh'
        });
    }


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
                $("#WfhListTab").html(data);
                // XLSXExportWfh();
                // $(".fa-file-export").remove();
                // $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    

function XLSXExportWfhRep(){
        $("#WfhListRepTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' Wfh'
        });
    }


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
                $("#WfhListRepTab").html(data);
                XLSXExportWfhRep();
                $(".fa-file-export").remove();
                $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    


function XLSXExportOb(){
        $("#ObListTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' Ob'
        });
    }


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
                $("#ObListTab").html(data);
                // XLSXExportOb();
                // $(".fa-file-export").remove();
                // $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){              
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    

function XLSXExportObRep(){
        $("#ObListRepTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' Ob'
        });
    }


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
                $("#ObListRepTab").html(data);
                XLSXExportObRep();
                $(".fa-file-export").remove();
                $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){              
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    }); 

function XLSXExportDtrc(){
        $("#DtrcListTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' Dtrc'
        });
    }


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
                $("#DtrcListTab").html(data);
                // XLSXExportDtrc();
                // $(".fa-file-export").remove();
                // $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });    

function XLSXExportDtrcRep(){
        $("#DtrcListRepTab").tableExport({
            headers: true,
            footers: true,
            formats: ['xlsx'],
            filename: 'id',
            bootstrap: false,
            exportButtons: true,
            position: 'top',
            ignoreRows: null,
            ignoreCols: null,
            trimWhitespace: true,
            RTL: false,
            sheetname: ' Dtrc'
        });
    }


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
                $("#DtrcListRepTab").html(data);
                XLSXExportDtrcRep();
                $(".fa-file-export").remove();
                $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');
            },
            error: function (data){
                
                document.getElementById("myDiv").style.display="none"; 
            }
        });

    });                  

});