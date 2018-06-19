<link rel="stylesheet" href="<?php echo base_url('assets/fonts/ionicons/css/ionicons.min.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/fonts/fontawesome/css/font-awesome.min.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/slick.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/slick-theme.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/helpers.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css');?>">
<link rel="stylesheet" href="<?php echo base_url('assets/css/landing-2.css');?>">
<link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/bvalidator.css');?>" rel="stylesheet" />
<link href="<?php echo base_url('assets/css/circle-animation.css');?>" rel="stylesheet" />
<section class="pb_cover_v3 overflow-hidden cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-6">
        <h1 class="heading mb-3" style="font-size:58px;">WELCOME TO THE</h1>
          <h2 class="b-text">Biometric Authentication System</h2>
        <div class="sub-heading">
          <p class="mb-4"></p>

        </div>
      </div>
      <div class="col-md-1">
      </div>
      <div class="col-md-5 relative align-self-center">

        <form action="<?php echo base_url('admin/login');?>" id="login_validation" class="bg-white rounded pb_form_v1" method="post">
          <h2 class="mb-4 mt-0 text-center">Log into Admin Account</h2>
<div style="clear:both;"><?php include('validation_message.php'); ?></div>
            <div class="form-group">
                <input type="text" name="login" class="form-control input-form" value ="<?php echo set_value('login');?>" placeholder="Email Address" data-bvalidator="required,email" autocomplete="email" />
                <small class="form-control-feedback"></small>
            </div>
            <div class="form-group">


                    <input type="password" name="password" class="form-control input-form" placeholder="Password" data-bvalidator="required" autocomplete="false" size="30" />
                    <small class="form-control-feedback"></small>

            </div>


          <div class="form-group">
              <button class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue"   type="submit">Login</button>

          </div>


        </form>

      </div>
    </div>
  </div>
</section>
