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
            include("../mf_position/mfpositionlist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');
            $allMfpositionList = new MfpositionList(); 
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
<link rel="stylesheet" href="../mf_position/mfposition.css">
<script type="text/javascript" src="../mf_position/mfposition_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
    <div class="section-title">
          <h1>ALL JOB POSITION LIST</h1>
        </div>
    <div class="main-body mbt">
          <!-- Breadcrumb -->
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-users'>
                        </i>&nbsp;JOB POSITION LIST</b></li>
            </ol>
          </nav>
    <div class="pt-3">
        <div class="row align-items-end justify-content-end">
            <div class="col-md-12 mb-3">
                <button type="button" class="btn btn-secondary" id="mfpositionEntry"><i class="fas fa-plus-circle"></i> ADD NEW JOB POSITION </button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="panel-body">
                    <div id="tableList" class="table-responsive-sm table-body">
                        <?php $allMfpositionList->GetAllMfpositionList(); ?>

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
                    <h5 class="modal-title bb" id="popUpModalTitle">JOB POSITION ENTRY <i class="fas fa-users"></i></h5>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="position">Job Position Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="position"
                                            id="position" placeholder="Position Name....." > 
                                    </div>
                                </div>
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                    <label class="control-label" for="depname">Department Name<span class="req">*</span></label>
                                    <?php $dd->GenerateMultipledDropDown("department", $mf->GetDeptForJob("depwid")); ?>  
                                     </div>                                  
                                 </div>                                 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="status">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select inputtext" id="status" name="status" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
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

    <div class="modal fade" id="updateMfpos" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-sg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">UPDATE POSITION <i class="fas fa-users"></i></h5>
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
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="pstn">Job Position Name<span class="req">*</span></label>
                                        <input type="text" class="form-control inputtext" name="pstn" id="pstn" placeholder="Job Position"> 
                                    </div>
                                </div> 
                                 <div class="col-lg-12">
                                    <div class="form-group">
                                    <label class="control-label" for="depment">Department Name<span class="req">*</span></label>
                                    <?php $dd->GenerateMultipledDropDown("depment", $mf->GetDeptForJob("depwid")); ?>
                                    </div>                                    
                                 </div>   
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="control-label" for="stts">Status<span class="req">*</span></label>
                                        <select type="select" class="form-select" id="stts" name="stts" >
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>                                    
                                    </div>
                                </div> 
                              <form action=""></form>
                                <input type="text" class="form-control" name="dscsbup" id="dscsbup" hidden>
                                <input type="text" class="form-control" name="rowd" id="rowd" hidden> 
                        </div> <!-- form row closing -->
                    </fieldset> 

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                    <button type="button" class="btn btn-success" onclick="updateMfpos()" ><i class="fas fa-check-circle"></i> Submit</button>
                                </div> 
                        </div> <!-- main body closing -->
                    </div> <!-- modal body closing -->
                </div> <!-- modal content closing -->
            </div> <!-- modal dialog closing -->
        </div><!-- modal fade closing -->
        <input type="text" class="form-control" name="empCode" id="empCode" value="<?php echo $empCode; ?>" hidden>
    </div> <!-- main body mbt closing -->
</div><!-- container closing -->

        <?php 

        $query = "SELECT * from dbo.mf_position ORDER BY rowid asc";
        $stmt =$connL->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();

            $totalVal = [];
            do { 
                array_push($totalVal,$result['position']);
                
            } while ($result = $stmt->fetch());



        $queryd = "SELECT * from dbo.mf_jobdept";
        $stmtd =$connL->prepare($queryd);
        $stmtd->execute();
        $resultd = $stmtd->fetch();

        $totalVal2 = [];
        if($resultd){
            do { 
                array_push($totalVal2,$resultd);
                
            } while ($resultd = $stmtd->fetch());
        }



               

        $sqldepartments = $connL->prepare("SELECT rowid,descs,code FROM dbo.mf_dept where status = 'Active' ORDER by rowid ASC");
        $sqldepartments->execute();
        $departments = [];
        while ($r = $sqldepartments->fetch(PDO::FETCH_ASSOC))
        {
           array_push($departments, $r);
        }
    
        ?>


<script>

function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allMfpositionList");
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

                var totalVal3 = <?php echo json_encode($totalVal2) ;?>;
                var depart = <?php echo json_encode($departments) ;?>;
                console.log(depart);
                $('#position').change(function(){
                var totalVal = <?php echo json_encode($totalVal) ;?>;
                var cd = $('#position').val();

                if(totalVal.includes(cd)){
                    swal({text:"Duplicate Position Name!",icon:"error"});
                    var dbc = document.getElementById('position');
                    dbc.value = '';               
                }else{
                }

            });

                $('#pstn').change(function(){
                var totalVal = <?php echo json_encode($totalVal) ;?>;
                var cd = $('#pstn').val();
                var hidb = $('#dscsbup').val();

                if(totalVal.includes(cd)){
                        if(hidb === cd){

                        }else{
                            swal({text:"Duplicate Position Name!",icon:"error"});
                            var dbc = document.getElementById('pstn');
                            dbc.value = hidb;                            
                        }               
                }else{
                }

            });




    function editMfpositionModal(id,desc){

          
        $('#updateMfpos').modal('toggle');
        $("#depment").val([]);
        $.each(totalVal3, function(i){
            if(totalVal3[i]['job_id'] == id)
            {
                $("#depment option[value='" +totalVal3[i]['dept_id']+ "']").prop("selected", true);
            }
        });                          
        document.getElementById('rowd').value =  id;   
        document.getElementById('pstn').value =  document.getElementById('pst'+id).innerHTML;   
        document.getElementById('stts').value =  document.getElementById('st'+id).innerHTML;  
        document.getElementById('dscsbup').value =  desc;                                   

    }


     function updateMfpos()
    {

        var url = "../mf_position/updatemfposition_process.php";
        var rowid = document.getElementById("rowd").value;
        var position = document.getElementById("pstn").value; 
        var status = document.getElementById("stts").value; 
        var empCode = document.getElementById("empCode").value; 
        var department =  $('#depment').val();

                        swal({
                          title: "Are you sure?",
                          text: "You want to update this position type?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((updateMfpos) => {
                          if (updateMfpos) {
                                $.post (
                                    url,
                                    {
                                        rowid: rowid ,
                                        position: position ,
                                        status : status,
                                        department:department,
                                        empCode: empCode
                                        
                                    },
                                    function(data) { 
                                            swal({
                                            title: "Success!", 
                                            text: "Successfully updated the job position details!", 
                                            type: "success",
                                            icon: "success",
                                            }).then(function(e) {
                                                totalVal3 = totalVal3.filter(jobId => jobId.job_id != rowid);  
                                                    $.each(department, function(i){
                                                            var da = {};
                                                            da.job_id = rowid;
                                                            da.dept_id = department[i];
                                                            totalVal3.push(da);
                                                      });  
                                                    var dep = ""; 
                                                    var countDep = department.length;
                                                    var cDept = 0;
                                                    $.each(depart, function(i){

                                                       var dddd = department.includes(depart[i].rowid);
                                                       if(dddd == true)
                                                       {
                                                        cDept = cDept +1;
                                                        if(countDep == cDept)
                                                            {
                                                                dep = dep + depart[i].code;
                                                            }
                                                            else
                                                            {
                                                                dep = dep + depart[i].code+',';
                                                            }
                                                        }
                                                      });  
                                                    
                                                $('#updateMfpos').modal('hide');
                                                    document.getElementById('pst'+rowid).innerHTML = position;
                                                    document.getElementById('dptv'+rowid).innerHTML = dep; 
                                                    document.getElementById('st'+rowid).innerHTML = status;
                                            });
                                    }
                                );
                          } else {
                            swal({text:"You cancel the updating of job position details!",icon:"error"});
                          }
                        });
   
                }


getPagination('#allMfpositionList');

function getPagination(table) {
  var lastPage = 1;

  $('#maxRows')
    .on('change', function(evt) {
      //$('.paginationprev').html('');                      // reset pagination

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
