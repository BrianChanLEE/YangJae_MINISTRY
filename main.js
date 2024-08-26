$.ajax({
	url: "cookiecheck.php", 
	success: function(result){

		if(result!="nothing" && result!="" && result!=null ){
			var res=result.split("|");
			
			if (res[0]=="" || res[0]==null)
			{
				return;
			}

			$("#m_name").val(res[0]);
			$("#m_pw").val(res[1]);
			$("#m_logincheck").prop("checked",true);
//			$("form").submit();
		}else{
			$("#m_logincheck").prop("checked",false);
		}

	}
});	