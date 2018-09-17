<article class="content responsive-tables-page">
    <section class="section">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-block">
                            <div class="col-md-9">

                                <div class="example">
                                    <div class="header-user">
                                        <p>
                                            DECLINE USER VERIFICATION DETAILS
                                            <span class="back-buttion"> <a href="<?php echo base_url('admin/decline_user');?>"> <i class="fa fa-arrow-left" aria-hidden="true"></i> Back </a> </span>
                                        </p>

                                    </div>
                                    <div class="panel panel-default">
                                        <div class="panel-heading"> <i class="fa fa-asterisk" aria-hidden="true"></i> User Details  </div>
                                        <div class="table-responsive" style="padding: 10px;">
                                            <table class="table table-striped table-bordered table-hover">

                                                <tbody>
                                                    <tr>
                                                        <td>First Name: </td>
                                                        <td><?php echo $first_name;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Last Name: </td>
                                                        <td><?php echo $last_name;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Email Address: </td>
                                                        <td><?php echo $email;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Date of Birth: </td>
                                                        <td><?php echo $birth_date;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Country: </td>
                                                        <td><?php echo $country;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>State: </td>
                                                        <td><?php echo $state;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>City: </td>
                                                        <td><?php echo $city;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Zip Code: </td>
                                                        <td><?php echo $zipcode;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Home address: </td>
                                                        <td><?php echo $home_address;?></td>
                                                    </tr>
                                                    <tr>
                                                        <td> Mailing address: </td>
                                                        <td><?php echo $mailing_address;?></td>
                                                    </tr>

                                                </tbody>
                                            </table>


                                        </div>
                                    </div>


                                    <div class="col-sm-12 col-md-12" style="padding:0px;">
                                        <div class="panel panel-default pull-left" style="width:100%;">
                                          <div class="clearfix"><?php include('validation_message.php'); ?></div>
                                            <div class="panel-body">
											<?php foreach($comments as $comment){ ?>
											
											<div class="col-md-12 form-control" style="padding:5px;margin-bottom:10px;" >
											<p style="margin-bottom:5px;">Comment: <?php echo $comment->comment;?></p>
                                            <p style="margin-bottom:5px;">Status: <?php if($comment->status =='cancelled'){echo 'Decline';}else{echo 'Accept';};?> Verified By: <?php echo $comment->verified_by;?> Verified At: <?php echo $comment->verification_at;?></p>
                                            </div>
											<?php } ?>
                                               
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-3">
                               <div class="right-section">
                                   <div class="panel panel-default">
                                       <div class="panel-heading"> <i class="fa fa-address-card" aria-hidden="true"></i> Passport / Driver's License</div>
                                       <div class="panel-body"><p>Document Type: <?php echo $document_type;?></p><a href="#myModal-id" data-backdrop="false" data-toggle="modal"><img src="<?php echo base_url('upload/decline_user/documents/').$document_path;?>" style="width:100%;" /> </a></div>
                                   </div>
                                       <div class="panel panel-default">
                                           <div class="panel-heading">	<i class="fa fa-user" aria-hidden="true"></i> User Photo </div>
                                           <div class="panel-body"> <a  href="#full-width" data-toggle="modal"><img src="<?php echo base_url('upload/decline_user/users/').$user_image;?>" style="width:100%;" /> </a></div>
                                       </div>




                               </div>
                            </div>
                            </div>

                        </div>
                    </div>
                </div>
    </section>
</article>
<div id="myModal-id"  class="modal fade upload-id">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title"><i class="fa fa-address-card" aria-hidden="true"></i>  Passport / Driver's License</h4>

            </div>
            <div class="modal-body">
                    <img src="<?php echo base_url('upload/decline_user/documents/').$document_path;?>" />
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
