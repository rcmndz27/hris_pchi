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
            include("../mf_pyrollco/mfpyrollcolist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfpyrollcoList = new MfpyrollcoList(); 
            $mf = new MasterFile();
            $dd = new DropDown();

       if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head' || $empUserType == 'President')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }

    }    
?>
<link rel="stylesheet" href="../mf_pyrollco/mfpyrollco.css">
<script type="text/javascript" src="../mf_pyrollco/mfpyrollco_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL PAYROLL CUTOFF LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-calendar-alt'>
                        </i>&nbsp;PAYROLL CUTOFF LIST</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-secondary" id="mfpyrollcoEntry"><i class="fas fa-plus-circle"></i> ADD NEW PAYROLL CUTOFF </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfpyrollcoList->GetAllMfpyrollcoList(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">PAYROLL CUTOFF ENTRY <i class="fas fa-calendar-alt"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
        <div class="modal-body">
            <div class="main-body">
                <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                </legend>
                             </div>
                        <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollco_from">Payroll From<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollco_from"
                                            id="pyrollco_from" onkeydown="return false"> 
                                    </div>
                                </div>                            
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollco_to">Payroll To<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollco_to"
                                            id="pyrollco_to" onkeydown="return false"> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="co_type">Payroll Type<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="co_type" name="co_type" >
                                            <option value="0">Payroll 15th</option>
                                            <option value="1">Payroll 30th</option>
                                        </select>    
                                    </div>
                                </div>
                                <input type="text" name="eMplogName" id="eMplogName" value="<?php echo $empName ?>" hidden>                                 
                            </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" id="Submit" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    <div class="modal fade" id="updateMfPyco" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE PAYROLL CUTOFF <i class="fas fa-calendar-alt"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
        <div class="modal-body">
            <div class="main-body">
                <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                </legend>
                             </div>
                        <div class="form-row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollcofrom">Payroll From<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollcofrom"
                                            id="pyrollcofrom" onkeydown="return false"> 
                                    </div>
                                </div>                            
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="pyrollco_to">Payroll To<span class="req">*</span></label>
                                        <input type="date" class="form-control inputtext" name="pyrollcoto"
                                            id="pyrollcoto" onkeydown="return false"> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="cotype">Payroll Type<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="cotype" name="cotype" >
                                            <option value="0">Payroll 15th</option>
                                            <option value="1">Payroll 30th</option>
                                        </select>    
                                    </div>
                                </div>                                                          
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="stts" name="stts" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
                                    </div>
                                </div>
                                <input type="text" class="form-control" name="rowd" id="rowd" hidden> 
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateMfPyco()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->
<script type="text/javascript">

           $('#pyrollco_to').change(function(){

                if($('#pyrollco_to').val() < $('#pyrollco_from').val()){

                    swal({text:"Date to must be greater than date from!",icon:"error"});
                    document.getElementById('pyrollco_to').value = '';               
                }
            });


            $('#pyrollco_from').change(function(){

                if($('#pyrollco_from').val() > $('#pyrollco_to').val()){
                    var input2 = document.getElementById('pyrollco_to');
                    document.getElementById("pyrollco_to").min = $('#pyrollco_from').val();
                    input2.value = '';
                }
            });



function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allMfpyrollcoList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}

    function editMfpyrollcoModal(id){
          
        $('#updateMfPyco').modal('toggle'); 
        document.getElementById('rowd').value =  id;   
        document.getElementById('pyrollcofrom').value =  document.getElementById('pcf'+id).innerHTML;   
        document.getElementById('pyrollcoto').value =  document.getElementById('pct'+id).innerHTML;  
        document.getElementById('cotype').value =  document.getElementById('cor'+id).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
    }


    function updateMfPyco()
    {

        var url = "../mf_pyrollco/updatemfpyrollco_process.php";
        var rowid = document.getElementById("rowd").value;
        var pyrollco_from = document.getElementById("pyrollcofrom").value;
        var pyrollco_to = document.getElementById("pyrollcoto").value;
        var co_type = document.getElementById("cotype").value;
        if(co_type == 1){
            var ctype =  'Payroll 30th';
        }else{
            var ctype =  'Payroll 15th';
        }
        var status = document.getElementById("stts").value;

        // console.log(pyrollco_from);
        // console.log(pyrollco_to);       
        // console.log(co_type);
        // console.log(status);
        // console.log(ctype);
        // return false;


                        swal({
                          title: "Are you sure?",
                          text: "You want to update this pyrollco type?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateMfPyco) => {
                          if (updateMfPyco) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        rowid: rowid ,
                                        pyrollco_from: pyrollco_from,
                                        pyrollco_to: pyrollco_to,
                                        co_type: co_type,
                                        status: status                                       
                                    },
                                    function(data) { 
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully updated the payroll cut-off details!", 
                                            type: "success",
                                            icon: "success",
                                            }).then(function() {
                                                $('#updateMfPyco').modal('hide');
                                                 document.getElementById('pcf'+rowid).innerHTML = pyrollco_from;
                                                 document.getElementById('pct'+rowid).innerHTML = pyrollco_to;
                                                 document.getElementById('cot'+rowid).innerHTML = ctype;
                                                 document.getElementById('st'+rowid).innerHTML = status;
                                            });  
                                });
                          } else {
                            swal({text:"You cancel the updating of payroll cut-off details!",icon:"error"});
                          }
                        });

                }
    

getPagination('#allMfpyrollcoList');

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
    .val(10)
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


<?php include("../_footer.php");?>
