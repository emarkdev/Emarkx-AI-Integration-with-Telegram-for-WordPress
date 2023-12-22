document.addEventListener('DOMContentLoaded', function() {
	var mainButton = document.getElementById('main');
	var documentationButton = document.getElementById('documentation');
	var content1Div = document.getElementById('content1');
	var content2Div = document.getElementById('content2');
	
	var telegram_bot = document.getElementById('telegram_bot');
	var gpt_key = document.getElementById('gpt_key');
	var contenttelegram = document.getElementById('contenttelegram');
	
	var response_speed = document.getElementById('response_speed');
	var contentresponse = document.getElementById('contentresponse');

	mainButton.addEventListener('click', function() {
		content2Div.style.display = 'none';
		content1Div.style.display = 'block';
	});

	documentationButton.addEventListener('click', function() {
		content1Div.style.display = 'none';
		content2Div.style.display = 'block';
	});
	
	telegram_bot.addEventListener('click', function() {
		contentgpt.style.display = 'none';
		contentresponse.style.display = 'none';
		contenttelegram.style.display = 'block';
		
	});

	gpt_key.addEventListener('click', function() {
		contenttelegram.style.display = 'none';
		contentresponse.style.display = 'none';
		contentgpt.style.display = 'block';
	});
	
	response_speed.addEventListener('click', function() {
		contenttelegram.style.display = 'none';
		contentgpt.style.display = 'none';
		contentresponse.style.display = 'block';
	});
	
});
