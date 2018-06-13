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
<section class="pb_cover_v3  cover-bg-indigo cover-bg-opacity text-left pb_gradient_v1 pb_slant-light" id="section-home">
  <div class="container">
    <div class="row align-items-center justify-content-center">
        <div class="col-md-6 relative align-self-center verification-id" style="margin-top:50px; clear:both;">

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
                    <div class="feature-icon"> <span>03</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p>
                    </div>
                </div>
                <!-- feature-left -->
                <div class="feature-left">
                    <div class="feature-icon  bg-active"> <span>04</span></div>
                    <div class="feature-content">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text</p></div>
                </div>
                <hr>

            </div>

        </div>

        <div class="col-md-6 col-sm-12">
            <div  style="position:relative; z-index: 99999; display: block; margin-top:0px; clear:both;">
                <h1 class="mb-3 heading-txt"></h1>


                <div  class="bg-white rounded pb_form_v1" style="float:left; width:100% !important;">
                   <div class="successful"><i class="fa fa-check" aria-hidden="true"></i>
                    <p>
                        <span> Thank You.</span>
                        <br />
                        You have been successfully registered.
                    </p>
                     </div>
                    <div class="clear"></div>

                    <hr />
                    <div class="clear"></div>
                    <div class="form-group">
                      <form action="<?php echo base_url('complete_verification');?>" id="capture_image_validation"  method="post">
                        <input type="hidden" name="finish" value="1">
                        <button class="btn btn-primary btn-lg btn-block pb_btn-pill  btn-shadow-blue"   type="submit">Finish</button>
                      </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
  </div>
</section>
