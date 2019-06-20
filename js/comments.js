$.fn.exists = function () {
    return this.length !== 0;
}

function refresh_shoutbox() {
	var parent = $("#parent").val();
	var data = 'refresh=1' + '&parent='+ parent;

	$.ajax({
		type: "POST",
		url: "/mvc/server/commentsServer.php",
		data: data,
		success: function(html){
			$("#comments").html(html);
		}
	});
}

$(document).ready(function(){
	if($('#comments').exists())
	{
		//заполнить страницу в первый раз
		refresh_shoutbox();
		// обновить каждые 15 секунд
		setInterval("refresh_shoutbox()", 15000);
	}
});

$(document).ready(function(){
	$('form.commentForm').submit(function(e){
		// Запрещаем стандартное поведение для кнопки submit
		e.preventDefault();
		// получаем то, что написал пользователь
		var user_id = $("#user").val();
		var message = $("#message").val();
		var parent = $("#parent").val();
		// Формируем строку запроса
		var data= 'user='+ user_id +'&message='+ message +'&parent='+ parent;
		// ajax вызов
		$.ajax({
			type: "POST",
			url: "/mvc/server/commentsServer.php",
			data: data,
			success: function(html){ // после получения результата
				$("#comments").html(html);
				$("#message").val("");
			}
		});
		return true;
	});
});