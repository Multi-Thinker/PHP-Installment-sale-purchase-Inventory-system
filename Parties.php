<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>

<h1 class="page-header">Manage Parties</h1> 

<?php 

if(isset($_GET['action']) && $_GET['action']=="delete" && $_GET['UID']!=''){
	$id = dbIN($_GET['UID']);
	mysqli_query($con,"DELETE FROM parties WHERE id='{$id}'");
	set_message("Party DELETED");
	goBack();
}

if(!isset($_GET['action']) && $_GET['action']!="edit"){
?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=addParty" method="POST" />
	<p>
		<input type="text" placeholder="Name" name="bname" class="form-control">
	</p>
	<p>
		<input type="text" placeholder="Phone" name="bphone" class="phone_mask form-control">
	</p>
	<p>
		<input type="text" placeholder="Fax" name="bfax" class="phone_mask form-control">
	</p>
	<p>
		<input type="text" placeholder="Address" name="baddr" class="form-control">
	</p>
	<p>
		<input type="email" placeholder="Email" name="bemail" class="form-control">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<input type="submit" class="btn btn-primary" value="Save" name="bsave" />
	</p>
</form>
</div>
<?php }else{ 
$id = $_GET['UID'];
$query = mysqli_query($con,"SELECT name,email,phone,fax,address FROM parties where id='".$id."' AND status=1");
$result = mysqli_fetch_array($query);
	?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=updateParty&UID=<?=$id?>" method="POST" />
	<p>
		<input type="text" placeholder="Name" name="bname" class="form-control" value="<?=dbOUT($result['name'])?>">
	</p>
	<p>
		<input type="text" placeholder="Phone" name="bphone" class="form-control phone_mask" value="<?=dbOUT($result['phone'])?>"> 
	</p>
		<p>
		<input type="text" placeholder="Fax" name="bfax" value="<?=dbOUT($result['fax'])?>" class="form-control phone_mask">
	</p>
	<p>
		<input type="text" placeholder="Address" value="<?=dbOUT($result['address'])?>" name="baddr" class="form-control">
	</p>
	<p>
	<p>
		<input type="email" placeholder="Email" name="bemail" class="form-control" value="<?php echo dbOUT($result['email']); ?>">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<input type="submit" class="btn btn-success" value="Update" name="bupdate" />
		<a href="Parties.php">
			<button  type="button" class="btn btn-info">Add new</button>
		</a>	
	</p>
</form>
</div>
<?php }

 ?>

<br>
<h1 class="page-header">Records Of Parties</h1> 
<div class="Server">
	<?php 
		$query = mysqli_query($con,"SELECT p.id,p.name,p.phone,p.email,p.fax,p.address,(select count(id) from transactions t where trans_type='0'  AND status='0' and t.party_id=p.id AND t.dispute='0') as purchase, (select count(id) from transactions t where trans_type='1' AND status='0' and t.party_id=p.id and t.dispute='0') as sales FROM parties p where p.status='1'");

	?>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Fax</th>
				<th>Address</th>
				<th>Sales in progress</th>
				<th>Purchase in progress</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				while($result = mysqli_fetch_array($query)){
			?>
			<tr>
				<td><?=dbOUT($result['id'])?></td>
				<td><?=dbOUT($result['name'])?></td>
				<td><?=dbOUT($result['phone'])?></td>
				<td><?=dbOUT($result['email'])?></td>
				<td><?=dbOUT($result['fax'])?></td>
				<td><?=dbOUT($result['address'])?></td>
				<td><a href="Transactions.php?type=sales&party=<?=$result['id']?>"><?=$result['sales']?></a></td>
				<td><a href="Transactions.php?type=purchase&party=<?=$result['id']?>"><?=$result['purchase']?></a></td>
				<th>
					<p>
						<a href="?action=edit&UID=<?=$result['id'];?>">EDIT</a>
					</p>
					<p>
						<a href="javascript:void(0);" onclick="getConfirmation('Are you sure you want to delete?',<?=$result['id'];?>);">DELETE</a>
					</p>
				</th>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Fax</th>
				<th>Address</th>
				<th>Sales in progress</th>
				<th>Purchase in progress</th>
				<th>Action</th>
			</tr>
		</tfoot>
	</table>
</div>


<?php include "tFooter.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.13/jquery.mask.min.js"></script>
<script>
	$(function(){
		$(".phone_mask").mask("(0-000)-000000000000");
	})
</script>