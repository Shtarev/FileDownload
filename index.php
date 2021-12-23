<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html" charset="utf-8" />
	<script src="js/jquery-3.4.1.js"></script>
	<title>File upload</title>
</head>
<style>
#wrapper{
	width: 60%;
	margin: 20px auto;
}
#process {
	display: none;
}
#example {
	display: block;
}
form button{
	margin-bottom: 50px;
}
input[type=text],input[type=file]{
	margin-bottom: 20px;
}
</style>
<body>
<div id="wrapper">
	<h1>File upload</h1>
	<form method="post" enctype="multipart/form-data" id="form-file-ajax" action="upload.php">
		<input type="file" id="file" name="file" required><br>
		<button type="submit" id="btn-file-upload">Загрузить</button>
		<div id="process"><img src="upload/example/preloader.gif" alt="Loading" width="200"></div>
		<div id="photo-content"></div>
	</form>
</div>
<script>
	function del(id, file) {  
		$.ajax({  
			type: "POST",
			url: "upload.php",
			data: {del: file}
		}).done(function( result )
			{
				$('#'+id).remove();
			}); 
		
	}
    $(document).ready(function(){
      $("#form-file-ajax").on('submit', function(e){
        e.preventDefault();
        var formData = new FormData();
        var form = $(this);
        formData.append('file', $('#file').prop("files")[0]);
        $.ajax({
          url: form.attr('action'),
          type: form.attr('method'),
          processData: false,
          contentType: false,
          cache:false,
          dataType : 'text',
          data: formData,
          beforeSend: function(){
            $('#process').fadeIn();
			$('#example').fadeOut();
          },
          complete: function () {
            $('#process').fadeOut();
			$('#example').fadeIn();
          },
          success: function(data){
            //form[0].reset();
            data = JSON.parse(data);
			var id = getRandomInt(1, 999999);
			if(data.definition) {
				var image = '<div id="'+id+'" class="img-item"><hr><img src="upload/'+data.view+'" width="50">'+data.info+' <img onclick="del('+id+', \''+data.file+'\')" src="upload/example/delete.png" width="25" style="cursor: pointer"></div>';
				var photoContent = $("#photo-content");
				photoContent.append(image);
			}
			else {
				var image = '<div id="'+id+'" class="img-item"><hr><img src="upload/example/error.jpg" width="50">'+data.info+'</div>';
				var photoContent = $("#photo-content");
				photoContent.append(image);
			}
          },
          error: function(data){
            console.log(data);
          }
        });
      });
    });
	function getRandomInt(min, max) {  
		return Math.floor(Math.random() * (max - min + 1)) + min;  
	} 
</script>
</body>
</html>