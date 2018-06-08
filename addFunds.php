<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php";
$userInfo = userInfo();
?>
<style>
.showables{
	display:none;
	padding:0px;
	margin: auto;

}
.bottomline{
	position: absolute;
	bottom:0px;
}
@media print{
	label,textarea,.form-control,input,button,a,nav,.dataTables_filter,.dataTables_length,.dataTables_info,.dataTables_paginate,.hideables,.sorting::after,.sorting_asc::after,.sorting_desc::after{
		display:none !important;
	}
	.showables{
		display:block !important;
	}
}
</style>
<center class="showables">
	<img src="./image/zb.png" width="200px" align="center" /><br>
	<span><?=date("D d-M-Y")?></span>
</center>
<?php include "tHeader.php"; ?>
<?php
$id = checkRequest('tid');
$type = checkRequest('type');
$invoice = checkRequest('invoice');
$is_client = checkRequest("client")=='' ? false : checkRequest("client");

switch($type){
	case 'purchase':
?>
<h2 class="page-header hideables"><span class="hideables">Funds to</span> Invoice# <?=$invoice?></h2>
<div class="Server">
<?=log_message();?>
<?php
	$party_query = mysqli_query($con,"SELECT th.id,th.dispute,t.invoice,t.total_amount,th.updated,th.amount,IFNULL((SELECT sum(th.amount) from transaction_history th where th.tid=t.id),0) as paid from transactions t left join transaction_history th on th.tid=t.id and th.type='0'
	WHERE t.id='{$id}'
	ORDER BY th.id;");
	$detail = mysqli_query($con,"SELECT (select name from clients cc where cc.id=t.client_id) as client,t.client_id, pat.name as party,pat.id as pid, p.alias as product, t.custom_attribute,t.dispute as global_dispute, t.chasing, t.total_amount as price, (select sum(th.amount) from transaction_history th where th.tid=t.id AND th.dispute='0') as paid from parties pat, products p, transactions t WHERE t.party_id=pat.id AND t.product_id=p.id AND t.invoice='{$invoice}' and t.type='1'");
	$fetch = mysqli_fetch_array($detail);
	?>

	<form action="Save.php?function=addFunds" method="POST">
	<table class="table table-striped">
		<tr >
			<th class="hideables" width="20%">Party</th><th width="20%">Product</th><th width="20%">Chasing</th>
			<?php
				if($fetch['client']!=''){
			?>
			<th width="20%">Client name</th>
			<?php }  ?>
		</tr>
		<tr>
			<td class="hideables" align="left"><?=dbOUT($fetch['party'])?></td>
			<td align="left"><?=dbOUT($fetch['product'])?></td>
			<td align="left"><?=dbOUT($fetch['chasing'])?></td>
			<?php
				if($fetch['client']!=''){
			?>
			<td align="left"><?=dbOUT($fetch['client'])?></td>
		<?php } ?>
		</tr>

<!--		<tr>
			<th width="20%">Net Price</th><td align="left"><?=dbOUT($fetch['price'])?></td>
		</tr>  -->

	</table>


	<?php
		if($fetch['custom_attribute']!='[]' && $fetch['custom_attribute']!=''){
		$headings = array();
		$body = array();
		$custom_value = json_decode($fetch['custom_attribute']);
		foreach($custom_value as $heading => $data){
			$headings[] = $heading;
			$body[] = $data;
		}
	?>
	<p>
		<label>Features</label>
		<table class="table table-striped">
		<tbody>
			<?php
			foreach($headings as $key => $heading){
			?>
			<tr>
			<th width="20%"><?=$heading?></th><td align="left"><?=$body[$key]?></td>
			</tr>
		<?php } ?>
		</tbody>

		</table>
	</p>
		<?php } ?>
		<?php
			if(($fetch['price']-$fetch['paid'])!=0){
		?>
	<p>
		<label>Add Fund</label>
		<input type="number" class="form-control" required="required" min="1" max="<?=$fetch['price']-$fetch['paid']?>" name="amount" placeholder="Maximum <?=$fetch['price']-$fetch['paid']?>" />
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
		<input type="hidden" name="tid" value="<?=$id?>" />
		<input type="hidden" name="pid" value="<?=$fetch['pid']?>" />
	</p>
	<p>

		<input type="submit" class="btn btn-primary" value="ADD" />
		<?php
		if($fetch['global_dispute']=="0"){
		?>
		<a href="Save.php?function=disputeProduct&tid=<?=$id?>&csrf_token=<?=$csrf_token?>&status=<?=$fetch['global_dispute']?>">
			<input type="button" role="button" class="btn btn-danger" VALUE="DISPUTE PRODUCT" />
			<br>
		</a>
		<?php }else{ ?>
		<a href="Save.php?function=disputeProduct&tid=<?=$id?>&csrf_token=<?=$csrf_token?>&status=<?=$fetch['global_dispute']?>">
			<input type="button" role="button" class="btn btn-success" VALUE="APPROVE PRODUCT" />
			<br>
		</a>
		<?php } ?>

	</p>
		<?php } else{ echo '<h2 style="color:green">PAYMENT SATTLED</h2>'; } ?>
		<button class="btn btn-primary" role="button" type="button" onclick="javascript:history.back();">Go Back</button>
	</form>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Invoice</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>DATE</th>
				<th class="hideables">STATUS</th>
				<?php
				if($fetch['global_dispute']=="0"){
				?>
				<th class="hideables">ACTION</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$amount = 0;
				while($result = mysqli_fetch_array($party_query)){
					if($result['dispute']=="0"){
			?>
			<tr>
				<td><?=$result['id']?></td>
				<td><?=$result['invoice']?></td>
				<td><?=$result['total_amount']?></td>
				<td><?=$result['amount']?></td>
				<td><?php

				if($amount>0){
					$amount = $amount-$result['amount'];
				}else{
					$amount = $result['total_amount']-$result['amount'];
				}

				echo $amount;
				?></td>
				<td><?=$result['updated']?></td>
				<td class="hideables"><?php
				if($result['dispute']!="0"){
					echo "<b><span style='color:red'>DISPUTED</span></b>";
				}else{
					echo "<b><span style='color:green'>PROCESSED</span></b>";
				}
				?>
				<?php
				if($fetch['global_dispute']=="0"){
				?>
				<td class="hideables">
				<?php
					if($result['dispute']!="0"){
					?>
					<a href="Save.php?function=addDispute&did=<?=$result['id']?>&csrf_token=<?=$csrf_token?>&status=<?=$result['dispute']?>&tid=<?=$id?>" style="color:blue">COUNT IN</a>
					<?php
					}else{
					?>
					<a href="Save.php?function=addDispute&did=<?=$result['id']?>&csrf_token=<?=$csrf_token?>&status=<?=$result['dispute']?>&tid=<?=$id?>" style="color:red">COUNT OUT</a>
					<?php
				}
				?>

				</td>
				<?php } ?>
			</tr>

		<?php } } ?>

		</tbody>
		<tfoot class="hideables">
			<tr>
				<th>ID</th>
				<th>Invoice</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>DATE</th>
				<th class="hideables">STATUS</th>
				<?php
				if($fetch['global_dispute']=="0"){
				?>
				<th class="hideables">ACTION</th>
				<?php } ?>
			</tr>
		</tfoot>
	</table>
</div>
<?php
break;
case 'sales':
?>
<h2 class="page-header hideables"><span class="hideables">Funds to</span> Invoice# <?=$invoice?></h2>
<div class="Server">
<?=log_message();?>
<?php
	$party_query = mysqli_query($con,"SELECT th.Remarks, th.reciept, th.id,th.dispute,t.invoice,
		t.total_amount,th.updated,th.amount,
		IFNULL((SELECT sum(th.amount) from
		 transaction_history th where th.tid=t.id and th.dispute='0'),0) as paid
	 from transactions t left join transaction_history th on th.tid=t.id and th.type='1'
	WHERE t.id='{$id}' and th.dispute='0'	ORDER BY th.id");

	$detail = mysqli_query($con,"SELECT (select ttt.custom_attribute from transactions ttt where ttt.id=t.sale_tid) as custom_attribute, c.name as client, pat.name as party,pat.id as pid, p.alias as product,t.dispute as global_dispute, t.total_amount as price, (select sum(th.amount) from transaction_history th where th.tid=t.id AND th.type='2' AND th.dispute='0') as paid,(select ttt.chasing from transactions ttt where ttt.id=t.sale_tid) as chasing from parties pat, products p, transactions t, clients c WHERE t.party_id=pat.id AND t.product_id=p.id AND t.invoice='{$invoice}' and t.client_id=c.id and t.type='2'");
	$fetch = mysqli_fetch_array($detail);
	?>
	<form action="Save.php?function=addFunds" method="POST">
	<table class="table table-striped">
		<tr class="hideables">
			<th width="20%">Party</th>
			<th width="20%">Product</th>
			<th width="20%">Chasing</th>
			<th width="20%">Client Name</th>
			<?php
				if($is_client){
					?>
					<th width="20%">Account Number</th>
				<?php } ?>
		</tr>
		<tr>
			<td align="left"><?=dbOUT($fetch['party'])?></td>
			<td align="left"><?=dbOUT($fetch['product'])?></td>
			<td align="left"><?=dbOUT($fetch['chasing'])?></td>
			<td align="left"><?=dbOUT($fetch['client'])?></td>
			<?php
				if($is_client){
					?>
					<td align="left"><?=$invoice?></td>
				<?php } ?>
		</tr>

				<?php
		if($fetch['custom_attribute']!='[]' && $fetch['custom_attribute']!='[]' && $fetch['custom_attribute']!=''){
			?>

		<tr>
			<th>Attributes</th>

			<td colspan="5">
				<table class='table'>
					<?php
						$arr = json_decode($fetch['custom_attribute']);
						foreach($arr as $name => $value){
					?>
					<tr>
						<th><?=$name?></th><td><?=$value?></td>
					</tr>
					<?php } ?>
				</table>
			</td>
		</tr>
		<?php } ?>
	<!--	<tr>
			<th width="20%">Net Price</th><td align="left"><?=dbOUT($fetch['price'])?></td>
		</tr>  -->
	</table>
		<?php
			if(($fetch['price']-$fetch['paid'])!=0){
		?>
	<p>
		<label>Add Fund</label>
		<input type="number" class="form-control" required="required" min="1" max="<?=$fetch['price']-$fetch['paid']?>" name="amount" placeholder="Maximum <?=$fetch['price']-$fetch['paid']?>" />
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
		<input type="hidden" name="tid" value="<?=$id?>" />
		<input type="hidden" name="pid" value="<?=$fetch['pid']?>" />
		<input type="hidden" name="type" value="1">
	</p>
	<p>
		<label>Reciept Number</label>
		<input type='text' name="reciept" class="form-control" placeholder="Reciept Number" />
	</p>
	<p>
		<label>Remarks</label>
		<textarea name="Remarks" class="form-control" placeholder="Remarks: Payment Collected by XYZ, 123 Remaining"></textarea>
	</p>
	<p>
		<a onclick="javascript:history.back();"
		><button type="button" role="button" class='btn btn-primary'>Go Back</button></a>
		<input type="submit" class="btn btn-primary" value="ADD" />
		<?php
		if($fetch['global_dispute']=="0"){
		?>
		<a href="Save.php?function=disputeProduct&tid=<?=$id?>&csrf_token=<?=$csrf_token?>&status=<?=$fetch['global_dispute']?>">
			<input type="button" role="button" class="btn btn-danger" VALUE="DISPUTE PRODUCT" />
			<br>
		</a>
		<?php }else{ ?>
		<a href="Save.php?function=disputeProduct&tid=<?=$id?>&csrf_token=<?=$csrf_token?>&status=<?=$fetch['global_dispute']?>">
			<input type="button" role="button" class="btn btn-success" VALUE="APPROVE PRODUCT" />
			<br>
		</a>
		<?php } ?>

	</p>
		<?php } else{ echo '<h2 style="color:green">PAYMENT SATTLED</h2>'; } ?>
	</form>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>DATE</th>
				<th>Reciept</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>
				<th>Remarks</th>
				<th class="hideables">STATUS</th>
				<?php
				if($fetch['global_dispute']=="0"){
				?>
				<th class="hideables">ACTION</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$amount = 0;
			$oldAm = 0;
			$oldPaid = 0;
			$paid = 0;
			$firstAttempt = 0;
				while($result = mysqli_fetch_array($party_query)){

					if($amount>0){
						$amount = $amount-$result['amount'];
					}else{
						$amount = $result['total_amount']-$result['amount'];
					}

					if($firstAttempt==0){
						$paid = $result['total_amount'];
						$OldAm = $result['amount'];
					}else{
						$paid = $paid - $OldAm;
						$OldAm = $result['amount'];
					}
					$firstAttempt++;
					if($result['dispute']=="0"){
			?>
			<tr>
				<td><?=$result['id']?></td>
				<td><?=$result['updated']?></td>
				<td><?=dbOUT($result['reciept'])?></td>
				<td><?=$paid?></td>
				<td><?=$result['amount']?></td>
				<td><?php

				echo $amount;
				?></td>

				<td><?=dbOUT($result['Remarks'])?></td>

				<td  class="hideables"><?php
				if($result['dispute']!="0"){
					echo "<b><span style='color:red'>DISPUTED</span></b>";
				}else{
					echo "<b><span style='color:green'>PROCESSED</span></b>";
				}
				?>
				<?php
				if($fetch['global_dispute']=="0"){
				?>
				<td class="hideables">
				<?php
					if($result['dispute']!="0"){
					?>
					<a href="Save.php?function=addDispute&did=<?=$result['id']?>&csrf_token=<?=$csrf_token?>&status=<?=$result['dispute']?>&tid=<?=$id?>" style="color:blue">COUNT IN</a>
					<?php
					}else{
					?>
					<a href="Save.php?function=addDispute&did=<?=$result['id']?>&csrf_token=<?=$csrf_token?>&status=<?=$result['dispute']?>&tid=<?=$id?>" style="color:red">COUNT OUT</a>
					<?php
				}
				?>

				</td>
				<?php } ?>
			</tr>

		<?php } } ?>

		</tbody>
		<tfoot class="hideables">
			<tr>
				<th>ID</th>
				<th>DATE</th><th>Reciept</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Remaining</th>

				<th>Remarks</th>

				<th class="hideables">STATUS</th>
				<?php
				if($fetch['global_dispute']=="0"){
				?>
				<th class="hideables">ACTION</th>
				<?php } ?>
			</tr>
		</tfoot>
	</table>
	<br class="showables bottomline">
	<span class="pull-right showables">Customer Sign: ___________________</span>
	<span class="pull-left showables">Seller Sign: ___________________</span>
</div>
<?php
break;
default:
set_message("Wrong choice");
goBack();
break;
}
?>

<?php include "tFooter.php"; ?>
