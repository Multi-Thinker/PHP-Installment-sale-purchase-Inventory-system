<?php
$functionsINNER=TRUE;
$functions['TRADITIONAL']=1;
include "config.php"; 
$userInfo = userInfo();
?>
<?php include "tHeader.php"; ?>
<style>
#img-upload{
	height: auto !important;
}
</style>
<h1 class="page-header">Clients</h1> 

		
	<?=log_message();?>
	<!--   -->
	<form action="Save.php?function=addClient" method="POST" enctype="multipart/form-data" />
	<div class="col-md-12">
		<div class="row">
		<div class="col-md-4">
				<label for="invoice">PROFILE PICTURE :</label>
			<div class="input-group">
	            <span class="input-group-btn">
	                <span class="btn btn-default btn-file">
	                    Browse… <input type="file" name="picture" id="imgInp" accept="image/*">
	                </span>
	            </span>
	            <input type="text" class="form-control" readonly>
	        </div>
	        <br>
			<p align="center">
			        <img id='img-upload'/>
			</p>
			</div> 			
		
		<div class="col-md-8">
			<p>			
				<input type="text" id="account_number" placeholder="ACCOUNT NO" required="required" name="account_no" class="form-control">
			</p>
			<p>			
				<input type="text" id="client_name" placeholder="CLIENT NAME" required="required" name="client_name" class="form-control">
			</p>
			<p>			
				<input type="text" id="client_fname" placeholder="FATHER NAME" required="required" name="client_fname" class="form-control">
			</p>
				<p>			
				<input type="text" id="client_cnin" placeholder="CNIC" name="client_cnin" required="required" class="form-control">
			</p>
				<p>			
				<input type="text" id="client_phone" placeholder="PHONE NUMBER" required="required" name="client_phoneno" class="form-control">
			</p>
			<p>			
				<input type="text" id="client_occupation" placeholder="OCCUPATION" name="client_occupation" class="form-control">
				<input type="hidden" required="required" name="csrf_token" value="<?=$csrf_token?>">
			</p>
		</div>

		</div>
			<div class="col-md-12">
			<p>			
			<input type="text" id="client_address" required="required" placeholder="ADDRESS" name="client_address" class="form-control textarea">	
			</p>
			<p>			
			<input type="text" id="client_permanent_address" placeholder="PERMANENT ADDRESS" required="required" name="client_permanent_address" class="form-control textarea">	
			</p>
			</div>
			<div class="text-center">
				<h3>GUARANTOR DETAIL</h3>
			</div>
			<div class="row">
			<div class="col-md-12">
				<div class="col-md-6">
			<p>			
				<input type="text" id="guarantor_name" placeholder="GUARANTOR NAME" name="guarantor_name" class="form-control">
			</p>
			<p>			
				<input type="text" id="guarantor_fname"  placeholder="FATHER NAME" name="guarantor_fname" class="form-control">
			</p>
				
			<p>			
				<input type="text" id="guarantor_occupation" placeholder="OCCUPATION" name="guarantor_occupation" class="form-control">
			</p>
				</div>				
				<div class="col-md-6">
				<p>			
				<input type="text" id="guarantor_cnin" placeholder="CNIC" name="guarantor_cnin" class="form-control">
			</p>
				<p>			
				<input type="text" id="guarantor_phone" placeholder="PHONE NUMBER" name="guarantor_phoneno" class="form-control">
			</p>
				<p>			
				<input type="text" id="guarantor_relation" placeholder="RELATION" name="guarantor_relation" class="form-control">
			</p>

				</div>
				<div class="col-md-12">
					<p>			
						<input type="text" id="client_address" placeholder="ADDRESS"  name="gclient_address" class="form-control textarea">	
					</p>
					<p>			
						<input type="text" id="client_permanent_address" placeholder="PERMANENT ADDRESS"  name="gclient_permanent_address" class="form-control textarea">	
					</p>
					<p>
					<input type="submit" class="btn btn-primary" value="Save" name="csave" />
					<a href="Clients.php"><input type="button" role="button" class='btn btn-info' value='Go Back' /></a>
					</p>
				</div>

			</div>
			
			</div>




<?php include "tFooter.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.13/jquery.mask.min.js"></script>

<script type="text/javascript">
	
	$(document).ready( function() {
    	$(document).on('change', '.btn-file :file', function() {
		var input = $(this),
			label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
		input.trigger('fileselect', [label]);
		});

		$('.btn-file :file').on('fileselect', function(event, label) {
		    
		    var input = $(this).parents('.input-group').find(':text'),
		        log = label;
		    
		    if( input.length ) {
		        input.val(log);
		    } else {
		        if( log ) alert(log);
		    }
	    
		});
		function readURL(input) {
		    if (input.files && input.files[0]) {
		        var reader = new FileReader();
		        
		        reader.onload = function (e) {
		            $('#img-upload').attr('src', e.target.result);
		        }
		        
		        reader.readAsDataURL(input.files[0]);
		    }
		}

		$("#imgInp").change(function(){
		    readURL(this);
		}); 	
		$("#client_cnin,#guarantor_cnin").mask("00000-0000000-0",{placeholder: "_____-_______-_"});
		$("#client_phone,#guarantor_phone").mask("0000-0000000",{placeholder: "____-_______"});
		
	});
</script>