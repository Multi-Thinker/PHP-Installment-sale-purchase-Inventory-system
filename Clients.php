<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php";
$userInfo = userInfo();
$_SESSION['inner_command'] = md5(microtime()."TALHA HABIB");
$ajax_token = $_SESSION['inner_command'];
?>
<?php include "tHeader.php"; ?>
<style>
#img-upload{
	height: auto !important;
}
.drawable {
  max-width: 200px;
  border: 1px solid black;
  border-radius: 5px;
}
.server{
	position:absolute;
}
.absolute{
	float: left;
    position: absolute;
    top: 0;
    overflow: hidden;
    width: 100%;
	min-height: 100%;
    background: white;
    align-items: center;
    text-align: center;
    margin: 0 auto;
    padding: 20px;
}
@media print{
	button,a,hr,h1,h2,h3,h4,h5,nav,.dataTables_filter,.dataTables_length,.dataTables_info,.dataTables_paginate{
		display:none;
	}
	.sort_table{
		opacity: 0;
	}
}
</style>

<h1 class="page-header">Clients</h1>
<a href="Report.php?reported=true&user_type=ALL" class="pull-right">Report</a>
<center><a href="Client.php"><button class="btn btn-info btn-lg">ADD NEW</button></a></center>
<hr>

	<?=log_message();?>
<div class="server">
	<h2 class="stable">Records of Clients</h2>
	<table class="stable table sort_table table-striped">
		<thead>
			<tr>
				<th>ACC #</th>
				<th>Picture</th>
				<th>Name</th>
				<th>Designation</th>
				<th>Father</th>
				<th>Guarantor</th>
				<th>Client's Phone</th>
				<th>Guarantor's Phone</th>
				<th>Sale in progress</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>


	<?php
		$clients = mysqli_query($con,"SELECT IFNULL((select count(id) FROM transactions tt where tt.client_id=c.id and tt.type='2' and tt.dispute='0' AND status='0'),0) as selling, c.permanent_address as paddr, c.address as addr, c.cnic, c.account_number as acc, c.id as cid, c.name as client, g.name as guarantor, c.phone as client_phone, g.phone as gaurantor_phone, c.occupation as designation, c.image as av, c.father_name as father FROM clients c, GUARANTOR g where c.guarantor_id=g.id");
		while($result = mysqli_fetch_array($clients)){
			$cnic = dbOUT($result['cnic']);
			// 3310293296339
			// if()
			// $cnic[4] = $cnic[4]."-";
			// $cnic[11] = $cnic[11]."-";
			?>
			<tr>
				<td><?=dbOUT($result['acc'])?></td>
				<td>
					<?php
					$av = dbOUT($result['av']);
					$addr = dbOUT($result['addr']);
					$pddr = dbOUT($result['paddr']);
					if($av==""){
						echo "<a href='#' class='showAble' data-cnic='".$cnic."' data-addr='".$addr."' data-paddr='".$pddr."' data-src='' data-id='".$result['cid']."'>NO IMAGE AVAILABLE</a>";
					}else{
						?>
						<a href="javascript:void(0);" class="showAble" data-src="uploads/image/<?=$av?>" data-cnic="<?=$cnic?>" data-addr="<?=$addr?>" data-id="<?=$result['cid']?>" data-paddr="<?=$pddr?>">Click to view image</a>
						<?php
					}
					?>
				</td>
				<td><?=dbOUT($result['client'])?></td>
				<td><?=dbOUT($result['designation'])?></td>
				<td><?=dbOUT($result['father'])?></td>
				<td><?=dbOUT($result['guarantor'])?></td>
				<td><?=dbOUT($result['client_phone'])?></td>
				<td><?=dbOUT($result['gaurantor_phone'])?></td>
				<td><a href="Transactions.php?type=sales&party=<?=dbOUT($result['cid'])?>&client=true">
					<?=$result['selling'];?>
				</a>
				<td>
					<a href="editClient.php?UID=<?=$result['cid']?>&csrf_token=<?=$csrf_token?>">
						EDIT
					</a>
					<br>
					<a href="javascript:void(0)" onclick="getConfirmation('Are you sure you want to perform this action, data loss is unrecoverable!','<?=$result['cid']?>','<?=$av?>','Save.php?function=deleteClient&csrf_token=<?=$csrf_token?>&UID=<?=$result['cid']?>&avatar=<?=$av?>')">DELETE
					</a>
				</td>
			</tr>
			<?php
		}
	?>
	</tbody>
			<tr>
				<th>ACC #</th>
				<th>Picture</th>
				<th>Name</th>
				<th>Designation</th>
				<th>Father</th>
				<th>Guarantor</th>
				<th>Client's Phone</th>
				<th>Guarantor's Phone</th>
				<th>Action</th>
			</tr>
	</table>
	<div class="hidden absolute">
		<img class="drawable" src="data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==" />
		<table class="table table-hover table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>Client</th>
					<th>Gaurantor</th>
				</tr>
			</thead>
			<tbody>
			<tr>
				<td>
					<table class="table table-responsive">
						<tr>
							<th class="picture"></th><td ></td>
							<th></th><td ></td>
						</tr>
						<tr>
							<th>Client Account</th><td class="acc" align="left"></td>
							<th>Client CNIC</th><td class="cnic" align="left"></td>
						</tr>
						<tr>

							<th>Client Name</th><td class="client" align="left"></td>
							<th>Client Father</th><td class="cfather" align="left"></td>

						</tr>
						<tr>
							<th>Client phone</th><td class="phone" align="left"></td>
							<th>Client Designation</th><td class="cdes" align="left"></td>
						</tr>
						<tr>
							<th>Client address</th><td class="addr" align="left"></td>
							<th>Client permanent address</th><td class="paddr" align="left"></td>
						</tr>
					</table>
				</td>
				<td>
					<table class="table table-responsive">
						<tr>
							<th>Gurantor Name</th><td class="gname" align="left"></td>
							<th>Gurantor Father</th><td class="gfname" align="left"></td>

						</tr>
						<tr>
							<th>Gurantor CNIC</th><td class="gcnic" align="left"></td>
							<th>Gurantor phone</th><td class="gphone" align="left"></td>


						</tr>
						<tr>
							<th>Gaurantor Designation</th><td class="gdes" align="left"></td>
							<th>Gurantor address</th><td class="gaddr" align="left"></td>

						</tr>
						<tr>
							<th>Gurantor permanent address</th><td class="gpaddr" align="left"></td>
							<th>Relation with Gaurantor</th><td class="relation" align="left"></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<th>Member since</th><td class="cdated" align="left"></td>
			</tr>
			</tbody>

		</table>
		<center><a href="javascript:void(0)" class="Close">Close</a></center>
	</div>
</div>
<?php include "tFooter.php"; ?>
<script>
	function str(str){
		if(str==''){
			return "Not Available";
		}else{
			return str;
		}
	}
$(function(){


	$(document).delegate(".showAble","click",function(){
		$src = $(this).attr("data-src");
		$(".drawable").removeClass("hidden");
		$cnic = $(this).attr("data-cnic");
		$addr = $(this).attr("data-addr");
		$paddr = $(this).attr("data-paddr");
		// var $image = $(".drawable");
		// var $downloadingImage = $("<img>");
		// $downloadingImage.load(function(){

		// 	if($(this).attr("src")!=""){
		//   		$image.attr("src", $(this).attr("src"));
		// 	}else{
		// 		$image.attr("src","data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==");
		// 	}
		// });
		//$downloadingImage.attr("src", $(this).attr("data-src"));
		$(".absolute").removeClass("hidden");
		$id = $(this).attr("data-id");
		//$(".absolute").prepend("<h3> Permanent Address: "+$paddr+"</h2><br>");
		//$(".absolute").prepend("<h3> Address: "+$addr+"</h2><br>");
		//$(".absolute").prepend("<h2> CNIC: "+$cnic+"</h2><br>");
		$.ajax({
			method:"POST",
			url:"ajax.php",
			data:{action: "aboutClient", id: $id,csrf_token:"<?=$ajax_token?>"},
			beforeSend:function(){

				$(".absolute").append("<p>Loading...</p>");
			},
			success:function(data){
				$(".absolute").find("p").remove();
				$(".drawable").addClass("hidden");
				$t = $(".absolute").find("table");
				$(".absolute").find("table").find(".cdated").text(data.since);
				if($src!=''){
					$(".absolute").find("table").find(".picture").html("<img src='"+$src+"' width='180' />");
				}else{
					$(".absolute").find("table").find(".picture").text("Not Available");
				}
				if(data.gpdr!=''){
					$t.find(".gpaddr").text(data.gpdr);
				}else{
					$t.find(".gpaddr").text("Not Available");
				}

				$t.find(".client").text(str(data.client));
				$t.find(".cfather").text(str(data.father));
				$t.find(".cnic").text(str(data.ccnic));
				$t.find(".phone").text(str(data.client_phone));
				$t.find(".addr").text(str(data.client));
				$t.find(".paddr").text(str(data.client));
				$t.find(".gname").text(str(data.guarantor));
				$t.find(".gfname").text(str(data.fname));
				$t.find(".gcnic").text(str(data.gcnic));
				$t.find(".gphone").text(str(data.gaurantor_phone));
				$t.find(".gaddr").text(str(data.client));
				$t.find(".acc").text(str(data.acc));
				$t.find('.relation').text(str(data.relation));
				$t.find('.gdes').text(str(data.gdes));
				$t.find('.cdes').text(str(data.designation));



			},
			error:function(data){
				alert("ERROR fetching client with cnic "+$cnic);
			}
		});
	});
	$(".Close").click(function(){
		$(".absolute").addClass("hidden");
		$(".drawable").removeClass("hidden");
		$(".drawable").attr("src","data:image/gif;base64,R0lGODlhEAAQAPIAAP///wAAAMLCwkJCQgAAAGJiYoKCgpKSkiH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAADMwi63P4wyklrE2MIOggZnAdOmGYJRbExwroUmcG2LmDEwnHQLVsYOd2mBzkYDAdKa+dIAAAh+QQJCgAAACwAAAAAEAAQAAADNAi63P5OjCEgG4QMu7DmikRxQlFUYDEZIGBMRVsaqHwctXXf7WEYB4Ag1xjihkMZsiUkKhIAIfkECQoAAAAsAAAAABAAEAAAAzYIujIjK8pByJDMlFYvBoVjHA70GU7xSUJhmKtwHPAKzLO9HMaoKwJZ7Rf8AYPDDzKpZBqfvwQAIfkECQoAAAAsAAAAABAAEAAAAzMIumIlK8oyhpHsnFZfhYumCYUhDAQxRIdhHBGqRoKw0R8DYlJd8z0fMDgsGo/IpHI5TAAAIfkECQoAAAAsAAAAABAAEAAAAzIIunInK0rnZBTwGPNMgQwmdsNgXGJUlIWEuR5oWUIpz8pAEAMe6TwfwyYsGo/IpFKSAAAh+QQJCgAAACwAAAAAEAAQAAADMwi6IMKQORfjdOe82p4wGccc4CEuQradylesojEMBgsUc2G7sDX3lQGBMLAJibufbSlKAAAh+QQJCgAAACwAAAAAEAAQAAADMgi63P7wCRHZnFVdmgHu2nFwlWCI3WGc3TSWhUFGxTAUkGCbtgENBMJAEJsxgMLWzpEAACH5BAkKAAAALAAAAAAQABAAAAMyCLrc/jDKSatlQtScKdceCAjDII7HcQ4EMTCpyrCuUBjCYRgHVtqlAiB1YhiCnlsRkAAAOwAAAAAAAAAAAA==");
		$(".absolute").find("h2,h3,br").remove();
	});
});
</script>
