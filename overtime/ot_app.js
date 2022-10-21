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

      // Set POST method and ajax file path
      xhttp.open("POST", "../pages/ajaxfile.php", true);

      // call on request changes state
      xhttp.onreadystatechange = function() {
         if (this.readyState == 4 && this.status == 200) {

           var response = this.responseText;
           if(response == 1){
              // alert("Upload successfully.");
           }else{
              // alert("File not uploaded.");
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

   $('#planot').hide();

    function CheckInput() {

        var inputValues = [];

        inputValues = [

            $('#otdate'),
            $('#remarks'),
            $('#otstartdtime'),
            $('#otenddtime'),
            $('#attachment')        
        ]

        var result = (CheckInputValue(inputValues) === '0') ? true : false;
        return result;
    }


$('#Submit').click(function(){

    
            var e_req = $('#e_req').val();
            var n_req = $('#n_req').val();
            var e_appr = $('#e_appr').val();
            var n_appr = $('#n_appr').val();            


            if (CheckInput() === true) {

                param = {
                    "Action":"ApplyOtApp",
                    "otdate": $('#otdate').val(),
                    "otstartdtime": $('#otstartdtime').val(),
                    "otenddtime": $('#otenddtime').val(),
                    "remarks": $('#remarks').val(),
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
                              text: "You want to apply this overtime?",
                              icon: "info",
                              buttons: true,
                              dangerMode: true,
                            })
                            .then((applyOT) => {
                            document.getElementById("myDiv").style.display="block";
                              if (applyOT) {
                                        $.ajax({
                                        type: "POST",
                                        url: "../overtime/ot_app_process.php",
                                        data: {data:param} ,
                                        success: function (data){
                                            console.log("success: "+ data);
                                                    swal({
                                                    title: "Success!", 
                                                    text: "Successfully added overtime details!", 
                                                    type: "success",
                                                    icon: "success",
                                                    }).then(function() {
                                                        location.href = '../overtime/ot_app_view.php';
                                                    });
                                        },
                                        error: function (data){
                                            // alert('error');
                                        }
                                    });//ajax

                              } else {
                                document.getElementById("myDiv").style.display="none";
                                swal({text:"You cancel your overtime!",icon:"error"});
                              }
                            });

                 
            }else{
                swal({text:"Kindly fill up blank fields!",icon:"error"});
            }
                        
      
        
    });



 $('#applyOvertime').click(function(e){
        e.preventDefault();
        $('#popUpModal').modal('toggle');

    });

});
