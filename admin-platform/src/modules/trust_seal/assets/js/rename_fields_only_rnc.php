<?php
$CI = &get_instance();
$msgError = "";

if ($CI->input->post()) {
     $msgError = form_error('passwordhidden');
}

?>
<script>
     window.addEventListener('load', function() {
          var parentCustom = document.getElementById("vat").parentElement;
          parentCustom.querySelector('label').innerHTML = '<?php echo _l('trust_seal_rnc'); ?>';
     });

     $(document).ready(function() {

          $('.register-password-group').append($('<input>', {
               'name': "passwordhidden",
               'id': "passwordhidden",
               'class': "form-control",
               'type': 'password'
          }));

          $("body").on("keyup, kepress, keydown, blur", "#passwordhidden", function(e) {
               var writen = $("#passwordhidden").val();
               $('#password').val(writen);
          });

          $("#password").hide();
          $('.register-password-group p').first().hide();
          $(".register-password-group").append('<?php echo $msgError ?>');
     });
</script>