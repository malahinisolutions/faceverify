<footer class="pb_footer bg-white" role="contentinfo" style="clear:both;">
  <div class="container">

    <div class="row">
      <div class="col text-center">
        <p class="pb_font-14">&copy; 2018 <a href="#">BIOMETRIC AUTHENTICATION SYSTEM</a> <br> Designed &amp; Developed by <a href="#">Malahini Solutions</a> </p>

      </div>
    </div>
  </div>
</footer>

<!-- loader -->
<div id="pb_loader" class="show fullscreen">
  <svg class="circular" width="48px" height="48px">
    <circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/>
    <circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#1d82ff"/>
  </svg>
</div>

<script src="<?php echo base_url('assets/js/jquery.min.js');?>"></script>


<script src="<?php echo base_url('assets/js/popper.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>

<script src="<?php echo base_url('assets/js/slick.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/jquery.mb.YTPlayer.min.js');?>"></script>

<script src="<?php echo base_url('assets/js/jquery.waypoints.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/jquery.easing.1.3.js');?>"></script>

<script src="<?php echo base_url('assets/js/jquery.bvalidator.js');?>"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>


<script src="<?php echo base_url('assets/js/main.js');?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#register_validation').bValidator();
        $('#login_validation').bValidator();
        $('#send_again_validation').bValidator();
        $('#forgot_password_validation').bValidator();
        $('#reset_password_validation').bValidator();
        $('#personal_information_validation').bValidator();
        $('#upload_document_validation').bValidator();
    });
</script>
<script>
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {

    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}
</script>

</body>
</html>
