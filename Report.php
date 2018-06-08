<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php";
$userInfo = userInfo();
$today = checkRequest("start_date");
$end = checkRequest("end_date");
if($today==''){
	$today = date("Y-m-d");
}
if($end == ''){
	$end   = date("Y-m-d",strtotime($today."-7 days"));
}
$to_day = date("d");
?>
<style>
@media print{
		.fprow, .table tr{
			background:grey !important;
		}
}
.fprow{
	background:grey !important;
}
</style>
<?php
if(isset($_REQUEST['wrong_token'])){
	echo "provided token was :".$_SESSION['provided_csrf_token']." where the token should be: ".$_SESSION['old_csrf_token']." current token is: ".$_SESSION['csrf_token'];
}
if(isset($_REQUEST['reported']) && $_REQUEST['reported']=="true"){
	if(isset($_REQUEST['user_type']) && $_REQUEST['user_type']=="SALES"){
		?>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">

		<center>
				<img src="./image/zb.png" width="200px" align="center"></img><br><?=date("d M-Y");?><br>
<?php
$remindQ = mysqli_query($con,"SELECT t.installment_day, t.invoice,t.id,t.client_id,c.name,c.father_name,
	c.permanent_address,c.occupation,c.phone,
	CAST((SELECT th.updated FROM `transaction_history` th where th.tid=t.id ORDER BY th.updated DESC LIMIT 1) as DATE) as last_update
	 from transactions t, clients c where type='2' and t.client_id=c.id and t.installment_day<'{$to_day}' and t.status='0' and t.Advance_pay=0") or die(mysqli_error($con));
	 if(mysqli_num_rows($remindQ)>0){
		 ?>
		<table class="table table-striped" style="margin:0 auto;">
			<thead>
					<tr>
						<th>Acc#</th>
						<th width="180px">Name</th>
						<th>Contact</th>
						<th>Address</th>
						<th width="180px">Installment day</th>
						<th>Last Paid</th>
						<th>Remarks</th>
					</tr>
			</thead>
		<?php

			while($rQ = mysqli_fetch_assoc($remindQ)){
		?>

		<tr>
				<td><?=$rQ['invoice'];?></td>
				<td width="180px"><?=dbOUT($rQ['name'])?> <br>S/D of<br> <?=dbOUT($rQ['father_name'])?></td>
				<td><?=dbOUT($rQ['phone'])?></td>
				<td><?=dbOUT($rQ['permanent_address'])?></td>
				<td><?=dbOUT($rQ['installment_day'])?></td>
				<td><?=$rQ['last_update']?></td>
				<td></td>
		</tr>


		<!-- <a href="addFunds.php?tid=<?=$rQ['id']?>&type=sales&client=<?=$rQ['client_id']?>&invoice=<?=$rQ['invoice']?>" class="list-group-item">
				<i class="fa fa-money fa-fw"></i>Reminder for Invoice# <?=$rQ['invoice']?>
						<span class="pull-right text-muted small"><em>Due on: <?=$rQ['installment_day']?> of every month</em>
						</span>
		</a>
		<br> -->
	<?php }} else{ echo "<h1>All Caught up! </h1>"; } ?>
		</table>
		<script> //window.print(); </script>
	</center>
		<?php
	}else if(isset($_REQUEST['user_type']) && $_REQUEST['user_type']=="ALL"){
		?>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
		<center>
				<img src="./image/zb.png" width="200px" align="center"></img><br><?=date("d M-Y");?><br>
<?php
$remindQ = mysqli_query($con,"SELECT t.installment_day, t.invoice,t.id,t.client_id,c.name,c.father_name,
	c.permanent_address,c.occupation,c.phone, c.account_number as acc, c.id as cid
	 from transactions t, clients c where type='2' and t.client_id=c.id") or die(mysqli_error($con));
	 if(mysqli_num_rows($remindQ)>0){
		 ?>

		<table class="table table-striped fptable" style="margin:0 auto;">
			<thead>
					<tr>
						<th>Acc#</th>
						<th width="180px">Name</th>
						<th>Contact</th>
						<th>Address</th>
						<th width="180px">Installment day</th>
						<th>Remarks</th>
					</tr>
			</thead>
		<?php
			while($rQ = mysqli_fetch_assoc($remindQ)){
		?>
		<tr class='fprow'>
				<td><?=$rQ['acc']?></td>
				<td width="180px"><?=dbOUT($rQ['name'])?></td>
				<!-- <br>S/D of<br> <?//=dbOUT($rQ['father_name'])?> -->
				<td><?=dbOUT($rQ['phone'])?></td>
				<td><?=dbOUT($rQ['permanent_address'])?></td>
				<td><?=dbOUT($rQ['installment_day'])?></td>
				<td></td>
		</tr>
		<tr>
			<td colspan="6" style="padding:0px; margin:0px;">
				<?php
			$id = $rQ['cid'];
			$Cquery = mysqli_query($con,"SELECT pat.name as party,(select CAST(ttt.updated AS DATE) from transaction_history ttt where ttt.tid=t.id order by ttt.id DESC limit 1) as last_paid,
			CAST(t.created_at as DATE) as created_at,
			t.status,t.id,t.invoice,t.party_id,t.dispute,t.total_amount,t.paid_amount, p.alias,
			IFNULL((select sum(th.amount) from transaction_history th where th.tid=t.id AND th.dispute='0' and th.type='1'),0) as other_paid
			FROM `transactions` t, products p, parties pat where t.product_id=p.id AND
			 pat.id=t.party_id and t.type='2' and t.client_id='".$id."'");
			if(mysqli_num_rows($Cquery)>0){
			?>
			<table class="sort_table table table-hover table-striped" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Product</th>

					<th>Total</th>
					<th>Paid</th>
					<th>Remaining</th>
					<th>Status</th>
					<th>DATE</th>

				</tr>
			</thead>
			<tbody>
				<?php
					while($result = mysqli_fetch_array($Cquery)){
						$totalAmount = "tamount";
						$tpaid_amount = "tpaid_amount";
						if($result['dispute']=="1"){
							$tpaid_amount = "";
							$totalAmount = "";
						}
				?>
				<tr>
					<td><?=dbOUT($result['id'])?></td>
					<td><?=dbOUT($result['alias'])?></td>

					<td class="<?=$totalAmount?>"><?=dbOUT($result['total_amount'])?></td>
					<td class="<?=$tpaid_amount?>"><?=dbOUT($result['other_paid'])?></td>
					<td class="tpaid_remaining"><?=$result['total_amount']-$result['other_paid']?></td>
					<td>
					<?php
					$pid = $result['id'];
					$invoice = $result['invoice'];
					if($result['status']=="0"){
						if($result['dispute']=="0"){
							echo "<span style='color:blue'><a href='addFunds.php?tid={$pid}&type=sales&client={$id}&invoice={$invoice}'>PENDING</a></span>";
						}else{
							echo "<b><span style='color:red'><a href='addFunds.php?tid={$pid}&type=sales&client={$id}&invoice={$invoice}' style='color:red'>DISPUTED</a></span></b>";
						}
					}else{
						if($result['dispute']=="0"){
						echo "<span style='color:green'><a href='addFunds.php?tid={$pid}&type=sales&client={$id}&invoice={$invoice}' style='color:green'>FULFILLED</a></span>";
						}else{
							echo "<b><span style='color:red'><a href='addFunds.php?tid={$pid}&type=sales&client={$id}&invoice={$invoice}' style='color:red'>DISPUTED</a></span></b>";
						}
					}
					?></td>
					<td><?=dbOUT($result['last_paid'])?></td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php }else{ echo "No Product found for this user"; } ?>
	</td>

<?php
}
?> </tr>
<table>
	<?php
}}
		else{
		echo "Nothing to do. ";
	}
	die();
}
?>
<?php include "tHeader.php"; ?>
<style>
.scroller{
	height:380px;
	overflow: auto;
}
.scroller .list-group-item{
	height:70px;
}
.scroller .list-group-item i{
	padding-right:30px;
}
</style>
<h1 class="page-header">Overview</h1>
<?php

$query = mysqli_query($con, "select t.dispute, t.client_id, p.id as party_id, po.id as product_id, p.name,t.invoice, po.alias,IFNULL((select sum(th.amount) from transaction_history th where th.pid=t.party_id AND th.tid=t.id AND th.dispute='0'),0) as other_paid, t.total_amount, t.paid_amount, t.id as tid, t.type, t.status, cast(t.created_at as date) as created_at from transactions t, parties p, products po where p.id=t.party_id and po.id=t.product_id and (t.type='1' or t.type='2') and cast(t.created_at as date)>='{$today}' and cast(t.created_at as date)<='{$end}'");

$record = mysqli_query($con, "select (select count(id) from clients where cast(created_at as date)>='{$today}' and cast(created_at as date)<='{$end}') as clients, (select count(id) from transactions where type='2' and cast(created_at as date)>='{$today}' and cast(created_at as date)<='{$end}') as sales, (select count(id) from parties where cast(updated_at as date)>='{$today}' and cast(updated_at as date)<='{$end}') as parties");
$records = mysqli_fetch_assoc($record);
?>

	<div class="row">
		*Records as per last week/selection
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-comments fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?=$records['clients']?></div>
                                        <div>New Clients!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="Clients.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-green">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-shopping-cart fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?=$records['sales']?></div>
                                        <div>New Sales!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="Sales.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="panel panel-red">
                            <div class="panel-heading">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <i class="fa fa-support fa-5x"></i>
                                    </div>
                                    <div class="col-xs-9 text-right">
                                        <div class="huge"><?=$records['parties']?></div>
                                        <div>New Parties!</div>
                                    </div>
                                </div>
                            </div>
                            <a href="Parties.php">
                                <div class="panel-footer">
                                    <span class="pull-left">View Details</span>
                                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                                    <div class="clearfix"></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
<a href="javascript:void(0)" class="toggler">Custom Search</a>

	<form method="GET" class="selector" style="display:none;">
<!-- 	<label for="selectType">Select Type:</label>
	<select name="selectType" id="selectType" class="selectType form-control">
		<option value="0" selected="selected">Purchase</option>
		<option value="1">Sale</option>
	</select> -->
	<label for="stimeRange">Start Date</label>
	<input type="text" id="stimeRange" class="sdate form-control" name="start_date" placeholder="YYYY-MM-DD" />
	<label for="etimeRange">End Date</label>
	<input type="text" id="etimeRange" class="edate form-control" name="end_date" placeholder="YYYY-MM-DD" />
	<button type="submit" role="submit" class="btn btn-primary goSearch">Search</button>
	</form>

<div class="row">
                    <div class="col-lg-8">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i> Transactions by last week
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body">
                                <div id="morris-area-chart"></div>
                            </div>
                            <!-- /.panel-body -->
                        </div>
                      </div>

                    <!-- /.col-lg-8 -->
                    <div class="col-lg-4">
                        <div class="panel panel-default ">
                            <div class="panel-heading">
                                <i class="fa fa-bell fa-fw"></i> Notifications Panel <a class="pull-right" href="?reported=true&user_type=SALES">Print</a>
                            </div>
                            <!-- /.panel-heading -->
                            <div class="panel-body scroller">
                                <div class="list-group">
                                  <!--   <a href="#" class="list-group-item">
                                        <i class="fa fa-shopping-cart fa-fw"></i> New Order Placed
                                            <span class="pull-right text-muted small"><em>9:49 AM</em>
                                            </span>
                                    </a> -->
                                    <?php
                                    	$remindQ = mysqli_query($con,"SELECT installment_day, invoice,id,client_id from
																				 transactions where type='2' and installment_day<='{$to_day}' and status='0' and Advance_pay='0'");
																			if(mysqli_num_rows($remindQ)>0){
                                    	while($rQ = mysqli_fetch_assoc($remindQ)){
                                    ?>

                                    <a href="addFunds.php?tid=<?=$rQ['id']?>&type=sales&client=<?=$rQ['client_id']?>&invoice=<?=$rQ['invoice']?>" class="list-group-item">
                                        <i class="fa fa-money fa-fw"></i>Reminder for Invoice# <?=$rQ['invoice']?>
                                            <span class="pull-right text-muted small"><em>Due on: <?=$rQ['installment_day']?> of every month</em>
                                            </span>
                                    </a>
                                    <br>
																	<?php }} else { echo "All caught up!"; } ?>
                                </div>

                            </div>
                            <!-- /.panel-body -->
                        </div>
                    </div>
                    <!-- /.col-lg-4 -->
                </div>
                <div class="row">
                	 <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-bar-chart-o fa-fw"></i> Report
                            </div>
                            <!-- /.panel-heading -->
                <div class="panel-body">
						<table class='table table-hover sort_table' style="margin:auto;width:100%" width="100%">
							<thead>
								<tr>
									<th>Invoice</th>
									<th>Date</th>
									<th>Party</th>
									<th>Product</th>
									<th>Type</th>
									<th>Total Amount</th>
									<th>Total Paid</th>

									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php
							while($result = mysqli_fetch_assoc($query)){
								$tid = $result['tid'];
							?>
								<tr>
									<td><?=$result['invoice']?></td>
									<td><?=$result['created_at']?></td>
									<td><?=dbOUT($result['name'])?></td>
									<td><?=dbOUT($result['alias'])?></td>
									<td>
									<?php
									$type = "purchase";
									$client = '';
									if($result['type']=="1"){
										echo "purchases";
									}
									if($result['type']=="2"){
										echo "Sale";
									if($result['client_id']!=''){
										$client = "&client=true";
										$type = "sales";
										}
									}
									?>
									</td>
									<td><?=$result['total_amount']?></td>
									<td><?=$result['other_paid']?></td>
									<td>
									<?php
									if($result['dispute']=='0'){
										if($result['status']=='0'){
											echo "In Progress";
										}
										else if($result['status']=='1'){
											echo "Fulfilled";
										}else{
											echo "Sold";
											}
									}else{
										echo "Disputed";
									}
									?>
									</td>
									<td>
										<a href="addFunds.php?tid=<?=$tid?>&type=<?=$type?>&invoice=<?=$result['invoice']?><?=$client?>">View</a>
										</td>
								</tr>
							<?php
							}
							?>
							</tbody>
						</table>
                     </div>
                            <!-- /.panel-body -->
               </div>
        </div>




<?php include "tFooter.php"; ?>
 <script src="../js/raphael.min.js"></script>
 <script src="../js/morris.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.13/jquery.mask.min.js"></script>
<?php

$AreaQ = mysqli_query($con,"SELECT (select count(pt.id) from transactions pt WHERE cast(pt.created_at as date)>='{$today}' AND cast(pt.created_at as date)<='{$end}' and pt.type='1') as purchase, cast(created_at as date) as period, (select count(st.id) from transactions st where cast(st.created_at as date)>='".$today."' and cast(st.created_at as date)<='".$end."' and st.type='2') as sales from transactions group by cast(created_at as date)");
while($re = mysqli_fetch_assoc($AreaQ)){
	$result[] = $re;
}

?>
<script>
	$(function(){
		$(".sdate,.edate").mask("0000-00-00");
		$(".toggler").click(function(){
			$(".selector").slideToggle();
		});
		     Morris.Area({
		        element: 'morris-area-chart',
		        data: <?=json_encode($result)?>,
		        xkey: ['period'],
		        ykeys:  ['sales', 'purchase'],
		        labels: ['Sales', 'Purchase'],
		        pointSize: 1,
		        hideHover: 'auto',
		        resize: true
		    });
	});
</script>
