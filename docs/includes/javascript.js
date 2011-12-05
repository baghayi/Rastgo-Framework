window.onload = init;

function init()
{
	dynamicTitleDir();
}

function dynamicTitleDir()
{
	var title = document.getElementById('title'),
		res = new RegExp('[a-zA-Z\.\s\(\)]+', 'g');
	
	if(title['title'] == res.exec(title['title']))
	{
		title.className = 'forLeftUsers';
	}
	
	return;
}
