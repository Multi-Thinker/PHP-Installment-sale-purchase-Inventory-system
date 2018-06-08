<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>

<h1 class="page-header">Manage Product Types</h1> 

<?php 
$company = mysqli_query($con, "SELECT id,name FROM companies where status='1'");

if(isset($_GET['action']) && $_GET['action']=="delete"){
	$id = dbIN($_GET['UID']);
	mysqli_query($con,"DELETE FROM product_types WHERE id='{$id}'");
	set_message("Product Type DELETED");
	goBack();
}

if(!isset($_GET['action']) && $_GET['action']!="edit"){
?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=addProductType" method="POST" />
	<p>
		<input type="text" placeholder="By name" name="bname" class="form-control">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<select class="form-control company" name="company">
			<?php 
			while($resultC = mysqli_fetch_array($company)){
				?>
			<option id="<?=$resultC['id']?>" value="<?=$resultC['id']?>">
				<?=dbOUT($resultC['name']);?>
			</option>
			<?php } ?>
		</select> 
	</p>
	<p>
		<input type="submit" class="btn btn-primary" value="Save" name="bsave" />
	</p>
</form>
</div>
<?php }else{ 
$id = $_GET['UID'];
$query = mysqli_query($con,"SELECT name,company_id FROM product_types where id='".$id."' AND status=1");
$result = mysqli_fetch_array($query);
	?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=updateProductType&UID=<?=$id?>" method="POST" />
	<p>
		<input type="text" placeholder="By name" name="bname" class="form-control" value="<?=dbOUT($result['name'])?>">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<select class="form-control company" name="company">
			<?php 
			while($resultC = mysqli_fetch_array($company)){
				$selected = '';
				if($result['company_id']==$resultC['id']){
					$selected="style='background:grey;color:white;' selected='selected'";
				}
				?>
			<option id="<?=$resultC['id']?>" value="<?=$resultC['id']?>" <?=$selected?>>
				<?=dbOUT($resultC['name']);?>
			</option>
			<?php } ?>
		</select> 
	</p>
	<p>
		<input type="submit" class="btn btn-success" value="Update" name="bupdate" />
		<a href="ProductType.php">
			<button  type="button" class="btn btn-info">Add new</button>
		</a>	
	</p>
</form>
</div>
<?php }

 ?>

<br>
<h1 class="page-header">Records Of Product Types</h1> 
<div class="Server">
	<?php 
		$query = mysqli_query($con,"SELECT pt.name, pt.id, co.name as company_id FROM `product_types` pt, `companies` co where pt.company_id=co.id AND pt.status='1';");

	?>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Company</th>
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
				<td><?=dbOUT($result['company_id']);?></td>
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
				<th>Company</th>
				<th>Action</th>
			</tr>
		</tfoot>
	</table>
</div>


<?php include "tFooter.php"; ?>