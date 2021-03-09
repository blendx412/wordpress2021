<div class="wpwoof-box">
    <form method="post" action="">
        <h3>Feed Refresh Option:</h3>
        <div class="wpwoof-addfeed-top">
            <div class="addfeed-top-field"><p>
                <label class="addfeed-top-label">Interval</label>
                <span class="addfeed-top-value">
                    <?php $current_interval = wpwoof_get_interval();  ?>
                    <select name="wpwoof_schedule" id="wpwoof_schedule">
                        <?php 

                        $intervals = array(
                            /*
                            '604800'    => '1 Week',
                            '86400'     => '24 Hours',
                            '43200'     => '12 Hours',
                            '21600'     => '6 Hours',
                            '3600'      => '1 Hour',
                            '900'       => '15 Minutes',
                            '300'       => '5 Minutes',
                            */
                            '3600'      => 'Hourly',
                            '43200'     => 'Twice daily',
                            '86400'     => 'Daily',
                            '604800'    => 'Weekly'
                        );
                        foreach($intervals as $interval => $interval_name) {
                            ?><option <?php if($interval==$current_interval) echo " selected " ?> value="<?php echo $interval; ?>"><?php echo $interval_name; ?></option><?php
                        }
                        ?>
                    </select>
                </span>
            </p></div>
        </div>
        <div class="wpwoof-aligncenter">
            <input type="submit" class="wpwoof-button wpwoof-button-blue" value="Update Refresh Options" name="wpwoof_schedule_submit" />
        </div>
    </form>
</div>

<?php include('feed-manage-list.php');

$myListTable = new Wpwoof_Feed_Manage_list();
$myListTable->prepare_items();
/* trace($myListTable); */

  ?>
<form id="contact-filter" method="post">
	<!-- For plugins, we also need to ensure that the form posts back to our current page -->
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
	<?php //$myListTable->search_box('search', 'search_id'); ?>
	<!-- Now we can render the completed list table -->
	<?php $myListTable->display() ?>
</form>

<div class="wpwoof-content wpwoof-box">
    <h2>Product Catalog Feed PRO License</h2>
    <div class="wpwoof-aligncenter">
        <a class="wpwoof-button wpwoof-button-blue" href="<?php echo admin_url() ?>?page=wpwoof-settings&pcbpys_license_deactivate=true"><?php _e('Deactivate License'); ?></a>
    </div>
</div>