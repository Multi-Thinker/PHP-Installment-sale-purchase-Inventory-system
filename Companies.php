<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>

<h1 class="page-header">Manage Companies</h1> 

<?php 

if(isset($_GET['action']) && $_GET['action']=="delete" && $_GET['UID']!=''){
	$id = dbIN($_GET['UID']);
	mysqli_query($con,"DELETE FROM companies WHERE id='{$id}'");
	set_message("Company DELETED");
	goBack();
}

if(!isset($_GET['action']) && $_GET['action']!="edit"){
?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=addCompany" method="POST" />
	<p>
		<input type="text" placeholder="By name" name="bname" class="form-control">
	</p>
	<p>
		<input type="mobile" placeholder="By phone" name="bphone" class="form-control">
	</p>
	<p>
		<input type="fax" placeholder="By Fax" name="bfax" class="form-control">
	</p>
	<p>
		<input type="address" placeholder="By Address" name="baddr" class="form-control">
	</p>
	<p>
		<input type="text" placeholder="By owner" name="bowner" class="form-control">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<input type="submit" class="btn btn-primary" value="Save" name="bsave" />
	</p>
</form>
</div>
<?php }else{ 
$id = $_GET['UID'];
$query = mysqli_query($con,"SELECT name,owner,phone,fax,address FROM companies where id='".$id."' AND status=1");
$result = mysqli_fetch_array($query);
	?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=updateCompany&UID=<?=$id?>" method="POST" />
	<p>
		<input type="text" placeholder="By name" name="bname" class="form-control" value="<?=dbOUT($result['name'])?>">
	</p>
	<p>
		<input type="mobile" placeholder="By phone" name="bphone" class="form-control" value="<?=dbOUT($result['phone'])?>">
	</p>
		<p>
		<input type="fax" placeholder="By Fax" name="bfax" value="<?=dbOUT($result['fax'])?>" class="form-control">
	</p>
	<p>
		<input type="address" placeholder="By Address" value="<?=dbOUT($result['address'])?>" name="baddr" class="form-control">
	</p>
	<p>
	<p>
		<input type="text" placeholder="By owner" name="bowner" class="form-control" value="<?php echo dbOUT($result['owner']); ?>">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<input type="submit" class="btn btn-success" value="Update" name="bupdate" />
		<a href="Companies.php">
			<button  type="button" class="btn btn-info">Add new</button>
		</a>	
	</p>
</form>
</div>
<?php }

 ?>

<br>
<h1 class="page-header">Records Of Companies</h1> 
<div class="Server">
	<?php 
		$query = mysqli_query($con,"SELECT id,name,owner,phone,fax,address FROM companies where `status`='1'");

	?>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Owner</th>
				<th>Phone</th>
				<th>Fax</th>
				<th>Address</th>
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
				<td><?=dbOUT($result['owner'])?></td>
				<td><?=dbOUT($result['phone'])?></td>
				<td><?=dbOUT($result['fax'])?></td>
				<td><?=dbOUT($result['address'])?></td>
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
				<th>Owner</th>
				<th>Phone</th>
				<th>Fax</th>
				<th>Address</th>
				<th>Action</th>
			</tr>
		</tfoot>
	</table>
</div>


<?php include "tFooter.php"; ?>