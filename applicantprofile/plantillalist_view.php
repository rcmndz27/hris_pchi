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
            include("../applicantprofile/plantillalist.php");
            include('../elements/DropDown.php');
            include('../controller/MasterFile.php');

            $mf = new MasterFile();
            $dd = new DropDown();
            $allPlaList = new PlantillaList(); 
            
       if ($empUserType == 'Admin' || $empUserType == 'HR Generalist' ||$empUserType == 'HR Manager' || $empUserType == 'Group Head')
        {
  
        }else{
            echo '<script type="text/javascript">swal({text:"You do not have access here!",icon:"error"});';
            echo "window.location.href = '../index.php';";
            echo "</script>";
        }

    }    
?>
<link rel="stylesheet" type="text/css" href="../applicantprofile/app_view.css">
<script type="text/javascript" src="../applicantprofile/plantilla_ent.js"></script>
<script type='text/javascript' src='../js/validator.js'></script>
<div class="container">
        <div class="section-title">
          <h1>PLANTILLA LIST</h1>
        </div>
<div class="main-body mbt">
          <nav aria-label="breadcrumb" class="main-breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item active bb" aria-current="page"><b><i class='fas fa-warehouse  fa-fw'>
                        </i>&nbsp;PLANTILLA LIST</b></li>
            </ol>
          </nav>
            <div class="pt-3">
                <div class="row align-items-end justify-content-end">
                    <div class="col-md-12 mb-3">
                        <button type="button" class="btn btn-secondary" id="plantillaEntry"><i class="fas fa-plus-circle"></i> ADD NEW PLANTILLA </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="panel-body">
                            <div id="tableList" class="table-responsive-sm table-body">
                                <?php $allPlaList->GetAllPlantillaList(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <div class="modal fade" id="popUpModal" tabindex="-1" role="dialog" aria-labelledby="informationModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title bb" id="popUpModalTitle">PLANTILLA ENTRY  <i class="fas fa-plus-circle"></i></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times; </span>
                    </button>
                </div>
            <div class="modal-body">
                <div class="main-body mbt">
                      <fieldset class="fieldset-border">
                            <div class="d-flex justify-content-center">
                                <legend class="fieldset-border pad">
                                </legend>
                             </div>
                            <div class="form-row">
                                    <div class="form-group">
                                        <input type="text" class="form-control inputtext" name="entry_date"
                                            id="entry_date" placeholder="entry date" value="<?php echo date("Y/m/d"); ?>" hidden>    
                                    </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="control-label" for="Department">Department</label>
                                        <?php $dd->GenerateDropDown("department", $mf->GetAllDepartment("alldep")); ?> 
                                    </div>
                                </div>  
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Position</label>
                                        <?php $dd->GenerateDropDown("position", $mf->GetJobPosition("jobpos")); ?> 
                                    </div>
                                </div> 
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label class="control-label" for="presentAddress">Reporting To</label>
                                        <?php $dd->GenerateDropDown("reporting_to", $mf->GetJobPosition("jobpos")); ?> 
                                    </div>
                                </div>
                                <input type="text" class="form-control inputtext" name="status"
                                            id="status" placeholder="status" value="Open" hidden>    
                                                                 
                            </div>  
                        </fieldset>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
                                <button type="button" class="btn btn-success" id="Submit" ><i class="fas fa-check-circle"></i> Submit</button>
                            </div>                                 
                        </div>
                    </div>    
                    
                    </div>
            </div>
        </div>
    </div>

</div>
</div>


<script>

function activatePlant(data)
{       

var url = "../applicantprofile/activate_plaent_process.php";
var status = 'Active';
var rowid = data;

        swal({
          title: "Are you sure?",
          text: "You want to activate this plantilla?",
          icon: "success",
          buttons: true,
          dangerMode: true,
        })
    .then((actPlaent) => {
      if (actPlaent) {
            $.post (
                url,
                {
                    action: 1,
                    status: status ,
                    rowid: rowid                
                },
                function(data) { 
                    swal({
                    title: "Success!", 
                    text: "Successfully activated the plantilla details!", 
                    type: "success",
                    icon: "success",
                    }).then(function(e) {
                        document.getElementById('st'+rowid).innerHTML = 'Active';
                        document.getElementById('act'+rowid).innerHTML = '<button type="button" class="debtn btn-info" onclick="deactivatePlant('+rowid+')"><i class="fas fa-times-circle"></i> DE-ACTIVATE</button>';
                    }); 
                    }   
                );
      } else {
        swal({text:"You cancel the activating of plantilla!",icon:"error"});
      }
    });

}

    function deactivatePlant(data)
    {
 
        var url = "../applicantprofile/activate_plaent_process.php";
        var status = 'De-Activated';
        var rowid = data;


                        swal({
                          title: "Are you sure?",
                          text: "You want to de-activate this plantilla?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((deactPlaent) => {
                          if (deactPlaent) {
                                $.post (
                                    url,
                                    {
                                        action: 1,
                                        status: status ,
                                        rowid: rowid                
                                    },
                                    function(data) { 
                                        swal({
                                        title: "Success!", 
                                        text: "Successfully de-activated the plantilla!s!", 
                                        type: "success",
                                        icon: "success",
                                        }).then(function(e) {
                                            document.getElementById('st'+rowid).innerHTML = 'De-Activated';
                                            document.getElementById('act'+rowid).innerHTML = '<button type="button" class="btn btn-info btn-sm" onclick="activatePlant('+rowid+')"><i class="fas fa-check-circle"></i>ACTIVATE</button>';
                                        });

                                     }
                                );
                          } else {
                            swal({text:"You cancel the de-activateing of plantilla!",icon:"error"});
                          }
                        });
    }


 function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("allPlaList");
  tr = table.getElementsByTagName("tr");
for (i = 0; i < tr.length; i++) {
   td = tr[i].getElementsByTagName("td");
    if(td.length > 0){ // to avoid th
       if (td[0].innerHTML.toUpperCase().indexOf(filter) > -1 || td[1].innerHTML.toUpperCase().indexOf(filter) > -1 
        || td[2].innerHTML.toUpperCase().indexOf(filter) > -1  || td[3].innerHTML.toUpperCase().indexOf(filter) > -1
        || td[4].innerHTML.toUpperCase().indexOf(filter) > -1 ) {
         tr[i].style.display = "";
       } else {
         tr[i].style.display = "none";
       }

    }
 }
}



getPagination('#allPlaList');

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
