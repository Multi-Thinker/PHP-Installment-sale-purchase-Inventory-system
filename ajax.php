<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
$CSRFrequired = 0;
include "config.php";
$userInfo = userInfo();
$action = checkRequest('action');
header("Content-Type: application/json");

if(checkRequest("csrf_token") != $_SESSION['inner_command']){
		die("YOU ARE NOT ALLOWED TO PERFORM THIS ACTION");
}

switch ($action) {
	case 'selectClient':
		$cid = checkRequest('cid');
		$clientQ = mysqli_query($con,"SELECT name,father_name,cnic,occupation,phone,address,permanent_address,account_number as acc,image as av FROM clients  where status='1' AND id='{$cid}'");
		$gQ = mysqli_query($con,"SELECT name,father_name,cnic,designation,phone,address,permanent_address,relation FROM GUARANTOR where client_id='{$cid}'");
		$result = mysqli_fetch_assoc($clientQ);
		$data['client']['acc'] = dbOUT($result['acc']);
		$data['client']['name'] = dbOUT($result['name']);
		$data['client']['father_name'] = dbOUT($result['father_name']);
		$data['client']['cnic'] = dbOUT($result['cnic']);
		$data['client']['occupation'] = dbOUT($result['occupation']);
		$data['client']['phone'] = dbOUT($result['phone']);
		$data['client']['address'] = dbOUT($result['address']);
		$data['client']['permanent_address'] = dbOUT($result['permanent_address']);
		$data['client']['av'] = dbOUT($result['av']);
		$result = mysqli_fetch_assoc($gQ);
		$data['guarantor']['name'] = dbOUT($result['name']);
		$data['guarantor']['father_name'] = dbOUT($result['father_name']);
		$data['guarantor']['cnic'] = dbOUT($result['cnic']);
		$data['guarantor']['occupation'] = dbOUT($result['designation']);
		$data['guarantor']['phone'] = dbOUT($result['phone']);
		$data['guarantor']['address'] = dbOUT($result['address']);
		$data['guarantor']['permanent_address'] = dbOUT($result['permanent_address']);
		$data['guarantor']['relation'] = dbOUT($result['relation']);
		$data['csrf_token'] = $csrf_token;
		echo json_encode($data);
	break;
	case 'selectProduct':
		$pid = checkRequest("pid");
		$productQ = mysqli_query($con,"SELECT t.party_id, p.name from transactions t,parties p where t.type='1' and (t.status='1' or t.status='0') and t.dispute='0' and t.product_id='{$pid}' and t.party_id=p.id 
			GROUP BY p.name");
		while($productR = mysqli_fetch_assoc($productQ)){
			$data['name'][]  =      dbOUT($productR['name']);
			$data['name']['id'][] = $productR['party_id'];
		}
		$data = array_unique($data);
		$data['quantity'] = mysqli_num_rows($productQ);
		$data['csrf_token'] = $csrf_token;
		echo json_encode($data);
	break;

	case 'FetchDetail':
		$pid = checkRequest("pid"); // party id
		$product_id = checkRequest("pro_id");

		$productQ = mysqli_query($con,"SELECT invoice,id,total_amount, custom_attribute, chasing from transactions where dispute='0' and (status='1' or status='0') and type='1' and product_id='{$product_id}' and party_id='{$pid}'");
		$keys = 0;
		while($productR = mysqli_fetch_assoc($productQ)){
			$data['invoice'][] = $productR['invoice'];
			$data['id'][] = $productR['id'];
			$data['total_amount'][] = $productR['total_amount'];
			$data['custom_attribute'] = array();
			$array = json_decode($productR['custom_attribute']);
			foreach($array as $key => $val){
				$data['custom_attribute'][$keys]["key"][] = $key;	
				$data['custom_attribute'][$keys]["val"][] = $val;	
			}
			$data['chasing'][] = $productR['chasing'];
			$keys++;
		}
		$data['quantity'] = mysqli_num_rows($productQ);
		$data['csrf_token'] = $csrf_token;
		echo json_encode($data);
	break;

	case 'aboutClient':

		$id  = checkRequest("id");
		$query = mysqli_query($con,"SELECT g.permanent_address as gpdr, g.address as gaddr, g.cnic as gcnic, c.cnic as ccnic, c.account_number as acc, g.father_name as fname, c.name as client, g.name as guarantor,cast(c.created_at as date) as since, c.phone as client_phone, g.phone as gaurantor_phone, g.relation, c.occupation as designation, g.designation as gdes, c.father_name as father FROM clients c, GUARANTOR g where c.guarantor_id=g.id and c.id='{$id}'");
		$result = mysqli_fetch_assoc($query);

		$data['gcnic'] = dbOUT($result['gcnic']);
		$data['ccnic'] = dbOUT($result['ccnic']);
		$data['acc'] = dbOUT($result['acc']);
		$data['fname'] = dbOUT($result['fname']);
		$data['client'] = dbOUT($result['client']);
		$data['guarantor'] = dbOUT($result['guarantor']);
		$data['since'] = dbOUT($result['since']);
		$data['client_phone'] = dbOUT($result['client_phone']);
		$data['gaurantor_phone'] = dbOUT($result['gaurantor_phone']);
		$data['relation'] = dbOUT($result['relation']);
		$data['gdes'] = dbOUT($result['gdes']);
		$data['father'] = dbOUT($result['father']);
		$data['gpdr'] = dbOUT($result['gpdr']);
		$data['gaddr'] = dbOUT($result['gaddr']);
		$data['father'] = dbOUT($result['father']);
		$data['designation'] = dbOUT($result['designation']);
		
		echo json_encode($data);
	break;

	default:
		echo "ERROR";
	break;
}

?>