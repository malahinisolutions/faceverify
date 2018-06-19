<?php

	function sort_url($lang, $by, $sort, $sorder)
	{
		if ($sort == $by)
		{
			if ($sorder == 'asc')
			{
				$sort	= 'desc';
				$icon	= ' <i class="fa fa-chevron-up pull-right" aria-hidden="true"></i>';
			}
			else
			{
				$sort	= 'asc';
				$icon	= ' <i class="fa fa-chevron-down pull-right" aria-hidden="true"></i>';
			}
		}
		else
		{
			$sort	= 'asc';
			$icon	= '';
		}


		$return = site_url('admin/userverification/index/'.$by.'/'.$sort);

		echo '<a href="'.$return.'">'.lang($lang).$icon.'</a>';

	} ?>
  <article class="content responsive-tables-page">
            <section class="section">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-block">
                                    <div class="card-title-block">
                                        <div class="col-lg-9">
                                            <h3 class="title"> <i class="fa fa-certificate" aria-hidden="true"></i> Welcome Admin, Below is the list of Pending, Approved and Declined users. </h3>
                                            <div class="clearfix"><?php include('validation_message.php'); ?></div>
                                        </div>
                                            <div class="col-lg-3">
                                                <div class="input-group custom-search-form">
                                                    <input type="text" placeholder="Search" class="form-control">
                                                    <span class="input-group-btn">
                                                        <button class="btn btn-primary" type="button">
                                                            <i class="fa fa-search" aria-hidden="true"></i>
                                                        </button>
                                                    </span>
                                                </div><!-- /input-group -->
                                            </div>
                                        </div>
                                    <section class="example">
                                        <div class="col-md-12 table-responsive">
                                            <table id="tables" class="table table-striped table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th><?php echo sort_url('first_name', 'first_name', $sort_by, $sort_order); ?></th>
                                                        <th><?php echo sort_url('last_name', 'last_name', $sort_by, $sort_order); ?></th>
                                                        <th><?php echo sort_url('email', 'email', $sort_by, $sort_order); ?></th>
                                                        <th><?php echo sort_url('status', 'status', $sort_by, $sort_order); ?></th>
                                                        <th><?php echo sort_url('country', 'country', $sort_by, $sort_order); ?></th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                  <?php //echo '<pre>';print_r($alladmins);die();
                                        						echo (count($alladmins) < 1)?'<tr><td class="center" colspan="7">'."<h3>There is no record of Admins</h3>" .'</td></tr>':'';
                                        					 $count=0;$i=0; foreach($alladmins as $admin): $count=$count++;$i=$i+1;?>
                                        					<tr class="<?php if(($count % 2)==0){echo 'even';}else{echo 'odd';} ?>">
                                        						<td><?php echo $i;?></td>
                                                    <td><?php echo $admin->first_name;?></td>
                                        						<td><?php echo $admin->last_name;?></td>
                                                    <td><?php echo $admin->email;?></td>
                                                    <td><?php echo $admin->status;?></td>
                                                    <td><?php echo $admin->country;?></td>
                                                    <td><a href="<?php echo base_url('admin/userverification/edit/').$admin->user_id;?>" title="More Details"> <i class="fa fa-info-circle" aria-hidden="true"></i> </a></td>
                                                    </tr>
                                                    <?php endforeach; ?>

                                                </tbody>
                                            </table>

                                          <div class="paging_bootstrap" id="datatable-table_paginate">
                                            <?php echo $this->pagination->create_links();?>
                                          </div>
                                            <!--<ul class="pagination">
                                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                            </ul>-->
                                        </div>

                                    </section>
                                </div>
                            </div>
                        </div>
                    </div>

            </section>

        </article>
