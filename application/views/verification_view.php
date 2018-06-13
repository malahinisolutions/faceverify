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
<section class="pb_cover_v3 overflow-hidden cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
  <div class="container">
    <div class="row align-items-center justify-content-center">
      <div class="col-md-7">
        <h1 class="mb-3 heading-txt">IDENTITY VERIFICATION </h1>

        <div class="sub-heading text-justify">
          <p class="mb-4">Please verify your identity using one of the two identification documents (Either Driving License or Passport) Your home address must be on the identification document you chose.</p>
            <p> Your are also required to have a web cam connected to your computer. Without a webcame you won't be able to take the verification process. </p>
            <p>
                <b>Verification System validates customers passport/driving license with in 14 working days.</b> <br />
            </p>

          <!--  <button type="button"  class="verifynow-button" data-toggle="modal" data-target="#myModal"><span>Verify Now </span></button>-->

            <p style="margin-top:10px;">  If your identity is not successfully verified within 14 days, your account will be deactivated.  </p>
         <p> <a href="verification-user-capture-image.html"> Days remaining: 5 </a></p>
         <h2>Verification Status: <?php echo $status;?></h2>
         <a href="<?php echo base_url('authenticate');?>" title="Authenticate Yourself">Authenticate Yourself</a>

        </div>
      </div>

      <div class="col-md-5 relative align-self-center verification-sec">

         <!-- <img src="assets/images/Untitled-1.png" alt=""/> -->


              <div id="main">
                  <div id="outer-circle">
                      <div id="inner-circle">
                          <div id="center-circle">
                              <div id="content1"> <div class="p-text"> </div> </div>
                          </div>
                      </div>
                  </div>

              </div>
      </div>
    </div>
  </div>
</section>
