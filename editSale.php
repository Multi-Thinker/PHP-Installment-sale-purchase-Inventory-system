<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$real_csrf = $csrf_token;

$userInfo = userInfo();
$_SESSION['inner_command'] = md5(microtime()."TALHA HABIB");
$csrf_token = $_SESSION['inner_command'];
$id = checkRequest("UID");
$UID = $id;
if($id==''){
	set_message("Not Allowed");
	goBack();
}
$selectQuery = mysqli_query($con,"SELECT p.name as party_name, pr.alias as product_name, t.client_id,t.product_id,t.id,t.chasing,t.sale_type,t.sale_tid,t.total_amount,t.total_installments,t.installment_amount,t.paid_amount,t.Advance_pay,t.party_id from transactions t, parties p, products pr where t.id='{$id}' and p.id=t.party_id and pr.id=t.product_id");
$result     = mysqli_fetch_assoc($selectQuery);
$cid        = $result['client_id'];
$pid        = $result['party_id'];
$prod       = $result['product_id'];
$tid 		= $result['sale_tid'];
$totalpaid  = $result['paid_amount'] + $result['Advance_pay']; 
$total      = $result['total_amount'];
$party_name = dbOUT($result['party_name']);
$product_name = dbOUT($result['product_name']);

$totalremain= $total-$totalpaid;

$clientQ = mysqli_query($con,"SELECT name,father_name,cnic,occupation,phone,address,permanent_address,account_number as acc,image as av FROM clients WHERE id='{$cid}'");
$clientRS = mysqli_fetch_assoc($clientQ);

$gQ = mysqli_query($con,"SELECT name,father_name,cnic,designation,phone,address,permanent_address,relation FROM GUARANTOR where client_id='{$cid}'");
$gR = mysqli_fetch_assoc($gQ);


?>
<?php include "tHeader.php"; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
<style>
.cdetail th{
	max-width: 70px !important;
}
.select2{
	width:100% !important;
}
</style>
<h1 class="page-header">Sale</h1> 

<?php 
// echo "<pre>";
// print_r($result);
// echo "</pre>";
?>

		
	<?=log_message();?>
	
	<!--  action="Save.php?function=editSale"   -->
	<form  action="Save.php?function=editSale"  method="POST" />

		<div class="col-md-12">
			<div class="row">
					<div class="client_detail">
					<table class="cdetail" width="100%">
						<thead>
							<tr>
							</tr>
							<tr>
								<th>Client Detail</th>
								<th>Guarantor Detail</th>
							</tr>
						</thead>
						<tbody>
							<td class="cdetail">
								<table class="table table-responsive">
									<tbody>
										<tr>
											<th width="30px" align="left">Picture</th>
											<td width="150px" height="150px" align="center cav"><a href="uploads/image/<?=$clientRS['av']?>"><img src="uploads/image/<?=$clientRS['av']?>" width="150px"></a></td>
										</tr>
										<tr>
											<th width="30px" align="left">Account #</th>
											<td align="left"><?php echo $clientRS['acc']; ?></td>
										</tr>
										<tr>
											<th width="30px" align="left">Name</th>
											<td align="left"><?=dbOUT($clientRS['name'])?></td>
										</tr>
										<tr><th width="30px" align="left">Father Name</th><td align="left"><?=dbOUT($clientRS['father_name'])?></td></tr><tr><th width="30px" align="left">CNIC</th><td align="left"><?=dbOUT($clientRS['cnic'])?></td></tr><tr><th width="30px" align="left">Occupation</th><td align="left"><?=dbOUT($clientRS['occupation'])?></td></tr><tr><th width="30px" align="left">Phone</th><td align="left"><?=dbOUT($clientRS['phone'])?></td></tr><tr><th width="30px" align="left">Address</th><td align="left"><?=dbOUT($clientRS['address'])?></td></tr><tr><th width="30px" align="left">Permanent Address</th><td align="left"><?=dbOUT($clientRS['permanent_address'])?></td></tr></tbody>
								</table>
							</td>
							<td class="gdetail">
								<table class="table table-striped table-hover table-responsive">
									<tbody><tr><th>Name</th><td><?=dbOUT($gR['name'])?></td></tr><tr><th>Father Name</th><td><?=dbOUT($gR['father_name'])?></td></tr><tr><th>CNIC</th><td><?=dbOUT($gR['cnic'])?></td></tr><tr><th>Occupation</th><td><?=dbOUT($gR['designation'])?></td></tr><tr><th>relation</th><td><?=dbOUT($gR['relation'])?></td></tr><tr><th>Phone</th><td><?=dbOUT($gR['phone'])?></td></tr><tr><th>Address</th><td><?=dbOUT($gR['address'])?></td></tr><tr><th>Permanent Adress</th><td><?=dbOUT($gR['permanent_address'])?></td></tr><tr><th></th><td></td></tr></tbody>
								</table>
							</td>
						</tbody>
					</table>
					<br>
				</div>
			</div>

			<div class="row invoice_selector">
				<b>Product Information:</b>
				<table class='table table-hover' >
					<tr>
						<th>Party</th><td><?=$party_name?></td>
					</tr>
					<tr>
						<th>Product</th><td><?=$product_name?></td>
					</tr>
				<?php 
				   $selected_productQ = mysqli_query($con,"SELECT id,invoice,chasing,custom_attribute from transactions where id='{$tid}'");
				   while($selected_resultQ = mysqli_fetch_assoc($selected_productQ)){
				   	?>
				   	<tr>
				   		<th>ID</th><td><?=$selected_resultQ['id']?></td>
				   	</tr>
				   	<tr>
				   		<th>INVOICE</th><td><?=$selected_resultQ['invoice']?></td>
				   	</tr>
				   	<?php 
				   	if($selected_resultQ['custom_attribute']!='[]'){
				   	?>
				   	<tr>
				   		<th>CUSTOM ATTRIBUTES</th><td>
				   			<table class='table'>
				   			<?php 
				   			 $custom = json_decode($selected_resultQ['custom_attribute']);
				   			 foreach($custom as $key => $value){
				   			 	?>
				   			 		<tr>
				   			 			<th><?=$key?></th><td><?=$value?></td>
				   			 		</tr>
				   			 	<?php 
				   			 }
				   			?>
				   			</table>
				   		</td>
				   	</tr>
				   	<?php } ?>
				   	<tr>
				   		<th>SERIAL</th><td><?=$selected_resultQ['chasing']?></td>
				   	</tr>
				   	<?php 
				   }
				?>
				</table>
			</div>
			<div class="row addPurchaser">
				<p>
					<label for="PurchaseType">Purchase Type:</label>
					<select name="PurchaseType" id="PurchaseType" class="form-control">
						<option value="0" selected="selected">Installments</option>
						<option value="1">Cash</option>
					</select>
				</p>
				<p>
					<label for="totalAmountIs">Total Amount</label>
					<input type="text" name="TotalAmount" id="totalAmountIs" class="form-control" placeholder="Total Amount" value="<?=$result['total_amount']?>">
					<input type='hidden' name='csrf_token' value="<?=$real_csrf?>" />
					<input type='hidden' name='UID' value="<?=$UID?>" />
				</p>
				<p class="onCash hidden">
					<label for="PaidAmountIs">Paid Amount</label>
					<input type="text" name="PaidAmount" id="PaidAmountIs" class="form-control" placeholder="Paid Amount" value="<?=$result['paid_amount']?>"/>
				</p>
				<p class="onInstallment ">
					<label for="AdvanceAmountIS">Advance Amount</label>
					<input type="text" name="AdvanceAmount" id="AdvanceAmountIS" class="form-control" placeholder="Advance Amount" value="<?=$result['Advance_pay']?>"/>
				</p>
				<p>
					<label for="TotalPayable">Total Payable</label>
					<input type="text" name="TotalPayable" id="TotalPayable" class="form-control" placeholder="Total Payable" disabled="disabled" value="<?=$totalremain?>" />
				</p>
				<p class="onInstallment ">
					<label for="InstallmentMonthDate">Pay Day of month</label>
					<input type="text" name="InstallmentMonthDate" id="InstallmentMonthDate" class="form-control" placeholder="00" value="1" value="<?=$result['total_amount']?>"/>

				</p>
				<p class="onInstallment">
					<label for="nOInstallement">Number of installements</label>
					<input type="number" name="nOInstallement" id="nOInstallement" class="form-control" placeholder="00" value="1s" value="<?=$result['total_installments']?>" />
				</p>
				<p class="onInstallment">
					<label for="InstallmentAmount">Installements amount</label>
					<input type="number" name="InstallmentAmount" id="InstallmentAmount" class="form-control" placeholder="00" value="0" value="<?=$result['installment_amount']?>"/>
				</p>
				<p>
					<input type="submit" role="submit" name="submit" value="Add Sale" class="btn btn-primary">
				</p>
			</div>
			<img src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==" class="cloader hidden" />
			<p class="pSelect error hidden">
					THIS <span class="name"></span> CAN'T BE FOUND IN <span class="haystack"></span>
			</p>
		</div>


	</form>

<?php include "tFooter.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.13/jquery.mask.min.js"></script>
<script>
	$(function(){
		$total_amount = [];


		$("#nOInstallement").keypress(function(){
			if($(this).val()!=''){
				$("#InstallmentAmount").val($("#TotalPayable").val()/$(this).val());
			}else{
				$("#InstallmentAmount").val($("#TotalPayable").val());
			}
		});
		$("#nOInstallement").keyup(function(){
			$(this).keypress();
		})
		$("#totalAmountIs").keypress(function(){
			$("#TotalPayable").val($(this).val());
			$("#InstallmentAmount").val($(this).val());
			$("#nOInstallement,#AdvanceAmountIS").keypress();
		});
		
		$("#totalAmountIs,#AdvanceAmountIS,#PaidAmountIs").keyup(function(){
			$(this).keypress();
		});

		$("#PaidAmountIs").keypress(function(){
			$total = 0;
			if($("#TotalPayable").val()!=''){
				$total = parseInt($("#totalAmountIs").val());
				$("#TotalPayable").val($total-$(this).val());
			}
			
		});
		$("#AdvanceAmountIS").keypress(function(){
			$total = 0;
			if($("#TotalPayable").val()!=''){
				$total = parseInt($("#totalAmountIs").val());
				$("#TotalPayable").val($total-$(this).val());
				$("#InstallmentAmount").val($total-$(this).val());
				$("#nOInstallement").keypress();
			}
		});
		
		$(".tableSelector").change(function(){
			$(".invoice_selector").find("table").addClass("hidden");
			$id = $(".tableSelector").find("option[value='"+$(this).val()+"']").attr("data-for");
			$tid = $(".invoice_selector").find("table[data-id='"+ $id +"']").find(".theID").text();
			$(".tid").val($tid);
			$(".invoice_selector").find("table[data-id='"+ $id +"']").removeClass("hidden");

		});

		$(".FetchDetail").click(function(){
			$product_id = $("#selectProduct").val();
			$party_id   = $("#SelectParty").val();
			$newTB = 0;
			$.ajax({
				method:"POST",
				url:"ajax.php",
				data:{csrf_token:'<?=$csrf_token?>',action:"FetchDetail",pro_id:$product_id,pid:$party_id},
				beforeSend:function(){
					$(".cloader").removeClass("hidden");
					$(".invoice_selector").addClass("hidden");
					$(".table_selector").addClass("hidden");
					$(".table_selector").find("option").remove();
					$(".invoice_selector").html("");
					$newTB = 0;
				},
				success:function(data){
					$(".csrf_token").val(data.csrf_token);
					$i = 0;
					console.log(data);
					while($i<data.quantity){
						$table      = $("<table class='table table-hover hidden'></table");
						$total_amount.push(data.total_amount);
						$($table).attr("data-id",$i);
						if(typeof data.id[$i]!='undefined'){
							$($table).append("<tr><th>ID</th><td class='theID'>"+data.id[$i]+"</td></tr>");
						}
						if(typeof data.invoice[$i]!='undefined' && data.invoice[$i]!=''){
							$($table).append("<tr><th>Invoice</th><td>"+data.invoice[$i]+"</td></tr>");
						}
					
						$att_tb = $("<table class='table table-hover'></table>");

						$newTB = 0;
						if(typeof data.custom_attribute[$i]!='undefined'){
							while($newTB<data.custom_attribute[$i].key.length){
								$($att_tb).append("<tr><th>"+data.custom_attribute[$i].key[$newTB]+"</th><td>"+data.custom_attribute[$i].val[$newTB]+"</td></tr>");
								$newTB++;
							}
							$($table).append("<tr><th>Custom Attribute</th><td>"+$att_tb[0].innerHTML+"</td></tr>");
						}
						
					
						if(typeof data.chasing[$i]!='undefined'){
							$($table).append("<tr><th>Serial</th><td>"+data.chasing[$i]+"</td></tr>");
							$(".tableSelector").append("<option value='"+data.chasing[$i]+"' data-for='"+$i+"'>"+data.chasing[$i]+"</option>");
						}
						$(".invoice_selector").append($table);
						$i++;
					}
					$(".table_selector,.invoice_selector").removeClass("hidden");
					$("#tSelect").change();
				},
				complete:function(){
					$(".cloader").addClass("hidden");

					$(".invoice_selector").find("table").addClass("hidden");
					$id = $(".tableSelector").find("option[value='"+<?=$result['chasing']?>+"']").attr("data-for");

					$tid = $(".invoice_selector").find("table[data-id='"+ $id +"']").find(".theID").text();
					$(".tid").val($tid);
					$(".tableSelector").find("option[value='"+<?=$result['chasing']?>+"']").attr("selected","selected").trigger("change");
					$(".invoice_selector").find("table[data-id='"+ $id +"']").removeClass("hidden");

				
				}
			})
		})
		$("#InstallmentMonthDate,#nOInstallement").mask("00",{placeholder:"--"});
		$("#AdvanceAmountIS,#InstallmentAmount,#totalAmountIs").mask("000000000000",{placeholder:"------------"});
		$("#InstallmentMonthDate").keypress(function(){
			if($(this).val()!=''){
				$value = parseInt($(this).val());
				if($value>0 && $value<=31){
					return true;
				}else{
					if($value>31){
						$(this).val("31");
					}
					if($value<=0){
						$(this).val("1");
					}
					return false;
				}
			}
		});
		$("#SelectParty").change(function(){
			$(".FetchDetail").click();
		});
		$("#PurchaseType").change(function(){
			$type = $(this).val();
			$("#AdvanceAmountIS").val("0");
			$("#PaidAmountIs").val("0");
			$("#AdvanceAmountIS").keypress();
			$("#PaidAmountIs,#totalAmountIs").keypress();
			if($type=="0"){
				$(".onInstallment").removeClass("hidden");
				$(".onCash").addClass("hidden");
			}else{
				$(".onInstallment").addClass("hidden");
				$(".onCash").removeClass("hidden");				
			}
		});
		$("#InstallmentMonthDate").keyup(function(){
			$(this).keypress();
		})
		$("select").select2();
		$(".cToggle").click(function(){
			$(".client_detail").slideToggle();
		});
		$("#selectProduct").change(function(){
			$pid = $(this).val();
			$.ajax({
				method:"post",
				url:"ajax.php",
				data:{action:"selectProduct",pid:$pid,csrf_token:"<?=$csrf_token?>"},
				beforeSend:function(){
					$(".pSelect.error").addClass("hidden");
					$(".pSelect .name").text("PRODUCT");
					$(".pSelect .haystack").text("PARTIES");
					$(".party_selector").addClass("hidden");
					$(".cloader").removeClass("hidden");
					$("#SelectParty").find("option").remove();
					$newTB = 0;
				},
				success:function(data){
					if(data.quantity==0){
						$(".pSelect.error").removeClass("hidden");
					}else{
						$i = 0;
						while($i<data.quantity){
							$("#SelectParty").append("<option value='"+data.name.id[$i]+"'>"+data.name[$i]+"</option>");
							$i++;
						}
						$(".party_selector,.addPurchaser").removeClass("hidden");

					}
					$(".csrf_token").val(data.csrf_token);
				},
				error:function(){
					$(".pSelect.error").removeClass("hidden");
				},
				complete:function(){
					$(".cloader").addClass("hidden");
					$(".FetchDetail").click();
				}

			});
		});
		$("select").blur(function(){
			$(this).change();
		})
		$("#selectClient").change(function(){
			$cid = $(this).val();
			$.ajax({
				method:"post",
				url:"ajax.php",
				data:{csrf_token:'<?=$csrf_token?>',action:"selectClient",cid:$cid},
				beforeSend:function(){
					$(".pSelect.error").addClass("hidden");
					$("table.cdetail").addClass("hidden");
					$(".cloader").removeClass("hidden");
					$("td.cdetail,td.gdetail").find("tbody").html("");
					$(".pSelect .name").text("CLIENT");
					$(".pSelect .haystack").text("RECORDS");
					$(".party_selector").addClass("hidden");
					$("#SelectParty").find("option").remove();
					$newTB = 0;
				},
				success:function(data){
					
					if(data.client.av!=''){
						$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Picture</th><td align='center cav' width='150px' height='150px'><a href='uploads/image/"+data.client.av+"'><img src='uploads/image/"+data.client.av+"' width='150px' /></a></td></tr>");
					}else{
						$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Picture</th><td align='left'><b>NOT AVAILABLE</b></td></tr>");
					}
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Account #</th><td align='left'>"+data.client.acc+"</td></tr>");
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Name</th><td align='left'>"+data.client.name+"</td></tr>");
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Father Name</th><td align='left'>"+data.client.father_name+"</td></tr>");
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>CNIC</th><td align='left'>"+data.client.cnic+"</td></tr>");
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Occupation</th><td align='left'>"+data.client.occupation+"</td></tr>");
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Phone</th><td align='left'>"+data.client.phone+"</td></tr>");
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Address</th><td align='left'>"+data.client.address+"</td></tr>");
					$("td.cdetail").find("tbody").append("<tr><th align='left' width='30px'>Permanent Address</th><td align='left'>"+data.client.permanent_address+"</td></tr>");
					$("table.cdetail").removeClass("hidden");

					$("td.gdetail").find("tbody").append("<tr><th>Name</th><td>"+data.guarantor.name+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th>Father Name</th><td>"+data.guarantor.father_name+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th>CNIC</th><td>"+data.guarantor.cnic+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th>Occupation</th><td>"+data.guarantor.occupation+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th>relation</th><td>"+data.guarantor.relation+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th>Phone</th><td>"+data.guarantor.phone+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th>Address</th><td>"+data.guarantor.address+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th>Permanent Adress</th><td>"+data.guarantor.permanent_address+"</td></tr>");
					$("td.gdetail").find("tbody").append("<tr><th></th><td></td></tr>");
					$(".cToggle").removeClass("hidden");
					$(".csrf_token").val(data.csrf_token);
				},
				error:function(){
					$(".pSelect.error").removeClass("hidden");
				},
				complete:function(){
					$(".product_selector").removeClass("hidden");
					$(".cloader").addClass("hidden");
				}
			});
		});

		$(".FetchDetail").click();
		$("#PurchaseType option[value='"+<?=$result['sale_type']?>+"']").attr("selected","selected").trigger("change");
		$("#PaidAmountIs").val("<?=$result['paid_amount']?>");
		$("#AdvanceAmountIS").val("<?=$result['Advance_pay']?>");
		$("#PaidAmountIs,#AdvanceAmountIS").keypress();
		$("#TotalPayable").val("<?=$totalremain?>");

	});
</script>