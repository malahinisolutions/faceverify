<section class="pb_cover_v3 overflow-hidden cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-6">
        <h1 class="heading mb-3">WELCOME TO THE </h1>
          <h2 class="b-text">Biometric Authentication System</h2>
        <div class="sub-heading">
          <p class="mb-4">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>

        </div>
      </div>
      <div class="col-md-1">
      </div>
      <div class="col-md-5 relative align-self-center">

        <form action="<?php echo base_url('reset_password/index/').$this->uri->segment(3).'/'.$this->uri->segment(4);?>" id="reset_password_validation" class="bg-white rounded pb_form_v1" method="post">
          <h2 class="mb-4 mt-0 text-center">Reset Password</h2>
<div style="clear:both;"><?php include('validation_message.php'); ?></div>

            <div class="form-group">
                <div class="col-xs-5">
                    <input type="password" name="new_password" class="form-control input-form" placeholder="New Password" data-bvalidator="required" autocomplete="false" size="30" />
                    <small class="form-control-feedback"></small>
                </div>
            </div>
            <div class="form-group">
                <div class="col-xs-5">
                    <input type="password" name="confirm_new_password" class="form-control input-form" placeholder="Confirm New Password" data-bvalidator="required" autocomplete="false" size="30" />
                    <small class="form-control-feedback"></small>
                </div>
            </div>

          <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue"   type="submit">Reset Password</button>

          </div>


        </form>

      </div>
    </div>
  </div>
</section>


<!-- END section -->

<section class="pb_section bg-light pb_slant-white pb_pb-250" id="section-features">
  <div class="container">


<div class="service">
    <div class="box-right pull-left">
        <div class="col-md-6 pull-left">
            <h3 style="margin-top:40px;"> Id Verification</h3>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
            <br /> <br />
                It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                <br /> <br />
                It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
            </p>

        </div>
        <div class="col-md-6 pull-right">
        <div class="img-effect"><img src="<?php echo base_url('assets/images/01.jpg');?>" class="img-responsive" alt="" > </div>
        </div>
    </div>

</div>
      </div>
    </section>
    <section class="pb_section bg-white pb_slant-white " id="section-features">
        <div class="container">



                <div class="box-right pull-left">
                    <div class="col-md-6 pull-left" style="margin-top:50px;">
                        <div class="img-effect"><img src="<?php echo base_url('assets/images/02.jpg');?>" class="img-responsive" alt=""> </div>
                    </div>
                    <div class="col-md-6 pull-right ">
                        <h3 style="margin-top:80px;">Biometric verification  </h3>
                        <p style="margin-bottom: 100px;">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                            <br /> <br />
                            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            <br /> <br />
                            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </p>
                    </div>

                </div>
            </div>

    </section>
    <section class="pb_section bg-light pb_slant-white pb_pb-250" id="section-features">
        <div class="container">


            <div class="service">
                <div class="box-right pull-left">
                    <div class="col-md-6 pull-left">
                        <h3 style="margin-top:170px;">Data security </h3>
                        <p>
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                            <br /> <br />
                            It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.
                            <br /> <br />
                            It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                        </p>
                    </div>


                    <div class="img-effect" style="margin-top:150px !important;"><img src="<?php echo base_url('assets/images/03.jpg');?>" class="img-responsive" alt=""> </div>
                </div>
            </div>
        </div>
    </section>
