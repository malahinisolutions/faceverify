<?php  
					//lets have the flashdata overright "$message" if it exists
					if($this->session->flashdata('message'))
					{
					$message    = $this->session->flashdata('message');
					}
    
					if($this->session->flashdata('error'))
					{
					$error  = $this->session->flashdata('error');
					}
    
					if(function_exists('validation_errors') && validation_errors() != '')
					{
					$error  = validation_errors();
					}
				?>
				<?php if (!empty($message)): ?>
					<div class="alert alert-success col-lg-12 center" style="margin-bottom:10px !important;text-align: left !important;">
						<button type="button" class="close" data-dismiss="alert" style="color:#fff;opacity: 0.7 !important;"><i class="fa fa-times"></i></button>
						<strong><i class="glyphicon glyphicon-ok"></i> </strong><?php echo $message; ?>
					</div>
					<?php endif; ?>
					<?php if (!empty($error)): ?>
					<div class="alert alert-danger col-lg-12 center" style="margin-bottom:10px !important;text-align: left !important;">
						<button type="button" class="close" data-dismiss="alert" style="color:#fff;opacity: 0.7 !important;"><i class="fa fa-times"></i></button>
						<h4><i class="glyphicon glyphicon-ban-circle"></i> <strong>Oh! You got an error!</strong></h4>
						<p><?php if(is_array($error)){echo $error['error'];}else{echo($error);} ?></p>
					</div>
					<?php endif; ?> 