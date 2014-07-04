<?php
	session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Control de Acceso</title>
</head><body>
<table width="750" border="0" align="center">
  <tr>
    <td><img src="Imagenes/Bien.gif"><br><br>
	
	 </td>
  </tr>
  <tr>
  <td>
    <?php  
	      $fecha = time();
		  date ( "Y:n:j:g:i:s" , $fecha );
         /*Para mostrarla*/ 
          echo 'HORA:  '.(date ( "g" ) -1).':'.date ( "i" );  
		?>

  </td>
  </tr>
</table>


</body>
</html>
