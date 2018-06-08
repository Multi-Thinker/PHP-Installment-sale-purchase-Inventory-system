<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>
<h1 class="page-header">Records</h1> 
<?=log_message();?>
<div class="server">
	<table class="table sort_table table-striped">
		<thead>
			<tr>
				<th>Invoice #</th>
				<th>Party</th>
				<th>Price Total</th>
				<th>Price Paid</th>
				<th>Dated</th>
			</tr>
		</thead>
		<tbody>
	<?php 
		$id = '';
		if(!isset($_REQUEST['ID']) && !$_REQUEST['ID']==''){
			set_message("You are not allowed to perform that action");
			goBack();
		}else{
			$id = $_REQUEST['ID'];
		}
		$clients = mysqli_query($con,"SELECT t.invoice,p.name,t.total_amount,(select sum(amount) from transaction_history th where th.tid=t.id and dispute='0' and th.pid=p.id) as paid, cast(t.created_at as date) as dated FROM `transactions` t,parties p where t.type='1' and t.dispute='0' and (t.status='1' or t.status='0') and t.product_id='{$id}' and p.status='1' and t.party_id=p.id");
		// remove or status to restrict
		while($result = mysqli_fetch_array($clients) ){
		
			?>
			<tr>
				<td><?=$result['invoice']?></td>
				<td><?=dbOUT($result['name'])?></td>
				<td><?=$result['total_amount']?></td>
				<td><?=$result['paid']?></td>
				<td><?=$result['dated']?></td>
			</tr>
			<?php 
		}
	?>
	</tbody>
			<tr>
				<th>Invoice #</th>
				<th>Party</th>
				<th>Price Total</th>
				<th>Price Paid</th>
				<th>Dated</th>
			</tr>
	</table>
</div>
<?php include "tFooter.php"; ?>

