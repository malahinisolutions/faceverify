<div class="navbar topnav">
   <div class="navbar-inner">
       <div class="container">
           <div class="nav-collapse ">
               <div class="col-md-4 col-sm-4 pull-right">
                   <div class="dropdown pull-right">
           <button onclick="myFunction()" class="dropbtn dropdown-toggle">  <?php if($name){ echo $name;}else{ echo $this->session->userdata('username');}?></button>
             <div id="myDropdown" class="dropdown-content">
                <a   href="<?php echo  base_url('login/logout');?>">Log Out</a>
             </div>
           </div>
               </div>
           </div>
       </div>
   </div>
</div>
<section class="pb_cover_v3  cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
  <div class="container">
    <div class="row">
        <div class="col-md-5 verification-id">

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
                    <div class="feature-icon  bg-active"> <span>02</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon"> <span>03</span></div>
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
            <div  style="position:relative; z-index: 99999; display: block; margin-top:0px; clear:both;">
                <h1 class="mb-3 heading-txt"></h1>


                <div  class="bg-white rounded pb_form_v1" style="float:left; width:100% !important;">
                    <h2 class="mb-4 mt-0 text-center">UPLOAD USER ID </h2>
                    <form action="<?php echo base_url('upload_document');?>" id="upload_document_validation"  class="uploader" method="post" enctype="multipart/form-data">
                    <div class="form-group col-md-12 col-sm-12 pd-5 no-padding pull-left">
                        <div style="clear:both;"><?php include('validation_message.php'); ?></div>
                        <label for="first_name" style="float: left; width: 100%; display: block;">Document Type:</label>
                        <select class="form-control" name="document_type" required="required">
                            <option  <?php if($document_type=='Driving License'){echo ' selected="selected"';}  ?> value="Driving License">Driving License</option>
                            <option <?php if($document_type=='Passport'){echo ' selected="selected"';}  ?> value="Passport">Passport</option>
                        </select>
                        <div class="id-uploader">
                          <input id="fileupload" type="file" name="document" <?php if($document_path==0){echo 'required="required" data-bvalidator="extension[jpeg:jpg:png],required" data-bvalidator-msg="Please select file of type .jpeg, .jpg or .png"';} ?>  />
                        </div>
                        <div id="start" class="upload-box">
                          <?php   if($document_path !='0' && !is_null($document_path)) { ?>
                          <img alt="Document" src="<?php echo base_url('upload/document/').$document_path;?>" />
                          <?php } ?>
                        </div>
                      </div>
                    <div class="form-group">
                        <div style="clear:both; float: right;">
                            <INPUT TYPE="button" VALUE="Back" class="previous-b btn btn-lg pb_btn-pill  btn-shadow-blue"  onClick="history.go(-1)"  style="width:150px; float: left;">
                            <button style="width:150px; float: left;" class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue"   type="submit">Continue &raquo;</button>
                        </div>
                    </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
  </div>
</section>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(function () {
    $("#fileupload").change(function () {
        $("#start").html("");
        var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
        if (regex.test($(this).val().toLowerCase())) {
            if ($.browser.msie && parseFloat(jQuery.browser.version) <= 9.0) {
                $("#start").show();
                $("#start")[0].filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = $(this).val();
            }
            else {
                if (typeof (FileReader) != "undefined") {
                    $("#start").show();
                    $("#start").append("<img />");
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("#start img").attr("src", e.target.result);
                    }
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    alert("This browser does not support FileReader.");
                }
            }
        } else {
            alert("Please upload a valid image file.");
        }
    });
});
</script>
