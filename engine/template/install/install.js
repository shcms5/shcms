	check_eula = function()
	{
		if( document.getElementById('eula').checked == true )
		{
			return true;
		}
		else
		{
			alert( 'Для продолжения установки необходми согласиться с условиями лицензии' );
			return false;
		}
	}
	document.getElementById('install-form').onsubmit = check_eula;