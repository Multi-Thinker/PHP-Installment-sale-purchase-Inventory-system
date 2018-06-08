<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
 <!-- <link href="css/bootstrap-theme.min.css" rel="stylesheet"> -->
<?php include "tHeader.php"; ?>

<h1 class="page-header">Manage Purchases</h1> 

<?php 

$productQ = mysqli_query($con, "SELECT id,alias,unit_price FROM products where status='1'");
if(isset($_GET['action']) && $_GET['action']=="delete"){
	$id = dbIN($_GET['UID']);
	mysqli_query($con,"DELETE FROM transactions WHERE id='{$id}'");
	set_message("Purchase DELETED");
	goBack();
}

if(!isset($_GET['action']) && $_GET['action']!="edit"){

$getlastInvoiceQ = mysqli_query($con,"SELECT invoice from transactions order by id DESC limit 1");
$getlastInvoiceR = mysqli_fetch_assoc($getlastInvoiceQ);
$lastInvoice     = intval(str_replace("P","",str_replace("S","",$getlastInvoiceR['invoice'])))+1;
?>
<div class="Client">
		
	<?=log_message();?>
	<!--   -->
	<form  action="Save.php?function=addPurchase" method="POST" />

	<p>
	<div class="form-group">
		<label class="sr-only" for="invoice">INVOICE #:</label>
		    <div class="input-group">
		      <div class="input-group-addon">P</div>
		      <input type="number" pattern="[0-9]" id="invoice" placeholder="INVOICE #" value="<?=$lastInvoice?>" readonly="readonly" name="invoice" class="form-control" aria-label="Invoice" aria-describedby="basic-addon1" />
		    </div>
  	</div>	
	</p>
	<p>
		<label for="purchaset">Purchase Type #:</label>
		<select name="purchaset" id="purchaset" class="form-control" required="required" readonly="readonly">
			<option value="1" >Cash</option>
		</select>
	</p>
	<div class="addAble">
	<p>
	
		<label for="sExtpro">Select Existing Product</label>
		<a href="javascript:void(0);" class="removeExtProduct pull-right">Remove</a>
		<select class="form-control mainProductSelector" required="required" id="sExtpro" name="product[0][id]" data-count="0" for="sExtpro">
			<option value="">---SELECT---</option>
			<?php
				while($products = mysqli_fetch_array($productQ)){
			?>
			<option value="<?=$products['id']?>">
				<?=dbOUT($products['alias'])?>
			</option>
			<?php } ?>
		</select>
	</p>
	</div>
	<p>
		<label for="part">party</label>
		<select name="part" class="form-control" required="required">
			<option value="">--SELECT--</option>
			<?php 
			$qq = mysqli_query($con,"SELECT id,name from parties where status='1'");
			while ($rr = mysqli_fetch_array($qq)) {
				?>
				<option value="<?=$rr['id']?>">
					<?=dbOUT($rr['name']);?>
				</option>
				<?php 
			}
			?>
		</select>
	</p>
	<p>
		<label for="total_price">Total Price:</label>
		<input type="text"   class="tPrice form-control" value="0" name="tprice" />
		<label for="total_price">paid Price:</label>
		<input type="number" name="paid" class="form-control tpaid" required="required"  value="0" />
	</p>
	<div class="decideAble">
	<hr>
	<p>
		<center>
		<input type="button" role="button" class="btn btn-success extproduct hidden" value="SELECT PRODUCT">
		<!-- &nbsp;--OR-- &nbsp; !-->
		<input type="button" role="button" class="btn btn-danger newproduct hidden" value="ADD NEW PRODUCT">
		<input type="button" role="button" class="btn btn-info productAttribute" value="PRODUCT ATTRIBUTE">
		</center>
	</p>
	<hr>
	</div>


	<p>
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
		<input type="submit" class="btn btn-primary" value="Save" name="bsave" />
	</p>
</form>
<div class="extproattAD hidden">
	<p>
		<a href="javascript:void(0);" class="removeExtField pull-right">Remove</a>
		<br>
		<div class="input-group">
	  		<span class="input-group-addon" id="basic-addon1">
	  			<input type="text" placeholder="Field name" class="form-control fcol priceCon" size="4" name="fieldname[]" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="text" class="fcol valueCon form-control" name="fieldval[]" placeholder="Field Value" aria-label="Username" aria-describedby="basic-addon1">
		</div>
	</p>
</div>
<?php
	$productQ = mysqli_query($con, "SELECT id,unit_price FROM products where status='1'");
	while($productE = mysqli_fetch_array($productQ)){
?>
<div class="thePriceContainer hidden" data-for="<?=$productE['id']?>">
		<div class="input-group priceCon" data-for="<?=$productE['id']?>">
	  		<span class="input-group-addon" id="basic-addon1">
	  			<input type="text" placeholder="Price" class="form-control priceConInput" size="4" name="fieldname[]" disabled="disabled" value="Price" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="number" required="required" class="valueConInput form-control" name="fieldval[]" placeholder="Field Value"  value="<?=$productE['unit_price']?>" aria-label="Username" aria-describedby="basic-addon1">
		</div>
		<div class="input-group priceCons1" data-for="<?=$productE['id']?>">
	  		<span class="input-group-addon" id="basic-addon2">
	  			<input type="text" placeholder="Serial Number" class="form-control priceConInputs" size="4" name="fieldname[chasing][]" disabled="disabled" value="Serial #" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="text" required="required" class="valueConInputs form-control" name="fieldval[chasing]" placeholder="Serial Number"   aria-label="Username" aria-describedby="basic-addon1">
		</div>
	
</div>
<?php
	}
?>
<div class="extproductAD hidden">
<p>
	<label>Select Existing Product</label>
	<a href="javascript:void(0);" class="removeExtProduct pull-right">Remove</a>
	<select class="form-control mainProductSelector" data-count="1" name="product[]">
		<option value="">---SELECT---</option>
		<?php 
		$productQ = mysqli_query($con, "SELECT id,alias FROM products where status='1'");
		
		while($products = mysqli_fetch_array($productQ)){
		?>
		<option value="<?=$products['id']?>">
			<?=dbOUT($products['alias'])?>
		</option>
		<?php } ?>
	</select>
</p>
</div>
<div class="newproductAD hidden">
	<div class="newform" data-count="0">
		<hr>
			<b data-count="0">New Product:</b>
			<a href="javascript:void(0);" class="removeExtProduct pull-right">Remove</a>
		<hr>

		<br>
		<p>
		<label data-class="company">Company:</label>
		<select class="form-control ncolChanger" name="ncompany[]" >
			<option value="">--SELECT--</option>
			<?php 
			 $q = mysqli_query($con,  "select id,name from companies where status='1'");
			 while($r = mysqli_fetch_array($q)){
			 	?>
			 	<option value="<?=$r['id']?>"><?=dbOUT($r['name']);?></option>
			 	<?php 
			 }
			?>
		</select>
			<center>OR</center>
			<input type="text" placeholder="Company" class="ncolChanger form-control" name="newcompany[]" />
		</p>
		<p>
			<label data-class="type">Type:</label>
		<select class="form-control ncolChanger" name="ntype[]">
			<option value="">--SELECT--</option>
			<?php 
			 $q = mysqli_query($con,  "SELECT pt.id, pt.name FROM `product_types` pt where pt.status='1' group by pt.name");
			 while($r = mysqli_fetch_array($q)){
			 	?>
			 	<option value="<?=$r['id']?>"><?=dbOUT($r['name']);?></option>
			 	<?php 
			 }
			?>
		</select>
		<center>OR</center>
			<input type="text" placeholder="Type" class="ncolChanger form-control" name="newtype[]" />
		</p>
		<p>
			<label data-class="model">Model:</label>
			<select class="form-control ncolChanger" name="nmodel[]">
			<option value="">--SELECT--</option>
			<?php 
			 $q = mysqli_query($con,  "select id,name from product_models where status='1' group by name");
			 while($r = mysqli_fetch_array($q)){
			 	?>
			 	<option value="<?=$r['id']?>"><?=dbOUT($r['name']);?></option>
			 	<?php 
			 }
			?>
		</select>
		<center>OR</center>
			<input type="text" placeholder="Model" class="ncolChanger form-control" name="newmodel[]" />
		</p>
		<div class="input-group">
	  		<span class="input-group-addon" id="basic-addon1">
	  			<input type="text" placeholder="Field name" class="form-control fcol ncolChanger" size="4" name="fieldname[]" disabled="disabled" value="Price" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="text" class=" form-control fcol ncolChanger" name="fieldval[]" placeholder="Field Value"  value="<?=$productE['unit_price']?>" aria-label="Username" aria-describedby="basic-addon1">
		</div>
	</div>
</div>
</div>
<?php }else{ 
$id = $_GET['UID'];
$query = mysqli_query($con,"SELECT t.invoice, t.id,t.party_id, t.total_amount, t.paid_amount,t.status, t.product_id, t.custom_attribute, t.chasing FROM transactions t where id='".$id."'");
$result = mysqli_fetch_array($query);
	?>
<div class="Client">
		<?=log_message();?>
	<!--   -->
	<form action="Save.php?function=updatePurchase&UID=<?=$result['id']?>" method="POST" />
	<p>
		<div class="form-group">
		<label class="sr-only" for="invoice">INVOICE #:</label>
		    <div class="input-group">
		      <div class="input-group-addon">P</div>
		      <input type="number" pattern="[0-9]" id="invoice" placeholder="INVOICE #" required="required" readonly="readonly" name="invoice" class="form-control" aria-label="Invoice" value="<?=str_replace("P","",str_replace("S","",$result['invoice']))?>" aria-describedby="basic-addon1" />
		    </div>
  		</div>
	</p>
	<p>
		<label for="purchaset">Purchase Type #:</label>
		<select name="purchaset" id="purchaset" class="form-control" required="required" readonly="readonly">
			<option value="1" >Cash</option>
		</select>
	</p>
	<div class="addAble">
	<p>
	
		<label for="sExtpro">Select Existing Product</label>
		<a href="javascript:void(0);" class="removeExtProduct pull-right">Remove</a>
		<select class="form-control mainProductSelector" required="required" id="sExtpro" name="product[0][id]" data-count="0" for="sExtpro">
			<option value="">---SELECT---</option>
			<?php
				while($products = mysqli_fetch_array($productQ)){
					$selected = "";
				if($products['id']==$result['product_id']){
					$selected = "style='color:white;background:grey;' selected='selected'";
				}
			?>
			<option value="<?=$products['id']?>" <?=$selected?>>
				<?=dbOUT($products['alias'])?>
			</option>
			<?php } ?>
		</select>
		<div class="thePrice">
		<div class="input-group priceCon" data-for="1">
	  		<span class="input-group-addon" id="basic-addon1">
	  			<input type="text" placeholder="Price" class="form-control priceConInput" size="4" name="fieldname[0][price]" disabled="disabled" value="Price" style="height:30px;width:200px">
	  		</span>
	  		<input style="height:50px;" type="number" value="<?=$result['total_amount'];?>" required="required" class="valueConInput form-control" name="fieldval[0][price]" placeholder="Field Value" value="54991" aria-label="Username" aria-describedby="basic-addon1">
		</div>
		<div class="input-group priceCons1" data-for="1">
	  		<span class="input-group-addon" id="basic-addon2">
	  			<input type="text" placeholder="Serial Number" class="form-control priceConInputs" size="4" name="fieldname[chasing][]" disabled="disabled" value="Serial #" style="height:30px;width:200px">
	  		</span>
	  		<input style="height:50px;" type="text" required="required" value="<?=$result['chasing'];?>"  class="valueConInputs form-control" name="fieldval[chasing]" placeholder="Serial Number" aria-label="Username" aria-describedby="basic-addon1">
		</div>
	
</div>
	</p>
	  <?php 
	  if($result['custom_attribute']!="[]"){
		  $attr = json_decode($result['custom_attribute']);
		  foreach($attr as $name => $value){
			  ?>
			  	<p>
					<a href="javascript:void(0);" class="removeExtField pull-right">Remove</a>
					<br>
					<div class="input-group">
						<span class="input-group-addon" id="basic-addon1">
							<input type="text" placeholder="Field name" value="<?=$name?>" class="form-control fcol priceCon" size="4" name="fieldname[0][custom_name][]" style="height:30px;width:200px" />
						</span>
						<input style="height:50px;" type="text" value="<?=$value?>" class="fcol valueCon form-control" name="fieldval[0][custom_value][]" placeholder="Field Value" aria-label="Username" aria-describedby="basic-addon1">
					</div>
				</p>
			  <?php 
		  }
	  }
	  ?>
	</div>
	<p>
		<label for="part">party</label>
		<select name="part" class="form-control" required="required">
			<option value="">--SELECT--</option>
			<?php 
			$qq = mysqli_query($con,"SELECT id,name from parties where status='1'");
			
			while ($rr = mysqli_fetch_array($qq)) {
				$selected = "";
				if($rr['id']==$result['party_id']){
					$selected = "style='color:white;background:grey;' selected='selected'";
				}
				?>
				<option value="<?=$rr['id']?>" <?=$selected?>>
					<?=dbOUT($rr['name']);?>
				</option>
				<?php 
			}
			?>
		</select>
	</p>
	<p>
		<label for="total_price">Total Price:</label>
		<input type="text"   class="tPrice form-control" value="<?=$result['total_amount']?>" name="tprice" />
		<label for="total_price">paid Price:</label>
		<input type="number" name="paid" class="form-control tpaid" required="required" max="<?=$result['total_amount']?>" value="<?=$result['paid_amount']?>" />
	</p>
	<div class="decideAble">
	<hr>
	<p>
		<center>
		<input type="button" role="button" class="btn btn-success extproduct hidden" value="SELECT PRODUCT">
		<!-- &nbsp;--OR-- &nbsp; !-->
		<input type="button" role="button" class="btn btn-danger newproduct hidden" value="ADD NEW PRODUCT">
		<input type="button" role="button" class="btn btn-info productAttribute" value="PRODUCT ATTRIBUTE">
		</center>
	</p>
	<hr>
	</div>


	<p>
		<input type="hidden" name="csrf_token" value="<?=$csrf_token?>" />
		<input type="submit" class="btn btn-primary" value="Update" name="bsave" />
		<a href="Purchase.php">
			<input type="button" role="button" class="btn btn-info" value="Add New" />
		</a>
	</p>
</form>
<div class="extproattAD hidden">
	<p>
		<a href="javascript:void(0);" class="removeExtField pull-right">Remove</a>
		<br>
		<div class="input-group">
	  		<span class="input-group-addon" id="basic-addon1">
	  			<input type="text" placeholder="Field name" class="form-control fcol priceCon" size="4" name="fieldname[]" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="text" class="fcol valueCon form-control" name="fieldval[]" placeholder="Field Value" aria-label="Username" aria-describedby="basic-addon1">
		</div>
	</p>
</div>
<?php
	$productQ = mysqli_query($con, "SELECT id,unit_price FROM products where status='1'");
	while($productE = mysqli_fetch_array($productQ)){
?>
<div class="thePriceContainer hidden" data-for="<?=$productE['id']?>">
		<div class="input-group priceCon" data-for="<?=$productE['id']?>">
	  		<span class="input-group-addon" id="basic-addon1">
	  			<input type="text" placeholder="Price" class="form-control priceConInput" size="4" name="fieldname[]" disabled="disabled" value="Price" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="number" required="required" class="valueConInput form-control" name="fieldval[]" placeholder="Field Value"  value="<?=$productE['unit_price']?>" aria-label="Username" aria-describedby="basic-addon1">
		</div>
		<div class="input-group priceCons1" data-for="<?=$productE['id']?>">
	  		<span class="input-group-addon" id="basic-addon2">
	  			<input type="text" placeholder="Serial Number" class="form-control priceConInputs" size="4" name="fieldname[chasing][]" disabled="disabled" value="Serial #" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="text" required="required" class="valueConInputs form-control" name="fieldval[chasing]" placeholder="Serial Number"   aria-label="Username" aria-describedby="basic-addon1">
		</div>
	
</div>
<?php
	}
?>
<div class="extproductAD hidden">
<p>
	<label>Select Existing Product</label>
	<a href="javascript:void(0);" class="removeExtProduct pull-right">Remove</a>
	<select class="form-control mainProductSelector" data-count="1" name="product[]">
		<option value="">---SELECT---</option>
		<?php 
		$productQ = mysqli_query($con, "SELECT id,alias FROM products where status='1'");
		while($products = mysqli_fetch_array($productQ)){
		?>
		<option value="<?=$products['id']?>">
			<?=dbOUT($products['alias'])?>
		</option>
		<?php } ?>
	</select>
</p>
</div>
<div class="newproductAD hidden">
	<div class="newform" data-count="0">
		<hr>
			<b data-count="0">New Product:</b>
			<a href="javascript:void(0);" class="removeExtProduct pull-right">Remove</a>
		<hr>

		<br>
		<p>
		<label data-class="company">Company:</label>
		<select class="form-control ncolChanger" name="ncompany[]" >
			<option value="">--SELECT--</option>
			<?php 
			 $q = mysqli_query($con,  "select id,name from companies where status='1'");
			 while($r = mysqli_fetch_array($q)){
			 	?>
			 	<option value="<?=$r['id']?>"><?=dbOUT($r['name']);?></option>
			 	<?php 
			 }
			?>
		</select>
			<center>OR</center>
			<input type="text" placeholder="Company" class="ncolChanger form-control" name="newcompany[]" />
		</p>
		<p>
			<label data-class="type">Type:</label>
		<select class="form-control ncolChanger" name="ntype[]">
			<option value="">--SELECT--</option>
			<?php 
			 $q = mysqli_query($con,  "SELECT pt.id, pt.name FROM `product_types` pt where pt.status='1' group by pt.name");
			 while($r = mysqli_fetch_array($q)){
			 	?>
			 	<option value="<?=$r['id']?>"><?=dbOUT($r['name']);?></option>
			 	<?php 
			 }
			?>
		</select>
		<center>OR</center>
			<input type="text" placeholder="Type" class="ncolChanger form-control" name="newtype[]" />
		</p>
		<p>
			<label data-class="model">Model:</label>
			<select class="form-control ncolChanger" name="nmodel[]">
			<option value="">--SELECT--</option>
			<?php 
			 $q = mysqli_query($con,  "select id,name from product_models where status='1' group by name");
			 while($r = mysqli_fetch_array($q)){
			 	?>
			 	<option value="<?=$r['id']?>"><?=dbOUT($r['name']);?></option>
			 	<?php 
			 }
			?>
		</select>
		<center>OR</center>
			<input type="text" placeholder="Model" class="ncolChanger form-control" name="newmodel[]" />
		</p>
		<div class="input-group">
	  		<span class="input-group-addon" id="basic-addon1">
	  			<input type="text" placeholder="Field name" class="form-control fcol ncolChanger" size="4" name="fieldname[]" disabled="disabled" value="Price" style="height:30px;width:200px" />
	  		</span>
	  		<input style="height:50px;" type="text" class=" form-control fcol ncolChanger"  name="fieldval[]" placeholder="Field Value"  value="<?=$productE['unit_price']?>" aria-label="Username" aria-describedby="basic-addon1">
		</div>
	</div>
</div>
</div>
<?php }

 ?>

<br>
<h1 class="page-header">Records Of Purchases</h1> 
<div class="Server ">
	<?php 
		$query = mysqli_query($con,"SELECT t.type,pat.name as party,t.created_at,t.status,t.dispute,t.id,t.invoice,t.party_id,t.total_amount,t.paid_amount,
		p.alias,(select sum(th.amount) from transaction_history th where th.tid=t.id) as other_paid FROM `transactions` t, products p, parties pat where t.product_id=p.id AND pat.id=t.party_id and t.trans_type='0'");

	?>
	<table class="sort_table table table-hover table-responsive">
		<thead>
			<tr>
				<th>ID</th>
				<th>Invoice</th>
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
				<td><?=dbOUT($result['invoice'])?></td>
				<td><?=dbOUT($result['alias'])?></td>
				<td><?=dbOUT($result['party'])?></td>
				<td><?=dbOUT($result['total_amount'])?></td>
				<td><?=dbOUT($result['other_paid'])?></td>
				<td><?php 
				if($result['status']!="3"){
					if($result['status']=="0"){
						if($result['dispute']=="0"){
							echo "<span style='color:blue'>PENDING</span>";
						}else{
							echo "<b><span style='color:red'>DISPUTED</span></b>";
						}
					}else{
						if($result['dispute']=="0"){
							echo "<span style='color:green'>FULFILLED</span>";
						}else{
							echo "<b><span style='color:red'>DISPUTED</span></b>";
						}	
					}
				}else{
					echo "<span style='color:green'>SOLD</span>";
				}
				?></td>
				<td><?=dbOUT($result['created_at'])?></td>

			 	<th>
			 		<?php 
			 		if($result['dispute']=="0" && $result['status']!='3'){
			 		?>
					<p>
						<a href="?action=edit&UID=<?=$result['id'];?>">EDIT</a>
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
</div>


<?php include "tFooter.php"; ?>

<script>
$(function(){
	
	$added = 0;
	$nadded = 0;
	$fadded = 0;

	$(document).delegate(".extproduct","click",function(){
		console.log("working");
		$(".extproductAD").children("p").find("label").attr("for","extpro"+$added);
		$(".extproductAD").children("p").find("select").attr("id","extpro"+$added);
		$(".extproductAD").children("p").attr("data-count",$added);
		$(".extproductAD").find(".mainProductSelector").attr("data-count",$added);
		$nameArr = $(".extproductAD").find(".mainProductSelector").attr("name");
		console.log("got name "+$nameArr+" and index "+$added);
		$nameArr = $nameArr.split("[");
		$nameArr = $nameArr[0];
		$nameArr = $nameArr + "["+$added+"][id]";
		console.log("Change name "+$nameArr);
		$(".extproductAD").find(".mainProductSelector").attr("name",$nameArr);

		$elem = $(".extproductAD")[0].innerHTML;
		$(".addAble").append($elem);
		$added++;
	});
	$(document).delegate(".removeExtProduct","click",function(){
		$(this).parent().remove();
	});
	$(document).delegate(".productAttribute","click",function(){
		$lastOne = $added;
		console.log("the last adder is "+$lastOne);
		$oldName = $(".extproattAD").find(".fcol.priceCon").attr("name");
		$oldNameArr = $oldName.split("[")[0]+"["+$lastOne+"][custom_name][]";
		$(".extproattAD").find(".fcol.priceCon").attr("name",$oldNameArr);
		$oldVal = $(".extproattAD").find(".fcol.valueCon").attr("name");
		$oldValArr = $oldVal.split("[")[0]+"["+$lastOne+"][custom_value][]";
		$(".extproattAD").find(".fcol.valueCon").attr("name",$oldValArr);
		$elem = $(".extproattAD")[0].innerHTML;
		$(".addAble").append($elem);
	});
	$(document).delegate(".newproduct","click",function(){
		$(".newproductAD").find("label").each(function(){
			$tempLabel = $(this).attr("data-class");
			$(this).attr("for",$tempLabel+$added);
			$(this).next("input").attr("id",$tempLabel+$added);
		});
		$(".newproductAD").find(".ncolChanger").each(function(){
			$tempName = $(this).attr("name");
			$tempName = $tempName.split("[")[0]+"["+$added+"][]";
			if($(this).hasClass("fcol")){
				$tempName = $tempName + "[price]";
			}
			$(this).attr("name",$tempName);
			$(this).attr("data-for",$added);
		});
		$elem = $(".newproductAD")[0].innerHTML;
		$(".addAble").append($elem);
		$added++;
	});
	$(document).delegate(".removeExtProduct","click",function(){
		$(this).parent().remove();
	});
	$(document).delegate(".removeExtField","click",function(){
		$(this).parent().next("div").remove();
		$(this).parent().remove();
	});
	$(document).delegate(".valueConInput","keypress",function(){
		$(".tPrice").val($(this).val());
		$(".tpaid").attr("max",$(this).val());
	});
	$(document).delegate(".tPrice","keypress",function(){
		$(".tpaid").attr("max",$(this).val());
	});
	$(document).delegate(".tPrice","keyup",function(){
		$(this).keypress();
	});
	$(document).delegate(".valueConInput","keyup",function(){
		$(this).keypress();
	});
	$(document).delegate(".mainProductSelector","change",function(){
		$count = $(this).attr("data-count");
		$pelem = $(this).parent();
		$id = $(this).val();
		if($id==""){
			$pelem.find(".thePrice").html('');
		}
		console.log("fetching price for id "+$id);
		$elem = $(".thePriceContainer[data-for='"+$id+"']")[0].innerHTML;
		if($pelem.find(".thePrice").length==0){
			$pelem.append("<div class='thePrice'></div>");
		}
		$pelem.find(".thePrice").html('');
		$priceElem = $pelem.find(".thePrice");
		$priceElem.html($elem);
		console.log("Changing Index to "+$count);
		$newName = $priceElem.find(".priceConInput").attr("name");
		$nameArr = $newName.split("[")[0]+"["+$count+"][price]";
		$priceElem.find(".priceConInput").attr("name",$nameArr);
		$newName = $priceElem.find(".valueConInput").attr("name");
		$nameArr = $newName.split("[")[0]+"["+$count+"][price]";
		$priceElem.find(".valueConInput").attr("name",$nameArr);
		$totalPrice = $priceElem.find(".valueConInput").attr("value");
		console.log("found price "+$totalPrice);
		$(".tPrice").val($totalPrice);
		$(".tpaid").attr("max",$totalPrice);
		console.log("New Name for pricecontainer is "+$nameArr);
	});
	
	//$(".mainProductSelector").change();
});

</script>