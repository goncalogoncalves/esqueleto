<script type="text/javascript">
$(document).ready(function() {
	var location = '<?=$data["location"]?>';
	if (location != "") {
		window.location.href = BASE_URL + location;
	}else{
		window.location.href = BASE_URL;
	}
});
</script>
