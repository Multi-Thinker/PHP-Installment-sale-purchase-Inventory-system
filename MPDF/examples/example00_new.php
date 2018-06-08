<?php
include("../MPDF.php");

$html = '<style>
.customers {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

.customers td, .customers th {
    border: 1px solid #ddd;
    padding: 8px;
}

.customers tr:nth-child(even){background-color: #f2f2f2;}

.customers tr:hover {background-color: #ddd;}

.customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: grey;
    color: white;
}
</style>
		<center><img src="zb.jpg" style="margin:0 auto;text-align:center;" width="180" /></center>

		<table width="100%" class="customers">
			<tr>
				<th>Customer Details</th>
				<th></th>
				<th><barcode code="2222" text="2" class="barcode" /></th>
			</tr>

			<tr>
				<td align="left">Name: <br>Talha Habib</td>
				<td align="left">Address: <br>ABC ABC ABC ABC ABC ABC</td>
				<td align="left">CNIC: <br>33102-1233445-1</td>
			</tr>
			<tr>
				<td align="left">Gaurantor Name: <br>Noman</td>
				<td align="left">Address: <br>ABC ABC ABC ABC ABC ABC</td>
				<td align="left">CNIC: <br>33102-1233445-1</td>
			</tr>
			<tr>
				<th>Transaction Detail</th>
				<th></th>
				<th><barcode code="123" text="2" class="barcode" /></th>
			</tr>
			<tr>
				<td align="left">Party: <br>Bajwa Corp</td>
				<td align="left">Date Purchased: <br>1/16/2017 12:22:30 AM</td>
				<td align="left">Remarks: <br>Noman referred</td>
			</tr>
			<tr>
				<td align="left">Recipe Number: <br>12345</td>
				<td align="left">Order Date: <br>1/16/2018 12:22:30 AM</td>
				<td align="left">Invoice: <br>123123-123</td>
			</tr>
			<tr>
				<td align="left">Product: <br>Honda Bike 70 CC</td>
				<td align="left">Type: <br>Purchase</td>
				<td align="left">Total:<br>$471</td>
			</tr>
			<tr>
				<td align="left">Billing date: <br>10th of month</td>
				<td align="left">Purchased on: <br>Installements</td>
				<td align="left">Remaining fund:<br>$271</td>
			</tr>
			<tr>
				<td align="left">Total Installments: <br>4</td>
				<td align="left">Current Installment Number: <br> 3 of 4</td>
				<td align="left">Remaining Installments: <br> 1</td>
			</tr>
		</table>
		<table width="100%" class="customers">
			<tr>
				<th>Product Attributes:</th>
				<th></th>
			</tr>
			<tr>
				<td align="left">Engine</td>
				<td align="left">70 cd</td>
			</tr>
			<tr>
				<td align="left">Color</td>
				<td align="left">Red</td>
			</tr>
			<tr>
		  		<td align="left">Model</td>
		  		<td align="left">2017</td>
		  	</tr>
		</table><br>
		';
		/*


Order Date :
 : 32173430
Transaction ID : 37675251
User Name : talhahabib
Address : Talha Habib
House Number 199
Street 4 Noorpur
Faisalabad
Punjab , 38000
PK
Payment Source : CreditCard
Initial Charge : $47.61
Final Cost : $47.61
Total Refund : $0.00
Refund Transaction
ID
: N/A
Refunded To : N/A

TYPE NAME QTY DURATION PRICE SUB TOTAL
RENEW Domain Renewal
codeot.com
1 1 year $8.55 $8.55
ICANN Fee  $0.18
RENEW Value 4G
for codeot.com (Jan 18
2018 - Jan 18 2019)
1 1 year $38.88 $38.88
ICANN Fee  $0.00
    Location: US $0.00 $0.00
    IP allocation: Dedicated IP: no $0.00 $0.00
Sub Total $47.61
TOTAL $47.61*/
$mpdf=new mPDF('c');
$mpdf->WriteHTML($html);
$mpdf->Output();
$mpdf->debug = true;

exit;
?>
