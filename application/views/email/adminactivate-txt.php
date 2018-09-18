Welcome to <?php echo $site_name; ?>,

Your account has been activated by admin.
Follow this link to login on the site:

<?php echo site_url('login/'); ?>

<?php if (strlen($username) > 0) { ?>

Your username: <?php echo $username; ?>
<?php } ?>

Your email address: <?php echo $email; ?>

<?php /* Your password: <?php echo $password; ?>

*/ ?>

Have fun!
The <?php echo $site_name; ?> Team
