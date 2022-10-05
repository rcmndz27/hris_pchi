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
            include("../mf_bank/banklist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allBankList = new BankList(); 
            $mf = new MasterFile();
            $dd = new DropDown();   

       if ($empUserType == 'Admin' || $empUserType == 'Finance' || $empUserType == 'Group Head' || $empUserType == 'President')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }  

    }    
?>
<link rel="stylesheet" href="../mf_bank/bank.css">
<script type="text/javascript" src="../mf_bank/bank_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL BANK TYPE LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-money-bill-wave fa-fw'>
                        </i>&nbsp;BANK TYPE LIST</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-secondary" id="bankEntry"><i class="fas fa-plus-circle"></i> ADD NEW BANK TYPE </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allBankList->GetAllBankList(); ?>

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
                    <h5 class="modal-title bb" id="popUpModalTitle">BANK TYPE ENTRY <i class="fas fa-money-bill"></i></h5>
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
                                 <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="bank_type">Bank Code<span class="req">*</span></label>
                                        <input type="text" style="text-transform:uppercase" class="form-control inputtext" name="descsb"
                                            id="descsb" placeholder="Bank Code..." maxlength="4">
                                    </div>
                                </div> 
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="control-label" for="bank_type">Bank Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="descsb_name"
                                            id="descsb_name" placeholder="Banco De Oro....." > 
                                    </div>
                                </div>                                                                                     
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

    <div class="modal fade" id="updateBan" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE BANK TYPE <i class="fas fa-money-bill"></i></h5>
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
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="dscsb">Bank Code<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" style="text-transform:uppercase" name="dscsb"
                                            id="dscsb" maxlength="4" placeholder="Bank Code...">
                                    </div>
                                </div> 
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <label class="control-label" for="descsbname">Bank Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="descsbname"
                                            id="descsbname"> 
                                    </div>
                                </div> 
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select" id="stts" name="stts" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
                                    </div>
                                </div>                                 
                                <input type="text" class="form-control" name="dscsbup" id="dscsbup" hidden> 
                                <input type="text" class="form-control" name="rowd" id="rowd" hidden> 

                        </div> <!-- form row closing -->
                    </fieldset> 
                                <div class="modal-footer">                                  
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateBan()" id="updateBanT"><i class="fas fa-check-circle"></i> Submit</button>                                      
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->

        <?php 
        $query = "SELECT * from dbo.mf_banktypes ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

            $totalVal = [];
            do { 
                array_push($totalVal,$result['descsb']);
                
            } while ($result = $stmt->fetch());

           ?>

    </div> <!-- main body mbt closing -->
</div><!-- container closing -->



<script>

            var totalVal = <?php echo json_encode($totalVal) ;?>;
            $('#descsb').change(function(){
                var cd = $('#descsb').val();
                var res = cd.toUpperCase();

                if(totalVal.includes(res)){
                    swal({text:"Duplicate Bank Code!",icon:"error"});
                    var dbc = document.getElementById('descsb');
                    dbc.value = '';               
                }else{
                }

            });

                $('#dscsb').change(function(){
                    var d = totalVal;
                    var rd = $('#rowd').val();
                    var cd = $('#dscsb').val();
                    var res = cd.toUpperCase();
                    var hidb = $('#dscsbup').val();

                console.log(d);

                if(d.includes(res)){
                        if(hidb === res){

                        }else{
                            swal({text:"Duplicate Bank Code!",icon:"error"});
                            var dbc = document.getElementById('dscsb');
                            dbc.value = hidb;                            
                        }               
                }else{
                }

            });

    function editBankModal(id,desc){
          
        $('#updateBan').modal('toggle');
        document.getElementById('rowd').value =  id;   
        document.getElementById('dscsb').value =  document.getElementById('bc'+id).innerHTML;   
        document.getElementById('descsbname').value =  document.getElementById('bn'+id).innerHTML;  
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
        document.getElementById('dscsbup').value =  desc;                                          
    }

            

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allBankList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}



     function updateBan()
    {

        var url = "../mf_bank/updatebank_process.php";
        var rowid = document.getElementById("rowd").value;
        var descsb = document.getElementById("dscsb").value.toUpperCase();
        var descsb_name = document.getElementById("descsbname").value;
        var status = document.getElementById("stts").value;     


                        swal({
                          title: "Are you sure?",
                          text: "You want to update this bank type?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateBan) => {
                          if (updateBan) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        rowid: rowid ,
                                        descsb: descsb ,
                                        descsb_name: descsb_name ,
                                        status: status 
                                        
                                    },
                                    function(data) {                                             
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully updated the bank details!", 
                                            type: "success",
                                            icon: "success",
                                        }).then(function() {
                                            $('#updateBan').modal('hide');
                                             document.getElementById('bc'+rowid).innerHTML = descsb;
                                             document.getElementById('bn'+rowid).innerHTML = descsb_name;
                                             document.getElementById('st'+rowid).innerHTML = status;
                                        }); 
                                    }
                                );

                          } else {
                            swal({text:"You cancel the updating of bank details!",icon:"error"});
                          }
                        });
   
                }
    
getPagination('#allBankList');

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
