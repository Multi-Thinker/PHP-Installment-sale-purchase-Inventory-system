<footer>
<!-- jQuery -->
<script src="js/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script src="js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script src="js/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script src="js/startmin.js"></script>

<script src="js/dataTables/jquery.dataTables.min.js"></script>
<script>
	function getConfirmation(str,id,avatar=null,custom=null){
		var conf = confirm(str);
		if(conf==false){
			return false;
		}else{
			if(custom==null){
				if(avatar==null){
					window.location.href="?action=delete&UID="+id;
				}else{
					window.location.href="?action=delete&UID="+id+"&avatar="+avatar;
				}
			}else{
				window.location.href=custom;
			}
			return true;
		}
	}
	$(".sort_table").dataTable();
</script>
</footer>
</html>