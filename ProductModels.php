<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>

<h1 class="page-header">Manage Product Models</h1> 

<?php 

if(isset($_GET['action']) && $_GET['action']=="delete" && $_GET['UID']!=''){
	$id = dbIN($_GET['UID']);
	mysqli_query($con,"DELETE FROM product_models WHERE id='{$id}'");
	set_message("Product Model DELETED");
	goBack();
}
$lQ = mysqli_query($con, "SELECT pt.`id`,pt.`name`,co.`name` as company_name,pt.`company_id` from product_types pt, companies co where pt.status='1' and `pt`.`company_id` = `co`.`id`");

if(!isset($_GET['action']) && $_GET['action']!="edit"){
?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=addProductModel" method="POST" />
	<p>
		<input type="text" placeholder="By name" name="bname" class="form-control">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<select name="btype" class="form-control">
			<?php 
			while($list = mysqli_fetch_array($lQ)){
				?>
				<option value="<?=$list['id'];?>"><?=dbOUT($list['name']);?> - <?=dbOUT($list['company_name'])?></option>
				<?php 
			}
			?>
		</select>
	</p>
	<p>
		<input type="submit" class="btn btn-primary" value="Save" name="bsave" />
	</p>
</form>
</div>
<?php }else{ 
$id = $_GET['UID'];
$query = mysqli_query($con,"SELECT pm.id, pm.name as Product_Model, pm.product_type_id as pid, pt.name Product_Type FROM product_models pm, product_types pt WHERE pm.id='{$id}' AND pt.id=pm.product_type_id AND pm.status='1'");
$result = mysqli_fetch_array($query);

	?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=updateProductModel&UID=<?=$id?>" method="POST" />
	<p>
		<input type="text" placeholder="By name" name="bname" class="form-control" value="<?=dbOUT($result['Product_Model'])?>">
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p>
		<select name="btype" class="form-control">
			<?php 
			
			while($list = mysqli_fetch_array($lQ)){
				$selected = "";
				echo $result['pid'].";;".$list['id']."::"; 
				
				if(($result['pid']==$list['id'])==1){
					$selected="style='background:grey;color:white;' selected='selected'";
				}
				?>
				<option value="<?=$list['id'];?>" <?=$selected?>><?=dbOUT($list['name']);?></option>
				<?php 
			}
			?>
		</select>
	</p>
	<p>
		<input type="submit" class="btn btn-success" value="Update" name="bupdate" />
		<a href="ProductModels.php">
			<button  type="button" class="btn btn-info">Add new</button>
		</a>	
	</p>
</form>
</div>
<?php }

 ?>

<br>
<h1 class="page-header">Records Of Product Models</h1> 
<div class="Server">
	<?php 
		$query = mysqli_query($con,"SELECT co.name as company, pm.id, pm.name as Product_Model, pt.name Product_Type FROM product_models pm, product_types pt,companies co WHERE pm.product_type_id=pt.id AND pm.status='1' AND co.id=pt.company_id");

	?>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Model</th>
				<th>Type</th>
				<th>Company</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				while($result = mysqli_fetch_array($query)){
			?>
			<tr>
				<td><?=$result['id'];?>
				<td><?=dbOUT($result['Product_Model'])?></td>
				<td><?=dbOUT($result['Product_Type'])?></td>
				<td><?=dbOUT($result['company']);?></td>
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
				<th>Model</th>
				<th>Type</th>
				<th>Company</th>
				<th>Action</th>
			</tr>
		</tfoot>
	</table>
</div>


<?php include "tFooter.php"; ?>