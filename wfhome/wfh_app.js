 var attFile;

function GetAttFile() {
    var selectedfile = document.getElementById("attachment").files;
    if (selectedfile.length > 0) {
        var uploadedFile = selectedfile[0];
        var fileReader = new FileReader();
        var fl = uploadedFile.name;

        fileReader.onload = function (fileLoadedEvent) {
            var srcData = fileLoadedEvent.target.result;
            attFile =  fl;
        }
        fileReader.readAsDataURL(uploadedFile);
    }
}


function uploadFile() {

   var files = document.getElementById("attachment").files;

   if(files.length > 0 ){

      var formData = new FormData();
      formData.append("file", files[0]);

      var xhttp = new XMLHttpRequest();


      xhttp.open("POST", "../pages/ajaxfile.php", true);

      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

           var response = this.responseText;
           if(response == 1){
              // alert("Upload successfully.");
           }else{
              swal("File not uploaded.");
           }
         }
      };

      // Send request with data
      xhttp.send(formData);

   }else{
      // alert("Please select a file");
   }

}


 $(function(){

   
    function CheckInput() {

        var inputValues = [];

        inputValues = [
            
            $('#wfh_task'),
            $('#attachment')
            
        ];

        var result = (CheckInputValue(inputValues) === '0') ? true : false;
        return result;
    }



$('#Submit').click(function(){

 
            // var dte = $('#wfhdate').val();
            // var dte_to = $('#wfhdateto').val();

            // dateArr = []; 

            // var start = new Date(dte);
            // var date = new Date(dte_to);
            // var end = date.setDate(date.getDate() + 1);

            // while(start < end){
            //    dateArr.push(moment(start).format('MM-DD-YYYY'));
            //    var newDate = start.setDate(start.getDate() + 1);
            //    start = new Date(newDate);  
            // }

            // const ite_date = dateArr.length === 0  ? dte : dateArr ;

            var e_req = $('#e_req').val();
            var n_req = $('#n_req').val();
            var e_appr = $('#e_appr').val();
            var n_appr = $('#n_appr').val();            



            if (CheckInput() === true) {

                param = {
                    "Action":"ApplyWfhApp",
                    "wfhdate": $('#wfhdate').val(),
                    "wfh_task": $('#wfh_task').val(),
                    "wfh_output": $('#wfh_output').val(),
                    "wfh_percentage": 0,
                    "e_req": e_req,
                    "n_req": n_req,
                    "e_appr": e_appr,
                    "n_appr": n_appr,
                    "attachment": attFile                    
                    
               };
                
                param = JSON.stringify(param);

                // console.log(param);
                // return false;
                
                      swal({
                          title: "Are you sure?",
                          text: "You want to apply this work from home?",
                          icon: "success",
                          buttons: true,
                          dangerMode: true,
                        })
                        .then((applyWfh) => {
                            document.getElementById("myDiv").style.display="block";
                          if (applyWfh) {
                                    $.ajax({
                                        type: "POST",
                                        url: "../wfhome/wfh_app_process.php",
                                        data: {data:param} ,
                                        success: function (data){
                                            console.log("success: "+ data);
                                                swal({
                                                title: "Success!", 
                                                text: "Successfully added work from home details!", 
                                                type: "success",
                                                icon: "success",
                                                }).then(function() {
                                                    location.href = '../wfhome/wfh_app_view.php';
                                                });
                                        },
                                        error: function (data){
                                            swal('error');
                                        }
                                    });
                          } else {
                            document.getElementById("myDiv").style.display="none";
                            swal({text:"You cancel your work from home!!",icon:"error"});
                          }
                        });

 
            }else{
                swal({text:"Kindly fill up blank fields!",icon:"error"});
            }

      
    });



 $('#applyWfh').click(function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');

    });

});
