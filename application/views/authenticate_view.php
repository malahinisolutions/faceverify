<div class="navbar topnav">
    <div class="navbar-inner">
        <div class="container">
            <div class="nav-collapse ">
                <div class="col-md-4 col-sm-4 pull-right">
                    <div class="dropdown pull-right">
                        <a  class="btn  dropdown-toggle" data-toggle="dropdown" style="color: #fff;">
                          <?php if($name){ echo $name;}else{ echo $this->session->userdata('username');}?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?php echo  base_url('login/logout');?>">Log Out</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="pb_cover_v3 cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
  <div class="container">
    <div class="row">
        <div class="col-md-5 verification-id page-p">

            <div class="feature-description">
                <h2>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy.
                </h2>
                <hr>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon"> <span>01</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon"> <span>02</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon bg-active"> <span>03</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon"> <span>04</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p></div>
                </div>
                <hr>

            </div>

        </div>

        <div class="col-md-7 col-sm-12">
            <div class="page-p">
                <h1 class="mb-3 heading-txt"></h1>
                <div  class="bg-white rounded pb_form_v1" style="float:left; width:100% !important;">
                    <h2 class="mb-4 mt-0 text-center">Authenticate Yourself</h2>
                    <div style="clear:both;"><?php include('validation_message.php'); ?></div>
                    <div class="form-group col-md-12 col-sm-12 pd-5  pull-left">

                      <div class="col-md-6 col-sm-12 pull-left no-padding" >
                       <div id="my_camera"></div>
                        <input type=button class="snapshot-b" value="Take Snapshot" onClick="take_snapshot()">
                     </div>
                     <div class="col-md-6 col-sm-12 pull-right" id="results">
                       <p>Your captured image will appear here...</p>
                       </div>
                    </div>
                    <div class="form-group">
                        <div class="pull-right">
                          <form action="<?php echo base_url('authenticate');?>" id="capture_image_validation"  method="post">
                            <INPUT TYPE="button" VALUE="Back" class="previous-b"  onClick="history.go(-1)" >
                            <button class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue"   type="submit">Continue &raquo;</button>
                          </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
  </div>
</section>
<style>
#my_camera{
 width: 268px;
 height: 200px;
 border: 1px solid black;
}
.no-padding{
  padding: 0px !important;
}
</style>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="<?php echo base_url('assets/webcamjs-master/webcam.js');?>"></script>
<script type="text/javascript">
$(document).ready(function(){
  Webcam.set({
    width: 266,
    height: 200,
    image_format: 'jpeg',
    jpeg_quality: 90
  });
  Webcam.attach( '#my_camera' );

});
function take_snapshot() {
  // take snapshot and get image data
  Webcam.snap( function(data_uri) {
    // display results in page
    document.getElementById('results').innerHTML =
      '<img id="imageprev" src="'+data_uri+'"/>'+' <input id="savesnapshot" type=button class="sav-snapshot-b" value="Authenticate Yourself" onClick="saveSnap()">';
  } );
}
function saveSnap(){
   var base_url = $('#base').val();
 var base64image = document.getElementById("imageprev").src;

 Webcam.upload( base64image, base_url+'authenticate/upload', function(code, text) {
  if(code=='200'){$('#savesnapshot').hide(); }
  console.log(code);
 });

}
</script>
