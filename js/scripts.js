function deleteFile(iFrameName,linkId){
	var IFrame = document.getElementById(iFrameName);
	var URL = IFrame.contentWindow.location.href;
	var html = URL.search("DB/");
	if(html != -1){
		if(URL.search("html") != -1){
			html = URL.substr(html+3,6);
			html = html+'.html';
			var link = document.getElementById(linkId).href = 'remove.php?remove_file='+html;
		}
	}
	else alert('File cant be deleted');
}

function inactiveFile(iFrameName,linkId){
	var IFrame = document.getElementById(iFrameName);
	var URL = IFrame.contentWindow.location.href;
	var html = URL.search("DB/");	
	if(html!=-1){
		if(URL.search("html")!=-1){
			html = URL.substr(html+3,6);
			html = html+'.html';
			var link = document.getElementById(linkId).href = 'inactive.php?remove_file='+html;
		}
	}
	else alert('File cant be inactive');
}
