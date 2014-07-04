<?php
	session_start();
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>XXXVI Feria Internacional de Barquisimeto</title>
</head>
<body>
<table width="650" height="400" border="1" align="center">
  <tr>
     <td width="421" align="center">
	<?php
include("ControlaBD.php");
include("util.php");
$con   = new ControlaBD();
$idcon = $con->conectarSBD();
$sel_bd= $con->select_BD("p_feria2008");

$trozo2=$_GET["valor"];
 $result= $con->ejecutar("SELECT * FROM acreditado where cod_b like '%$trozo2%'" ,$idcon);
$num_rows=mysql_num_rows($result);
	if ($num_rows <= 0){
		js_redireccion("error.php?msn=No se Encontraron Registros.....");exit;
   }else{	
	   while ($row=mysql_fetch_array($result)) {
		   $result2= $con->ejecutar("SELECT * FROM tipacr WHERE codtacr = ".$row['tipacr'] ,$idcon);
		   $row2=mysql_fetch_array($result2);	
	  	   $result3= $con->ejecutar("SELECT * FROM empresa WHERE rif = '".$row['rif']."'",$idcon);
		   $row3=mysql_fetch_array($result3);
		   $empresa = 	strtoupper($row3['nombre']);
		  ?> 
		   <img src=<?php echo $row['foto']  ?> width="200" height="200" vspace="10" hspace="50" border="1"><br>
		 <?php 
		   echo "<font style='font-family:Arial, Helvetica, sans-serif; font-size:18px'><strong>".$row['nombre'].'</strong></font><br>';
		  echo "<font style='font-family:Arial, Helvetica, sans-serif; font-size:18px'><strong>".$row['cedula'].'</strong></font><br>';			  
		  echo "<br><font style='font-family:Arial, Helvetica, sans-serif; font-size:18px'><strong>".$row2['descrip'].'</strong></font><br>';
		  echo "<br><font style='font-family:Arial, Helvetica, sans-serif; font-size:15px'>".$empresa."</font><br><br>";
		  $fecha = time();
          /*Para darle el formato que quieras año:mes:dia:hora:minuto:segundo*/        
          date ( "Y:n:j:g:i:s" , $fecha );
         /*Para mostrarla*/ 
          echo 'FECHA: '.date ( "j" ).' de Septiembre del '.date ( "Y" ).'<BR> HORA:  '.(date ( "g" ) -1).':'.date ( "i" );  
		 
		}
	}
 
 ?>
    <!--<img src="Imagenes/Bien.gif">--></td>
			  <tr>

 </tr>
  </tr>
  <!---tr>
    <td>&nbsp;</td>
    <td width="279"><div align="right"><img src="Imagenes/logos.gif"></div></td>
    <td width="196"><div align="center"><img src="Imagenes/fabiolaI.gif"></div></td>
  </tr--->
</table>
</body>
</html>

