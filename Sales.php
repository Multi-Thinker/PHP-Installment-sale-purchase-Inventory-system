<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
if(isset($_GET['action']) && $_GET['action']=="delete"){
	$id = dbIN($_GET['UID']);
	$sale_tid = mysqli_query($con,"select sale_tid from transactions where id='{$id}'");
	$sale_tid = mysqli_fetch_assoc($sale_tid);
	$sale_tid = $sale_tid['sale_tid'];
	echo $sale_tid;
	mysqli_query($con,"UPDATE transactions SET type='1' where id ='{$sale_tid}';");
	mysqli_query($con,"DELETE FROM transactions WHERE id='{$id}'");
	mysqli_query($con,"DELETE FROM transaction_history WHERE tid='{$id}'");
	set_message("Sale DELETED");
	goBack();
}
?>
<?php include "tHeader.php"; ?>

<h1 class="page-header">Sales</h1>  
<?=log_message();?>
<center>
	<a href="Sale.php"><button class="btn btn-info btn-lg">Add New</button></a>
</center>
<hr>
<?php 
		$query = mysqli_query($con,"SELECT c.name as client,t.client_id as cid,t.product_id as pid, t.type,pat.id as party_id, pat.name as party,t.created_at,t.status,t.dispute,t.id,IFNULL(t.invoice,0) as invoice,t.party_id,t.total_amount,t.paid_amount,
		p.alias,(select sum(th.amount) from transaction_history th where th.tid=t.id) as other_paid FROM `transactions` t, products p, parties pat, clients c where t.product_id=p.id AND pat.id=t.party_id and t.trans_type='1' and t.client_id=c.id");

	?>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Invoice</th>
				<th>Client</th>
				<th>Product</th>
				<th>Party</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Status</th>
				<th>DATE</th>
				<th>Action</th> 
			</tr>
		</thead>
		<tbody>
			<?php 
				while($result = mysqli_fetch_array($query)){
			?>
			<tr>
				<td><?=dbOUT($result['id'])?></td>
				<td><?=$result['invoice']?></td>
				<td><a href="editClient.php?UID=<?=$result['cid']?>"><?=dbOUT($result['client'])?></a></td>
				
				<td><a href="Products.php?action=edit&UID=<?=$result['pid']?>"><?=dbOUT($result['alias'])?></a></td>
				
				<td><a href="Parties.php?action=edit&UID=<?=$result['party_id']?>"><?=dbOUT($result['party'])?></a></td>
				<td><?=dbOUT($result['total_amount'])?></td>
				<td><?=dbOUT($result['other_paid'])?></td>
				<td><?php 
				if($result['type']!="3"){
					if($result['status']=="0"){
						if($result['dispute']=="0"){
							echo "<span style='color:blue'><a href='addFunds.php?tid={$result['id']}&type=sales&client={$result['cid']}&invoice={$result['invoice']}'>PENDING</a></span>";
						}else{
							echo "<b><span style='color:red'><a href='addFunds.php?tid={$result['id']}&type=sales&client={$result['cid']}&invoice={$result['invoice']}'>DISPUTED</a></span></b>";
						}
					}else{
						if($result['dispute']=="0"){
							echo "<span style='color:green'><a href='addFunds.php?tid={$result['id']}&type=sales&client={$result['cid']}&invoice={$result['invoice']}'>FULFILLED</a></span>";
						}else{
							echo "<b><span style='color:red'><a href='addFunds.php?tid={$result['id']}&type=sales&client={$result['cid']}&invoice={$result['invoice']}'>DISPUTED</a></span></b>";
						}	
					}
				}else{
					echo "<span style='color:green'><a href='addFunds.php?tid={$result['id']}&type=sales&client={$result['cid']}&invoice={$result['invoice']}'>SOLD</a></span>";
				}
				?></td>
				<td><?=dbOUT($result['created_at'])?></td>

			 	<th>
			 		<?php 
			 		if($result['dispute']=="0" && $result['type']!='3'){
			 		?>
					<p>
						<a href="editSale.php?action=edit&UID=<?=$result['id'];?>">EDIT</a>
					</p>
					<p>
						<a href="javascript:void(0);" onclick="getConfirmation('Are you sure you want to delete?',<?=$result['id'];?>);">DELETE</a>
					</p>
					<?php } else{ echo "NOT AVAILABLE"; } ?>
				</th> 
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Invoice</th>
				<th>Client</th>
				<th>Product</th>
				<th>Party</th>
				<th>Total</th>
				<th>Paid</th>
				<th>Status</th>
				<th>DATE</th>
				<th>Action</th> 
			</tr>
		</tfoot>
	</table>


<?php include "tFooter.php"; ?>