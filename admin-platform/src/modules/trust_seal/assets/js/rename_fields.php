<script>
     document.querySelector('.customer-profile-tabs li:nth-child(2)').style.display = "none";
     var parentCustom = document.getElementById("vat").parentElement;
     parentCustom.querySelector('label').innerHTML = '<?php echo _l('trust_seal_rnc'); ?>';


     function CargarFile(input, nombreDocumentoLlenar) {
          let thisDropzone;
          if (input.files && input.files[0]) {
               getBase64(input.files[0], function(e) {
                    var strImage = e.target.result.split(',');
                    $(nombreDocumentoLlenar).val(strImage[0] + "|" + strImage[1]);
               });
          }

     }

     function getBase64(file, onLoadCallback) {
          var reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = onLoadCallback;
          reader.onerror = function(error) {
               //console.log('Error when converting PDF file to base64: ', error);
               Alert(error);
          };
     }
     window.addEventListener('load', function() {
          $(".client-form").attr("enctype", "multipart/form-data");

          var parentCustomCurrency = document.getElementById("default_currency").parentElement;
          parentCustomCurrency.parentElement.style.display = "none";
          // document.getElementById("hide").style.display = "block"; 

          //Dropzone.options.clientAttachmentsUpload = false;
          var customer_id = $('input[name="userid"]').val();
          // if ($('#file_client_logo').length > 0) {
          //      new Dropzone('#file_client_logo', appCreateDropzoneOptions({
          //           paramName: "file_client_logo",
          //           url: '<?php echo admin_url('trust_seal/seals/add_logo_clients') ?>/' + customer_id,
          //           accept: function(file, done) {
          //                done();
          //           },
          //           success: function(file, response) {
          //                if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length ===
          //                     0) {
          //                     window.location.reload();
          //                }
          //           }
          //      }));
          // }

          // $("#file_client_logo").change(function() {

          //      let fileBase64 = $(this).closest('.input-group').find('#client_logo');
          //      CargarFile(this, fileBase64);

          //      var fileName = $(this).val().split("\\").pop();
          //      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
          // });

     })
</script>