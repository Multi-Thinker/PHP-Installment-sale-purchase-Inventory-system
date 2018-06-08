<?php
//DONT EDIT
//SAVE.PHP
$inner = TRUE;
$functionsINNER = TRUE;
$functions['GLOBAL_ONLY'] = 1;
$functions['USER_LEVEL_ONLY'] = 1;
$functions['DB_ONLY'] = 1;
$CSRFrequired = 1;
include "config.php";
$userInfo = userInfo();
$pageFunction = checkRequest('function');

function toZero($val){
	if($val==''){
		return "0";
	}else{
		return $val;
	}
}
function isval($val){

	if($val==''){
		set_message("Fields are required");
		die("Fields are required");
	}else{
		return $val;
	}
}

function uploadFile($input_of_file_name,$required=false,$newname=""){

		$error    = "";
		if(isset($_FILES[$input_of_file_name]['name']) && empty($_FILES[$input_of_file_name]['name']) && $required==true){
			set_message("Image required");
			$error = 1;
			goBack();
		}

		if(isset($_FILES[$input_of_file_name]['name']) && !empty($_FILES[$input_of_file_name]['name'])){

		// echo "GOT FILE";

		$filename = $_FILES[$input_of_file_name]['name'];
		$tempname = $_FILES[$input_of_file_name]['tmp_name'];
		$rename   = explode(".",$filename);
		//if($newname==""){
		$rename[0]= md5($rename[0].microtime());
		//}else{
		//	$rename[0]= $newname;
		//}

		$rename   = implode(".",$rename);
		$fileID   = $rename;
		$filepath = "";
		$parentDir= "uploads/image/";
		// echo "[+] Uploading in directory -> <b style='color:blue'>".$parentDir."</b><br>";
		$info = getimagesize($_FILES[$input_of_file_name]['tmp_name']);
		if (($info[2] !== IMAGETYPE_GIF) && ($info[2] !== IMAGETYPE_JPEG) && ($info[2] !== IMAGETYPE_PNG)) {
			set_message("IMAGE Format not allowed");
		   	$error = "1";
		   	goBack();
		}else{
			// echo "FILE <b style='color:orange'>".$rename."</b> ON <b style='color:red'>".$tempname."</b><b style='color:green'> IS ACCEPTED STATE </b><br>";
			if(!is_dir($parentDir)){
				mkdir($parentDir);
			}
			if(is_dir($parentDir)){
				$filepath = $parentDir.$rename;
				if(move_uploaded_file($tempname, $filepath)){
					// echo "<b style='color:green'>FILE UPLOADED</b><br>";
				}else{
					set_message("There was problem uploading the file");
					$error = "1";
					goBack();
				}
			}else{
				set_message("Directory ".$parentDir." Does not exist");
				$error = "1";
				goBack();
			}
		}
			$data = array("link"=>$filepath,"ID"=>$fileID,"error"=>$error);
			return $data;
		}
		else{
			return array("ID"=>"","error"=>"-1");
		}

}

/************ FUNCTION BODY ********************/
switch ($pageFunction) {

	/***** PARTY PAGE *********/
	case 'addParty':
			$name  = dbIN(checkRequest('bname'));
			$phone = dbIN(checkRequest('bphone'));
			$email = dbIN(checkRequest('bemail'));
			$fax   = dbIN(checkRequest('bfax'));
			$addr  = dbIN(checkRequest('baddr'));
			mysqli_query($con,"INSERT INTO parties (name,phone,email,fax,address) VALUES ('".$name."','".$phone."','".$email."','{$fax}','{$addr}')") or die(mysqli_error($con));
		  set_message("Party ADDED");

		  goBack();
	break;
	case 'updateParty':
		  $name  = dbIN(checkRequest('bname'));
		  $phone = dbIN(checkRequest('bphone'));
		  $email = dbIN(checkRequest('bemail'));
		  $fax   = dbIN(checkRequest('bfax'));
		  $addr  = dbIN(checkRequest('baddr'));
		  $id    = dbIN(checkRequest('UID'));
		  mysqli_query($con,"UPDATE parties SET name='{$name}', phone='{$phone}', email='{$email}', fax='{$fax}', address='{$addr}' WHERE id='{$id}' AND status='1'");
		  set_message("Party UPDATED");
		  goBack();
	break;

	/*********** COMPANY PAGE ******************/
	case 'addCompany':
		$name  = dbIN(checkRequest('bname'));
		$phone = dbIN(checkRequest('bphone'));
		$addr  = dbIN(checkRequest('baddr'));
		$fax   = dbIN(checkRequest('bfax'));
		$owner = dbIN(checkRequest('bowner'));
		mysqli_query($con,"INSERT INTO companies (name,phone,address,fax,owner) VALUES ('{$name}','{$phone}','{$addr}','{$fax}','{$owner}')");
		if(mysqli_error($con)){
			set_message(mysqli_error($con));	
		}else{
			set_message("Company ADDED");
		}

		goBack();
	break;
	case 'updateCompany':
		$name  = dbIN(checkRequest('bname'));
		$phone = dbIN(checkRequest('bphone'));
		$addr  = dbIN(checkRequest('baddr'));
		$fax   = dbIN(checkRequest('bfax'));
		$owner = dbIN(checkRequest('bowner'));
		$id    = dbIN(checkRequest('UID'));
		mysqli_query($con,"UPDATE companies SET name = '{$name}',phone='{$phone}',address='{$addr}',fax='{$fax}',owner='{$owner}' WHERE id='{$id}'");
		set_message("Company UPDATED");
		goBack();
	break;
	/****************** PRODUCT TYPE **********************/
	case 'addProductType':
		$name  = dbIN(checkRequest('bname'));
		$cid   = dbIN(checkRequest('company'));
		mysqli_query($con,"INSERT INTO product_types (name,company_id) VALUES ('{$name}','{$cid}')");
		set_message("Product Type ADDED");
		goBack();
	break;
	case 'updateProductType':
		$name  = dbIN(checkRequest('bname'));
		$cid   = dbIN(checkRequest('company'));
		$id    = dbIN(checkRequest('UID'));
		mysqli_query($con,"UPDATE product_types SET name = '{$name}', company_id='{$cid}' WHERE id='{$id}'");
		set_message("Product Type UPDATED");
		goBack();
	break;
	/***************** PRODUCT MODEL ***************************/
	case 'addProductModel':
		$name  = dbIN(checkRequest('bname'));
		$pid   = dbIN(checkRequest('btype'));

		mysqli_query($con,"INSERT INTO product_models (name,product_type_id) VALUES ('{$name}','{$pid}')");
		set_message("Product Model ADDED");
		goBack();
	break;
	case 'updateProductModel':
		$name  = dbIN(checkRequest('bname'));
		$pid   = dbIN(checkRequest('btype'));
		$id    = dbIN(checkRequest('UID'));
		mysqli_query($con,"UPDATE product_models set name='{$name}', product_type_id='{$pid}' WHERE id='{$id}'");
		set_message("Product Model UPDATED");
		goBack();
	break;
	/**************** Main Product ***********************/
	case 'addMainProduct':
		$company = dbIN(checkRequest('company'));
		$type    = dbIN(checkRequest('Type'));
		$model   = dbIN(checkRequest('Model'));
		$price   = dbIN(checkRequest('price'));
		$typeName = dbIN(checkRequest('tname'));
		$modelName = dbIN(checkRequest('mname'));
		$companyName = dbIN(checkRequest('cname'));
		$alias   = $companyName." ".$typeName." ".$modelName;

		mysqli_query($con,"INSERT INTO products (alias,company_id,product_type_id,product_model_id,unit_price) VALUES
			('{$alias}','{$company}','{$type}','{$model}','{$price}')");
		set_message("Product added");
		goBack();
	break;
	case 'updateMainProduct':
		$company = dbIN(checkRequest('company'));
		$type    = dbIN(checkRequest('Type'));
		$model   = dbIN(checkRequest('Model'));
		$price   = dbIN(checkRequest('price'));
		$typeName = dbIN(checkRequest('tname'));
		$modelName = dbIN(checkRequest('mname'));
		$companyName = dbIN(checkRequest('cname'));
		$alias   = $companyName." ".$typeName." ".$modelName;
		$id     = dbIN(checkRequest('UID'));

		mysqli_query($con, "UPDATE products set alias='{$alias}',
			company_id='{$company}', product_type_id='{$type}',
			product_model_id='{$model}', unit_price='{$price}' WHERE id='{$id}'");
		set_message("Product Updated");
		goBack();
	break;
	/************************ PURCHASE ***************************************/
	case 'addPurchase':
				$invoice = "P".checkRequest('invoice');
				$type    = 1;
				$product_id = $_REQUEST['product'][0]['id'];
				$total   = $_REQUEST['tprice'];
				$paid    = $_REQUEST['paid'];
				$party   = $_REQUEST['part'];
				$chasing = $_REQUEST['fieldval']['chasing'];
				$status  = 0;
				if(($total-$paid)==0){
					$status = 1;
				}
				if(isset($_REQUEST['fieldname'][0]['custom_name'])){
				 $customNames = $_REQUEST['fieldname'][0]['custom_name'];
				}else{
					$customNames[0] = '';
				}
				if(isset($_REQUEST['fieldval'][0]['custom_value'])){
					$customValues = $_REQUEST['fieldval'][0]['custom_value'];
				}else{
				   $customValues[0] = '';
				}
				$custom_value = array();
				foreach($customNames as $key => $value){
					if($value!=''){
						$custom_value[$value] = $customValues[$key];
					}
				}
				$custom_value = json_encode($custom_value);
				mysqli_query($con,"INSERT INTO transactions
					(invoice,type,party_id,total_amount,paid_amount,
					product_id,custom_attribute,chasing,status)
					VALUES ('{$invoice}','1',
					'{$party}','{$total}','{$paid}','{$product_id}','{$custom_value}','{$chasing}',{$status})");
				$lid = mysqli_insert_id($con);
				mysqli_query($con,"INSERT INTO transaction_history (tid,pid,amount,type) VALUES ('{$lid}','{$party}','{$paid}','0')");
				set_message("added");
				goBack();
	break;
	case 'updatePurchase':
				$invoice = "P".$_REQUEST['invoice'];
				$type    	= 	1;
				$product_id = 	$_REQUEST['product'][0]['id'];
				$total   = 		$_REQUEST['tprice'];
				$paid    = 		$_REQUEST['paid'];
				$party   = 		$_REQUEST['part'];
				$chasing = 		$_REQUEST['fieldval']['chasing'];
				$id      = 		$_REQUEST['UID'];
				$status  = 		0;
				if(($total-$paid)==0){
					$status = 1;
				}
				if(isset($_REQUEST['fieldname'][0]['custom_name'])){
				 $customNames = $_REQUEST['fieldname'][0]['custom_name'];
				}else{
					$customNames[0] = '';
				}
				if(isset($_REQUEST['fieldval'][0]['custom_value'])){
					$customValues = $_REQUEST['fieldval'][0]['custom_value'];
				}else{
				   $customValues[0] = '';
				}
				$custom_value = array();
				foreach($customNames as $key => $value){
					if($value!=''){
						$custom_value[$value] = $customValues[$key];
					}
				}
				$custom_value = json_encode($custom_value);
				// mysqli_query($con,"INSERT INTO transactions
				// 	(invoice,type,party_id,total_amount,paid_amount,
				// 	product_id,custom_attribute,chasing)
				// 	VALUES ('{$invoice}','1',
				// 	'{$party}','{$total}','{$paid}','{$product_id}','{$custom_value}','{$chasing}')");
				mysqli_query($con,"UPDATE transactions SET invoice='{$invoice}', party_id='{$party}',total_amount='{$total}',
					paid_amount='{$paid}',product_id='{$product_id}',custom_attribute='{$custom_value}',chasing='{$chasing}',status='{$status}' where id='{$id}'");
				mysqli_query($con,"UPDATE transaction_history SET amount='{$paid}' WHERE tid='{$id}' ORDER BY id ASC LIMIT 1 ");
				set_message("Updated");
				goBack();
	break;
	/********************** ADD FUNDS ******************/
	case 'addFunds':
		$tid = checkRequest('tid');
		$pid = checkRequest('pid');
		$remarks = checkRequest("Remarks");
		$reciept = checkRequest("reciept");
		$type = checkRequest("type")=='' ? 0 : checkRequest("type");
		$amount = $_REQUEST['amount'];
		mysqli_query($con,"INSERT INTO transaction_history (tid,pid,type,amount,Remarks,reciept) VALUES
			('{$tid}','{$pid}','{$type}','{$amount}','{$remarks}','{$reciept}')");
		$fetch = mysqli_query($con,"SELECT t.total_amount, (SELECT sum(th.amount) from transaction_history th where th.tid=t.id AND th.dispute='0') as paid FROM transactions t where t.id='{$tid}'");
		$result = mysqli_fetch_array($fetch);
		if(($result['total_amount']-$result['paid'])==0){
			mysqli_query($con,"UPDATE transactions set status='1' WHERE id='{$tid}'");
		}else{
			mysqli_query($con,"UPDATE transactions set status='0' WHERE id='{$tid}'");
		}
		set_message("Fund ADDED");
		goBack();
	break;
	/******** HANDLE DISPUTE TRANSACTION HISTORY ************/
	case 'addDispute':
		$did = $_REQUEST['did'];
		$status = $_REQUEST['status'];
		$tid  = $_REQUEST['tid'];
		$oldStatus = $status;
		if($status=="0"){
			$status = "1";
		}else{
			$status = "0";
		}
		mysqli_query($con,"UPDATE transaction_history SET dispute='{$status}' WHERE dispute='{$oldStatus}' AND id='{$did}'");

		$fetch = mysqli_query($con,"SELECT t.total_amount, (SELECT sum(th.amount) from transaction_history th where th.tid=t.id AND th.dispute='0') as paid FROM transactions t where t.id='{$tid}'");
		$result = mysqli_fetch_array($fetch);
		if(($result['total_amount']-$result['paid'])===0){
			mysqli_query($con,"UPDATE transactions set status='1' WHERE id='{$tid}'");
		}else{
			mysqli_query($con,"UPDATE transactions set status='0' WHERE id='{$tid}'");
		}
		set_message("Record Updated");
		goBack();
	break;
	case 'disputeProduct':
		$status = $_REQUEST['status'];
		$tid  = $_REQUEST['tid'];
		$oldStatus = $status;
		if($status=="0"){
			$status = "1";
		mysqli_query($con,"UPDATE transaction_history SET dispute='{$status}' WHERE tid='{$tid}'");
		}else{
			$status = "0";
		}
		mysqli_query($con,"UPDATE transactions SET dispute='{$status}' WHERE id='{$tid}'");
		set_message("Record Updated");
		goBack();
	break;
	/****************** ADD CLIENT ******************/
	case 'addClient':

		/******** CLIENT ***********/
		$account_id = $_REQUEST['account_no'];
		$file       = uploadFile('picture',false,$account_id);
		$filename   = dbIN($file['ID']);
		$name       = dbIN($_REQUEST['client_name']);
		$father_name= dbIN($_REQUEST['client_fname']);
		$cnic       = dbIN($_REQUEST['client_cnin']);
		$phone      = dbIN($_REQUEST['client_phoneno']);
		$designation= dbIN($_REQUEST['client_occupation']);
		$client_addr= dbIN($_REQUEST['client_address']);
		$client_paddr=dbIN($_REQUEST['client_permanent_address']);

		mysqli_query($con,"INSERT INTO clients
			(account_number,name,father_name,
			image,occupation,address,
			permanent_address,cnic,phone)
			VALUES
			('{$account_id}','{$name}',
			'{$father_name}','{$filename}',
			'{$designation}','{$client_addr}',
			'{$client_paddr}','{$cnic}','{$phone}')");
		$cid = mysqli_insert_id($con);
		set_message("Client ADDED");
		/******** GUARANTOR ***********/

		$gname 		    = dbIN($_REQUEST['guarantor_name']);
		$f_gname   		= dbIN($_REQUEST['guarantor_fname']);
		$gdesignation	= dbIN($_REQUEST['guarantor_occupation']);
		$gcninc         = dbIN($_REQUEST['guarantor_cnin']);
		$gphone         = dbIN($_REQUEST['guarantor_phoneno']);
		$grel  			= dbIN($_REQUEST['guarantor_relation']);
		$gaddr 			= dbIN($_REQUEST['gclient_address']);
		$gpaddr 		= dbIN($_REQUEST['gclient_permanent_address']);

		mysqli_query($con,"INSERT INTO GUARANTOR (name,father_name,address,permanent_address,phone,designation,relation,client_id,cnic)
			VALUES
			('{$gname}','{$f_gname}','{$gaddr}','{$gpaddr}','{$gphone}','{$gdesignation}','{$grel}','{$cid}','{$gcninc}')");
		$gid = mysqli_insert_id($con);
		set_message("Client ADDED");
		mysqli_query($con,"UPDATE clients SET guarantor_id='{$gid}' WHERE id='{$cid}'");
		set_message("Client ADDED");
		goBack();
	break;
	/******************** UPDATE CLIENT ***************/
	case 'updateClient':
		$cid  = $_REQUEST['cid'];
		$gid  = $_REQUEST['gid'];
		$oldFile = $_REQUEST['old_image'];
		// CLIENT VALS
		// IMAGE UPDATE
		$account_id = $_REQUEST['account_no'];
		$file       = uploadFile('picture',false,$account_id);
		// echo $file['ID']."first<br>";
		if(strpos($file['ID'],".")==false){
			$file['ID'] = "";
		}
		// echo $file['ID']."first 2<br>";
		if($file['ID']!=""){
			unlink("uploads/image/".$oldFile);
			$filename   = dbIN($file['ID']);
			// echo $filename."first 3<br>";
		}else{
			$filename   = $oldFile;
		}
		// echo $filename."first 4<br>";

		$name       = dbIN($_REQUEST['client_name']);
		$father_name= dbIN($_REQUEST['client_fname']);
		$cnic       = dbIN($_REQUEST['client_cnin']);
		$phone      = dbIN($_REQUEST['client_phoneno']);
		$designation= dbIN($_REQUEST['client_occupation']);
		$client_addr= dbIN($_REQUEST['client_address']);
		$client_paddr=dbIN($_REQUEST['client_permanent_address']);

		// GAURANTOR VALS
		$gname 		    = dbIN($_REQUEST['guarantor_name']);
		$f_gname   		= dbIN($_REQUEST['guarantor_fname']);
		$gdesignation	= dbIN($_REQUEST['guarantor_occupation']);
		$gcninc         = dbIN($_REQUEST['guarantor_cnin']);
		$gphone         = dbIN($_REQUEST['guarantor_phoneno']);
		$grel  			= dbIN($_REQUEST['guarantor_relation']);
		$gaddr 			= dbIN($_REQUEST['gclient_address']);
		$gpaddr 		= dbIN($_REQUEST['gclient_permanent_address']);

		// UPDATE CLIENT
		mysqli_query($con,"UPDATE clients set account_number='{$account_id}', name='{$name}', father_name='{$father_name}',
			image='{$filename}',occupation='{$designation}',
			address='{$client_addr}',permanent_address='{$client_paddr}',
			cnic='{$cnic}',phone='{$phone}'
			WHERE id='{$cid}'");
		set_message("UPDATED");
		// UPDATE GAURANTOR
		mysqli_query($con,"UPDATE GUARANTOR set name='{$gname}',
			father_name='{$f_gname}',address='{$gaddr}',permanent_address='{$gpaddr}',phone='{$gphone}',
			designation='{$gdesignation}',relation='{$grel}',
			cnic='{$gcninc}' WHERE id='{$gid}' AND client_id='{$cid}'");
		set_message("UPDATED");
		// GO BACK
		goBack();
	break;
	/*********** DELETE CLIENT ***********************/
	case 'deleteClient':
		$uid    = '';
		$file   = "0.0png";
		$action = checkRequest('action');
		$uid    = checkRequest('UID');
		$file   = checkRequest('avatar');
		unlink("uploads/image/".$file);
		mysqli_query($con, "DELETE FROM clients where id='{$uid}'");
		mysqli_query($con, "DELETE FROM GUARANTOR where client_id='{$uid}'");
		//echo "uploads/image/".$file;
		set_message("Deleted");
		goBack();
	break;
	case 'addSale':
		$invoices    = "S".isval(checkRequest("invoice"));
		$client_id   = checkRequest("Client");
		$product_id  = checkRequest("selectProduct");
		$party_id    = checkRequest("SelectParty");
		$invoice     = checkRequest("tSelect");
		$PurchaseType= checkRequest("PurchaseType"); // 0=> Installment 1=> Cash
		$trans_type  = '1';
		$type        = '2'; // 1=> purchase, 2=> sale, 3=>sold
		// calculation
		$status      = '0'; // 0=> in progress, 2=> fulfilled
		$total       = checkRequest('TotalAmount')=='' ? '0' : checkRequest("TotalAmount") ;
		$paid        = checkRequest("PaidAmount")=='' ? '0' : checkRequest("PaidAmount") ;
		$advance     = checkRequest("AdvanceAmount")=='' ? '0' : checkRequest("AdvanceAmount") ;
		$payday      = checkRequest("InstallmentMonthDate")=='' ? '0' : checkRequest("InstallmentMonthDate");
		$installNo   = checkRequest("nOInstallement")=='' ? '1' : checkRequest("nOInstallement");

		$totalPaid   = $paid+$advance;
		$totalRemain = $total-$totalPaid;
		$instalAmm   = checkRequest("InstallmentAmount")=='' ? $totalRemain : checkRequest("InstallmentAmount");
		$tid         = checkRequest("tid");
		$isAdvance   = '0';
		if($advance!='0'){
			$isAdvance = '1';
		}
		if(($total-$totalPaid)=="0"){
			$status = '1';
		}


		mysqli_query($con,"INSERT INTO transactions
					(invoice,type,party_id,total_amount,paid_amount,
					product_id,client_id,trans_type,Advance_pay,installment_day,sale_tid,status,total_installments,installment_amount,sale_type)
					VALUES ('{$invoices}','{$type}',
					'{$party_id}','{$total}','{$totalPaid}','{$product_id}','{$client_id}','{$trans_type}','{$advance}','{$payday}','{$tid}','{$status}','{$installNo}','{$instalAmm}','{$PurchaseType}')");
		$newTID = mysqli_insert_id($con);
		mysqli_query($con,"INSERT INTO transaction_history (type,tid,linked_tid,is_advance,amount,pid) values
			('{$trans_type}','{$newTID}','{$tid}','{$isAdvance}','{$totalPaid}','{$product_id}')");

		mysqli_query($con,"UPDATE transactions SET status='3' WHERE id='{$tid}'");
		set_message("Sale added");
		goBack();
	break;

	case 'editSale':
		$UID     		= toZero(isval(checkRequest("UID")));
		$ptype   		= toZero(isval(checkRequest("PurchaseType")));
		$total   		= toZero(isval(checkRequest("TotalAmount")));
		$paid    		= toZero(checkRequest("PaidAmount"));
		$advance 		= toZero(checkRequest("AdvanceAmount"));
		$totalPaid      = $paid+$advance;
		$totalRemain    = $total-$totalPaid;
		$status         = '0';
		$is_advance     = '0';
		if(($total-$totalPaid)=="0"){
			$status = '1';
		}
		if($advance!='0'){
			$is_advance = '1';
		}
		$installmount 	= toZero(checkRequest("InstallmentMonthDate"));
		$nOInstallement = toZero(checkRequest("nOInstallement"));
		$installmentamm = toZero(checkRequest("InstallmentAmount"));

		mysqli_query($con,"UPDATE transactions set
			sale_type='{$ptype}',total_amount='{$total}',
			paid_amount='{$totalPaid}',Advance_pay='{$advance}',
			installment_day='{$installmount}',status='{$status}',
			total_installments='{$nOInstallement}',
			installment_amount='{$installmentamm}' WHERE id ='{$UID}'");

		mysqli_query($con,"UPDATE transaction_history set amount='{$totalPaid}',
		 is_advance='{$is_advance}'
		where type='1' and tid='{$UID}' order by id DESC LIMIT 1");
		set_message("UPDATED");
		goBack();

	break;
	/************ WRONG OR EMPTY REQUESTS ***********/
	default:
		set_message("You are not allowed to perform that action");
		goBack();
	break;
}

?>
