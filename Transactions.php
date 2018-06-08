<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php";
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>
<?php

$is_client = checkRequest("client")=='' ? false : checkRequest("client");
$id = $_GET['party'];
$type = $_GET['type'];
$acc  = $id;
if(!$is_client){
	$party_query = mysqli_query($con,"SELECT name from parties where id='{$id}'");
	$r       = mysqli_fetch_array($party_query);
	$partyName = $r['name'];
}else{
	$party_query = mysqli_query($con, "SELECT account_number as acc, name from clients where id = '{$id}'");
	$r       = mysqli_fetch_array($party_query);
	$acc     = $r['acc'];
	$partyName = $r['name'];
}
if($type=="sales"){
	$title = "Sales";
}else{
	$title = "Purchases";
}


?>
<style>
@media print{
		label,textarea,.form-control,input,button,a,nav,.dataTables_filter,.dataTables_length,.dataTables_info,.dataTables_paginate{
		display:none !important;
	}
}
</style>
<center><h1 class="page-header"><?=$title?> of <?=dbOUT($partyName)?>: <?=$acc?></h1></center>
<table class="table table-striped">
	<tr>
		<th width="20%">Total Paid</th><td align="left" class="tpaid"></td>
	</tr>
	<tr>
		<th width="20%">Total Remaining</th><td align="left" class="tremain"></td>
	</tr>
	<tr>
		<th width="20%">Total Purchase</th><td align="left"  class="tpurchase"></td>
	</tr>
</table>

<?php
switch($type){
	case 'purchase':
?>

<div class="Server">
<?php
		$query = mysqli_query($con,"SELECT t.chasing as party,t.created_at,t.status,t.id,t.invoice,t.party_id,t.dispute,t.total_amount,t.paid_amount, p.alias,
		IFNULL((select sum(th.amount) from transaction_history th where th.pid='{$id}' AND th.tid=t.id AND th.dispute='0'),0) as other_paid FROM `transactions` t, products p, parties pat where t.product_id=p.id AND pat.id=t.party_id and t.party_id='{$id}' and t.type='1'");



	?>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Invoice</th>
				<th>Product</th>
				<th>chasing</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
				while($result = mysqli_fetch_array($query)){
					$totalAmount = "tamount";
					$tpaid_amount = "tpaid_amount";
					if($result['dispute']=="1"){
						$tpaid_amount = "";
						$totalAmount = "";
					}
			?>
			<tr>
				<td><?=dbOUT($result['id'])?></td>
				<td><?=dbOUT($result['created_at'])?></td>
				<td><?=dbOUT($result['invoice'])?></td>
				<td><?=dbOUT($result['alias'])?></td>
				<td><?=dbOUT($result['party'])?></td>
				<td class="<?=$totalAmount?>"><?=dbOUT($result['total_amount'])?></td>
				<td class="<?=$tpaid_amount?>"><?=dbOUT($result['other_paid'])?></td>
				<td class="tpaid_remaining"><?=$result['total_amount']-$result['other_paid']?></td>
				<td>
				<?php
				$pid = $result['id'];
				$invoice = $result['invoice'];
				if($result['status']=="0"){
					if($result['dispute']=="0"){
						echo "<span style='color:blue'><a href='addFunds.php?tid={$pid}&type=purchase&invoice={$invoice}'>PENDING</a></span>";
					}else{
						echo "<b><span style='color:red'><a href='addFunds.php?tid={$pid}&type=purchase&invoice={$invoice}' style='color:red'>DISPUTED</a></span></b>";
					}
				}else{
					if($result['dispute']=="0"){
					echo "<span style='color:green'><a href='addFunds.php?tid={$pid}&type=purchase&invoice={$invoice}' style='color:green'>FULFILLED</a></span>";
					}else{
						echo "<b><span style='color:red'><a href='addFunds.php?tid={$pid}&type=purchase&invoice={$invoice}' style='color:red'>DISPUTED</a></span></b>";
					}
				}
				?></td>



			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Invoice</th>
				<th>Product</th>
				<th>chasing</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>Status</th>


			</tr>
		</tfoot>
	</table>
</div>
<a href="Parties.php"><button class="btn btn-info" role="button">Go Back</button></a>
<?php
break;
case 'sales':

	if($is_client){
		$query = mysqli_query($con,"SELECT pat.name as party,t.created_at,t.status,t.id,t.invoice,t.party_id,t.dispute,t.total_amount,t.paid_amount, p.alias,
		IFNULL((select sum(th.amount) from transaction_history th where th.tid=t.id AND th.dispute='0' and th.type='1'),0) as other_paid FROM `transactions` t, products p, parties pat where t.product_id=p.id AND pat.id=t.party_id and t.type='2' and t.client_id='{$id}'");

		?>
		<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Product</th>
				<th>Party</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>Status</th>
				<th>DATE</th>

			</tr>
		</thead>
		<tbody>
			<?php
				while($result = mysqli_fetch_array($query)){
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
				<td><?=dbOUT($result['party'])?></td>
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
				<td><?=dbOUT($result['created_at'])?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Product</th>
				<th>Party</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>Status</th>
				<th>DATE</th>

			</tr>
		</tfoot>
	</table>
		<?php
	}else{
		$query = mysqli_query($con,"SELECT t.client_id, c.name as client_name,(select chasing from transactions ttt where ttt.id=t.sale_tid) as party,t.created_at,t.status,t.id,t.invoice,t.party_id,t.dispute,t.total_amount,t.paid_amount, p.alias,
		IFNULL((select sum(th.amount) from transaction_history th where th.pid=t.party_id AND th.tid=t.id AND th.dispute='0' and th.type='1'),0) as other_paid FROM `transactions` t, products p, parties pat, clients c where t.product_id=p.id AND pat.id=t.party_id and t.type='2' and t.client_id=c.id and t.party_id='{$id}'");
		?>
		<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Product</th>
				<th>Chasing</th>
				<th>Client</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>Status</th>

			</tr>
		</thead>
		<tbody>
			<?php
				while($result = mysqli_fetch_array($query)){
					$totalAmount = "tamount";
					$tpaid_amount = "tpaid_amount";
					if($result['dispute']=="1"){
						$tpaid_amount = "";
						$totalAmount = "";
					}
			?>
			<tr>
				<td><?=dbOUT($result['id'])?></td>
				<td><?=dbOUT($result['created_at'])?></td>
				<td><?=dbOUT($result['alias'])?></td>
				<td><?=dbOUT($result['party'])?></td>
				<td><?=dbOUT($result['client_name'])?></td>
				<td class="<?=$totalAmount?>"><?=dbOUT($result['total_amount'])?></td>
				<td class="<?=$tpaid_amount?>"><?=dbOUT($result['other_paid'])?></td>
				<td class="tpaid_remaining"><?=$result['total_amount']-$result['other_paid']?></td>
				<td>
				<?php
				$pid = $result['id'];
				$cid = $result['client_id'];
				$invoice = $result['invoice'];
				if($result['status']=="0"){
					if($result['dispute']=="0"){
						echo "<span style='color:blue'><a href='addFunds.php?tid={$pid}&type=sales&client={$cid}&invoice={$invoice}'>PENDING</a></span>";
					}else{
						echo "<b><span style='color:red'><a href='addFunds.php?tid={$pid}&type=sales&client={$cid}&invoice={$invoice}' style='color:red'>DISPUTED</a></span></b>";
					}
				}else{
					if($result['dispute']=="0"){
					echo "<span style='color:green'><a href='addFunds.php?tid={$pid}&type=sales&client={$cid}&invoice={$invoice}' style='color:green'>FULFILLED</a></span>";
					}else{
						echo "<b><span style='color:red'><a href='addFunds.php?tid={$pid}&type=sales&client={$cid}&invoice={$invoice}' style='color:red'>DISPUTED</a></span></b>";
					}
				}
				?></td>

			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Date</th>
				<th>Product</th>
				<th>Chasing</th>
				<th>Client</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>Status</th>


			</tr>
		</tfoot>
	</table>
	<a href="javascript:history.back();"><button class="btn btn-info" role="button">Go Back</button></a>
		<?php
	}


break;
default:
set_message("Wrong choice");
goBack();
break;
}
?>

<?php include "tFooter.php"; ?>
<script>
	$(function(){
		$total_amount = 0;
		$total_paid   = 0;
		$(".tamount").each(function(){
			if($(this).text()!=""){
				$total_amount = $total_amount + parseInt($(this).text());
			}
		});
		$(".tpaid_amount").each(function(){
			if($(this).text()!=""){
				$total_paid = $total_paid + parseInt($(this).text());
			}
		});


		$(".tpurchase").text($total_amount);
		$(".tpaid").text($total_paid);
		$(".tremain").text($total_amount-$total_paid);
	});
</script>
