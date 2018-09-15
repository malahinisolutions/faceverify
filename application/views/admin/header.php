<!DOCTYPE html>
<html lang="en">
 <head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <link href='<?php echo base_url('assets/images/favicon.png');?>' rel='icon' type='image/x-icon'>
  <title>  <?php echo $page_title;?></title>
   <meta name="description" content="Biometric Authentication System">
   <meta name="keywords" content="Biometric Authentication System">


    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/vendor.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/app.css');?>">



<input type="hidden" id="base" value="<?php echo base_url(); ?>">
 </head>
 <body>
   <div class="main-wrapper">
       <div class="app" id="app">
<?php if ($this->tank_auth->is_admin_login()) {?>
               <header class="header">

                       <div class="header-block header-block-collapse hidden-lg-up">
                           <button class="collapse-btn" id="sidebar-collapse-btn">
                               <i class="fa fa-bars"></i>
                           </button>
                       </div>


                       <div class="header-block header-block-nav">
                           <ul class="nav-profile pull-right">

                               <li class="profile">
                                   <a class="nav-link"  href="#"  >
                                        <span class="name">
                                           <?php echo $this->session->userdata('username');?>
                                       </span>
                                   </a>
                                   
                               </li>
							    <li class="profile">
                                   <a class="nav-link"   href="<?= base_url('admin/login/logout')?>">
                                           <i class="fa fa-power-off icon"></i>
                                           Logout
                                   </a> 
                                       
                               </li>
                           </ul>
                       </div>


                       </header>
<?php } ?>
