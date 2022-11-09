<?php
    session_start();

    if (empty($_SESSION['userid']))
    {
        include_once('../loginfirst.php');
        exit();
    }
    else
    {
        include('../_header.php');
        include('../payroll/payroll_reg_emp.php');
        include('../elements/DropDown.php');
        include('../controller/MasterFile.php');
        $empCode = $_SESSION['userid'];
        $empInfo->SetEmployeeInformation($_SESSION['userid']);
        $empUserType = $empInfo->GetEmployeeUserType();
        $empInfo = new EmployeeInformation();
        $mf = new MasterFile();
        $dd = new DropDown();
        $payrollEmpApplication = new PayrollRegEmpApplication();

            if($empUserType == 'Admin' || $empUserType == 'Group Head' || $empUserType == 'Finance' || $empUserType == 'Finance2') {

            }else{
                        echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
                        echo "window.location.href = '../index.php';";
                        echo "</script>";
            }
    }
        
?>
<link rel="stylesheet" type="text/css" href="../payroll/payroll_reg.css">
<script type='text/javascript' src='../payroll/payroll_reg.js'></script>
<script src="<?= constant('NODE'); ?>xlsx/dist/xlsx.core.min.js"></script>
<script src="<?= constant('NODE'); ?>file-saverjs/FileSaver.min.js"></script>
<div class="container">
    <div class="section-title">
          <h1><br></h1>
        </div>
    <div class="main-body mbt">

          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active font-weight-bold" aria-current="page"><i class='fas fa-money-check fa-fw mr-1'>
                        </i>Payroll Register per Employee View</li>
            </ol>
          </nav>

      <div class="form-row">
        <label for="payroll_period" class="col-form-label pad">Payroll Period:</label>
        <div class='col-md-3'>
            <select class="form-control" id="empCode" name="empCode" value="" hidden>
                <option value="<?php echo $empCode ?>"><?php echo $empCode ?></option>
            </select>
           <?php $dd->GenerateDropDown("ddcutoff", $mf->GetAllPayCutoffReg("paycutreg")); ?>
        </div>   
        <div class='col-md-2'>
            <?php $dd->GenerateSingleGenDropDown("addempnames", $mf->GetAttEmployeeNamesCode("allempcode")); ?> 
        </div>        
        <button type="button" id="search" class="btn btn-danger mr-1 mb-1 text-white" onmousedown="javascript:deletePayReg()">
            <i class="fas fa-backspace mr-1 text-white"></i> Delete                      
        </button>
          <button class='btn btn-success mb-1' onclick='ConfirmPayRegView()'>
            <i class='fas fa-check-square'></i> 
          Confirm Payroll Register
          </button>                                              
        </div>
            <div class="row">
                <div class="col-md-12">
                    <select class="form-control" id="empCode" name="empCode" value="" hidden>
                        <option value="<?php echo $empCode ?>"><?php echo $empCode ?></option>
                    </select>
                        <button type="button" id="search" hidden>Generate</button>
                            <?php $payrollEmpApplication->GetPayrollRegEmpList()?>
                </div>
            </div>
    </div>
</div>
<script>

    var a = document.getElementById("ddcutoff").value;
    var b = document.getElementById("search");
    if (a == null || a == "") {
      document.getElementById('ddcutoff').disabled = true
      document.getElementById("search").disabled = true;
      b.style.display = 'none';
    }else{

    }


jQuery(function(){
   jQuery('#search').click();
   $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i> ');
});

function deletePayReg()
{
 
  $(".fa-file-export").remove();
  $(".btn btn-primary").prepend('<i class="fas fa-file-export"></i>');    
  var empCode = $('#empCode').children("option:selected").val();
  var url = "../payroll/payrollRegViewProcess.php";
  var cutoff = $('#ddcutoff').children("option:selected").val();
  var dates = cutoff.split(" - ");  
  var emp_code = $('#addempnames').val();


  // console.log(dates[0]);
  // console.log(dates[1]);
  // console.log(empCode);
  // console.log(emp_code);
  // return false;

  swal({
    title: "Are you sure?",
    text: "You want to delete this generated payroll?",
    icon: "info",
    buttons: true,
    dangerMode: true,
  })
  .then((delPayGen) => {
    if (delPayGen) {
      
      $.post (
        url,
        {
          choice: 3,
          empCode:empCode,
          date_from: dates[0],
          date_to: dates[1],
          emp_code:emp_code

        },
        function(data) {
          // console.log(data);
          swal({
            title: "Success!", 
            text: "Successfully deleted generated payroll!", 
            type: "success",
            icon: "success",
          }).then(function() {
            location.href = '../payroll/payroll_view_register_emp.php';
          });                                             

        }
        );
    } else {
      
      swal({text:"You cancel the deletion of generated payroll!",icon:"error"});
    }
  });         
}


function ConfirmPayRegView()
{
 
  var empCode = $('#empCode').children("option:selected").val();
  var cutoff = $('#ddcutoff').children("option:selected").val();
  var dates = cutoff.split(" - ");    
  var url = "../payroll/payrollRegViewProcess.php";
  var emp_code = $('#addempnames').val();

  swal({
    title: "Are you sure?",
    text: "You want to confirm this payroll register?",
    icon: "info",
    buttons: true,
    dangerMode: true,
  })
  .then((savePayroll) => {
    if (savePayroll) {
      
      $.post (
        url,
        {
          choice: 4,
          date_from: dates[0],
          date_to: dates[1],
          emp_code: empCode,
          badgeno : emp_code

        },
        function(data) {
          console.log("success: "+ data);
          swal({
            title: "Success!", 
            text: "Successfully confirmed payroll register details!", 
            type: "success",
            icon: "success",
          }).then(function() {
            location.href = '../payroll/payroll_view_register_emp.php';
          });                                             

        }
        );
    } else {
      
      swal({text:"You cancel the confirmation of payroll register!",icon:"error"});
    }
  });         
}

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("payrollRegList");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
     if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
      || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
      || td[4].innerHTML.toUpperCase().indexOf(filter) > -1 || td[5].innerHTML.toUpperCase().indexOf(filter) > -1 
      || td[6].innerHTML.toUpperCase().indexOf(filter) > -1  || td[7].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
       tr[i].style.display = "";
   } else {
     tr[i].style.display = "none";
   }

 }
}
}
    
getPagination('#payrollRegList');

function getPagination(table) {
  var lastPage = 1;

  $('#maxRows')
    .on('change', function(evt) {
      //$('.paginationprev').html('');  
      // reset pagination

     lastPage = 1;
      $('.pagination')
        .find('li')
        .slice(1, -1)
        .remove();
      var trnum = 0; // reset tr counter
      var maxRows = parseInt($(this).val()); // get Max Rows from select option

      if (maxRows == 5000) {
        $('.pagination').hide();
      } else {
        $('.pagination').show();
      }

      var totalRows = $(table + ' tbody tr').length; // numbers of rows
      $(table + ' tr:gt(0)').each(function() {
        // each TR in  table and not the header
        trnum++; // Start Counter
        if (trnum > maxRows) {
          // if tr number gt maxRows

          $(this).hide(); // fade it out
        }
        if (trnum <= maxRows) {
          $(this).show();
        } // else fade in Important in case if it ..
      }); //  was fade out to fade it in
      if (totalRows > maxRows) {
        // if tr total rows gt max rows option
        var pagenum = Math.ceil(totalRows / maxRows); // ceil total(rows/maxrows) to get ..
        //  numbers of pages
        for (var i = 1; i <= pagenum; ) {
          // for each page append pagination li
          $('.pagination #prev')
            .before(
              '<li data-page="' +
                i +
                '">\
                                  <span>' +
                i++ +
                '<span class="sr-only">(current)</span></span>\
                                </li>'
            )
            .show();
        } // end for i
      } // end if row count > max rows
      $('.pagination [data-page="1"]').addClass('active'); // add active class to the first li
      $('.pagination li').on('click', function(evt) {
        // on click each page
        evt.stopImmediatePropagation();
        evt.preventDefault();
        var pageNum = $(this).attr('data-page'); // get it's number

        var maxRows = parseInt($('#maxRows').val()); // get Max Rows from select option

        if (pageNum == 'prev') {
          if (lastPage == 1) {
            return;
          }
          pageNum = --lastPage;
        }
        if (pageNum == 'next') {
          if (lastPage == $('.pagination li').length - 2) {
            return;
          }
          pageNum = ++lastPage;
        }

        lastPage = pageNum;
        var trIndex = 0; // reset tr counter
        $('.pagination li').removeClass('active'); // remove active class from all li
        $('.pagination [data-page="' + lastPage + '"]').addClass('active'); // add active class to the clicked
        // $(this).addClass('active');                  // add active class to the clicked
        limitPagging();
        $(table + ' tr:gt(0)').each(function() {
          // each tr in table not the header
          trIndex++; // tr index counter
          // if tr index gt maxRows*pageNum or lt maxRows*pageNum-maxRows fade if out
          if (
            trIndex > maxRows * pageNum ||
            trIndex <= maxRows * pageNum - maxRows
          ) {
            $(this).hide();
          } else {
            $(this).show();
          } //else fade in
        }); // end of for each tr in table
      }); // end of on click pagination list
      limitPagging();
    })
    .val(5000)
    .change();

  // end of on select change

  // END OF PAGINATION
}

function limitPagging(){
    // alert($('.pagination li').length)

    if($('.pagination li').length > 7 ){
            if( $('.pagination li.active').attr('data-page') <= 3 ){
            $('.pagination li:gt(5)').hide();
            $('.pagination li:lt(5)').show();
            $('.pagination [data-page="next"]').show();
        }if ($('.pagination li.active').attr('data-page') > 3){
            $('.pagination li:gt(0)').hide();
            $('.pagination [data-page="next"]').show();
            for( let i = ( parseInt($('.pagination li.active').attr('data-page'))  -2 )  ; i <= ( parseInt($('.pagination li.active').attr('data-page'))  + 2 ) ; i++ ){
                $('.pagination [data-page="'+i+'"]').show();

            }

        }
    }
}
    
</script>
<?php include('../_footer.php');  ?>
