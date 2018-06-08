<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
<style>
.hidden{
	display:none;
}
.visible{
	display:block;
}
</style>
<?php include "tHeader.php"; ?>

<h1 class="page-header">Manage Products</h1> 

<?php 


$companyQ = mysqli_query($con, "SELECT id,name from companies where status='1'");
$typeQ     = mysqli_query($con, "SELECT id,company_id as cid,name from product_types where status='1'");
$modelTypeQ = mysqli_query($con, "SELECT id,product_type_id as cid,name from product_models where status='1'");
if(isset($_GET['action']) && $_GET['action']=="delete" && $_GET['UID']!=''){
	$id = dbIN($_GET['UID']);
	mysqli_query($con,"DELETE FROM products WHERE id='{$id}'");
	set_message("Product DELETED");
	goBack();
}

if(!isset($_GET['action']) && $_GET['action']!="edit"){



?>
<div class="Client">
	<?=log_message();?>
	<form action="Save.php?function=addMainProduct" method="POST" />
	<p>
		<label for="company">Company:</label>
		<select name="company" class="company form-control" id="company">
			<option value="" selected="selected">--SELECT--</option>
				<?php 
				while($companyR = mysqli_fetch_array($companyQ)){
					?>
					<option value="<?=$companyR['id']?>" data-id="<?=$companyR['id']?>" data-type="company" class="companyHandler" >
						<?=dbOUT($companyR['name']);?>
					</option>
					<?php 
				}
				?>	
		</select>
	</p>
	<p class="type-container hidden">
		<label for="type">Type:</label>
		<select name="Type" class="type form-control" id="type">
			<option value="" selected="selected">--SELECT--</option>
				<?php 
				while($typeR = mysqli_fetch_array($typeQ)){
					?>
					<option value="<?=$typeR['id']?>" data-id="<?=$typeR['cid']?>" data-type="type" class="typeHandler hidden">
						<?=dbOUT($typeR['name']);?>
					</option>
					<?php 
				}
				?>	
		</select>
	</p>
	<p class="model-container hidden">
		<label for="model">Model:</label>
		<select name="Model" class="model form-control" id="model">
			<option value="" selected="selected">--SELECT--</option>
				<?php 
				while($modelR = mysqli_fetch_array($modelTypeQ)){
					?>
					<option value="<?=$modelR['id']?>" data-id="<?=$modelR['cid']?>" data-type="model" class="modelHandler hidden">
						<?=dbOUT($modelR['name']);?>
					</option>
					<?php 
				}
				?>	
		</select>
	</p>
	<p class="final hidden">
		<label for="price">Unit Price:</label>
		<input type="number" class="price form-control" placeholder="unit price" name="price" required="required" id="price"/>
		<input type="hidden" class="tname" name="tname" id="tname"/>
		<input type="hidden" class="cname" name="cname" id="cname"/>
		<input type="hidden" class="mname" name="mname" id="mname"/>
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p class="final hidden">
		<input type="submit" value="Save" class="btn btn-primary" name="save" />
	</p>
	</form>
</div>
<?php }else{ 
$id = $_GET['UID'];
$query = mysqli_query($con,"SELECT co.name as cname, pm.name as mname, pt.name as tname, p.company_id,p.product_type_id,p.product_model_id,p.unit_price FROM products p, companies co, product_models pm, product_types pt where p.id='{$id}' AND p.status='1' AND co.id=p.company_id AND pm.id=p.product_model_id AND pt.id=p.product_type_id");
$result = mysqli_fetch_array($query);
	?>
<div class="Client">
	<?=log_message();?>
<form action="Save.php?function=updateMainProduct&UID=<?=$id?>" method="POST" />
	<p>
		<label for="company">Company:</label>
		<select name="company" class="company form-control" id="company">
			<option value="" selected="selected">--SELECT--</option>
				<?php 
				while($companyR = mysqli_fetch_array($companyQ)){
					$selected = "";
					if($result['company_id']==$companyR['id']){
						$selected = "style='color:white;background:grey;' selected='selected'";
					}
					?>
					<option value="<?=$companyR['id']?>" data-id="<?=$companyR['id']?>" data-type="company" class="companyHandler" <?=$selected?> >
						<?=dbOUT($companyR['name']);?>
					</option>
					<?php 
				}
				?>	
		</select>
	</p>
	<p class="type-container visible">
		<label for="type">Type:</label>
		<select name="Type" class="type form-control" id="type">
			<option value="" selected="selected">--SELECT--</option>
				<?php 
				while($typeR = mysqli_fetch_array($typeQ)){
					$selected = "";
					$hidden = "hidden";
					if($result['product_type_id']==$typeR['id']){
						$selected = "style='color:white;background:grey;' selected='selected'";
						 $hidden = "visible";
					}
					?>
					<option value="<?=$typeR['id']?>" data-id="<?=$typeR['cid']?>" data-type="type" class="<?=$hidden?> typeHandler " <?=$selected?>>
						<?=dbOUT($typeR['name']);?>
					</option>
					<?php 
				}
				?>	
		</select>
	</p>
	<p class="model-container visible">
		<label for="model">Model:</label>
		<select name="Model" class="model form-control" id="model">
			<option value="" selected="selected">--SELECT--</option>
				<?php 
				while($modelR = mysqli_fetch_array($modelTypeQ)){
					$selected = "";
					$hidden = "hidden";
					if($result['product_model_id']==$modelR['id']){
						$selected = "style='color:white;background:grey;' selected='selected'";
						$hidden = "visible";
					}
					?>
					<option value="<?=$modelR['id']?>" data-id="<?=$modelR['cid']?>" data-type="model" class="modelHandler <?=$hidden?>" <?=$selected?>>
						<?=dbOUT($modelR['name']);?>
					</option>
					<?php 
				}
				?>	
		</select>
	</p>
	<p class="final visible">
		<label for="price">Unit Price:</label>
		<input type="number" class="price form-control" placeholder="unit price" name="price" required="required" id="price" value="<?=$result['unit_price']?>"/>
		<input type="hidden" class="tname" name="tname" id="tname" value="<?=$result['tname']?>"/>
		<input type="hidden" class="cname" name="cname" id="cname"value="<?=$result['cname']?>"/>
		<input type="hidden" class="mname" name="mname" id="mname" value="<?=$result['mname']?>"/>
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
	</p>
	<p class="final visible">
		<input type="submit" value="Update" class="btn btn-primary" name="save" />
		<a href="Products.php">
			<input type="button" role="button" value="Add new" class="btn btn-success" name="goBack" />
		</a>	
	</p>
</form>
</div>
<?php }

		$query = mysqli_query($con,"SELECT p.id, IFNULL( (
SELECT count( id )
FROM transactions tt
WHERE tt.type = '1'
AND tt.dispute = '0'
AND (tt.status = '1' or tt.status='0')
AND tt.product_id = p.id ) , 0
) AS stock, p.alias AS name, companies.name AS CompanyName, product_types.name AS ProductType, product_models.name AS ProductModel, p.unit_price
FROM products p
LEFT JOIN stocks ON stocks.product_id = p.id
LEFT JOIN companies ON p.company_id = companies.id
LEFT JOIN product_models ON p.product_model_id = product_models.id
LEFT JOIN product_types ON p.product_type_id = product_types.id
WHERE p.status = '1'");
 ?>

<br>
<h1 class="page-header">Records Of Products</h1> 
<div class="Server">
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Name</th>
				<th>Model</th>
				<th>Type</th>
				<th>Company</th>
				<th>Price</th>
				<th>Stock</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
				while($result = mysqli_fetch_array($query)){
			?>
			<tr>
				<td><?=$result['id']?></td>
				<td><?=dbOUT($result['name'])?></td>
				<td><?=dbOUT($result['ProductModel'])?></td>
				<td><?=dbOUT($result['ProductType'])?></td>
				<td><?=dbOUT($result['CompanyName'])?></td>
				<td><?=dbOUT($result['unit_price'])?></td>
				<td>
					<a href="ProductDetail.php?ID=<?=$result['id']?>">		<?=dbOUT($result['stock'])?>
					</a>
				</td>
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
				<th>Model</th>
				<th>Type</th>
				<th>Company</th>
				<th>Price</th>
				<th>Stock</th>
				<th>Action</th>
			</tr>
		</tfoot>
	</table>
</div>


<?php include "tFooter.php"; ?>
<script>
	$(function(){
		
		$(".companyHandler").each(function(){
			$pid = $(this).attr("data-id");
			console.log("finding cid for pid "+$pid);
			if($(".typeHandler[data-id='"+$pid+"']").length!=0){
			$(".typeHandler[data-id='"+$pid+"']").each(function(){
				$cid = $(this).attr("value");
				console.log("found cid "+$cid+ " for pid "+$pid);

				if($(".modelHandler[data-id='"+$cid+"']").length==0)
				{
					console.log("lock pid "+$pid+" for not having model on cid "+$cid);
					$(".companyHandler[data-id='"+$pid+"']").attr("disabled","disabled");
					$(".companyHandler[data-id='"+$pid+"']").append(" - NO MODEL");
				}
				if($(".typeHandler[data-id='"+$pid+"']").length==0)
				{
					console.log("lock pid "+$pid+" for not having type on cid "+$cid);
					$(".companyHandler[data-id='"+$pid+"']").attr("disabled","disabled");
					$(".companyHandler[data-id='"+$pid+"']").append(" - NO TYPE - NO MODEL");
				}
			});
		}else{
			console.log("lock pid "+$pid+" for not having type on cid "+$cid+" using direct");
			$(".companyHandler[data-id='"+$pid+"']").attr("disabled","disabled");
			$(".companyHandler[data-id='"+$pid+"']").append(" - NO TYPE - NO MODEL");
		}
		});

		$("#company").change(function(){
			$parentID = $(this).val();
			if($parentID!=''){
				$selectedHTML = $.trim($("#company option:selected").html());
				$("#cname").val($selectedHTML);
				
			}else{
				$("#cname").val('');
			}
			$("#mname,#tname").val('');
			console.log("Got Parent "+$selectedHTML+" with ID "+$parentID);
			$(".type-container").removeClass("hidden").addClass("visible");
			$(".typeHandler").addClass("hidden").removeClass("visible");
			if($(".typeHandler[data-id='"+$parentID+"']").length!=0){
				$(".typeHandler[data-id='"+$parentID+"']").removeClass("hidden").addClass("visible");
				$(".typeHandler:selected").removeAttr("selected");
			}else{
				$(".type-container").addClass("hidden").removeClass("visible");
				$(".typeHandler:selected").removeAttr("selected");
			}
			$(".model-container").addClass("hidden").removeClass("visible");
			$(".modelHandler:selected").removeAttr("selected");
			$(".final").addClass("hidden").removeClass("visible");
		});
		$("#type").change(function(){
			$parentID = $(this).val();
			console.log($parentID);
			if($parentID!=''){
				$selectedHTML = $.trim($("#type option:selected").html());
				$("#tname").val($selectedHTML);
			}else{
				$("#tname").val('');
			}
			$("#mname").val('');
			console.log("Got Child "+$selectedHTML+" with ID "+$parentID);
			$(".model-container").removeClass("hidden").addClass("visible");
			$(".modelHandler").addClass("hidden").removeClass("visible");
			$(".final").addClass("hidden").removeClass("visible");
			if($(".modelHandler[data-id='"+$parentID+"']").length!=0){
				$(".modelHandler[data-id='"+$parentID+"']").removeClass("hidden").addClass("visible");
			}else{
				$(".model-container").addClass("hidden").removeClass("visible");
				$(".modelHandler:selected").removeAttr("selected");
			}
			$(".modelHandler:selected").removeAttr("selected");
		});
		$("#model").change(function(){
			$parentID = $(this).val();
			if($parentID!=''){
				$selectedHTML = $.trim($("#model option:selected").html());
				$("#mname").val($selectedHTML);
			}else{
				$("#mname").val('');
			}
			console.log("Got Model "+$selectedHTML+" with ID "+$parentID);
			if($(this).val()!=""){
				$(".final").addClass("visible").removeClass("hidden");
			}else{
				$(".final").addClass("hidden").removeClass("visible");
			}
		});
		$("#model").change();
	});

</script>