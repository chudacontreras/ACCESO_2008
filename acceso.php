<?php
	session_start();
?>
<?php
    include("util.php"); // INCLUDE PARA LLAMAR A UNA PAGINA
	include("ControlaBD.php");

	if ($_POST[acceso]== "entra"){
		$_SESSION[entra] = "checked='checked'";
		$_SESSION[sale] = "";
	}else{
		$_SESSION[entra] = "";
		$_SESSION[sale] = "checked='checked'";
	}

	$con   = new ControlaBD();
	$idcon = $con->conectarSBD();
	$sel_bd= $con->select_BD("p_feria2008");
	$hoy = date("Y-m-d");
	//$dia = 'D'.date("d");
	$dia = 'D11';

/* ***************************************INICIO VALIDACIONES************************************/	
	//Inicio: Valida ingreso un codigo de barra
	if ($_POST["cod"]==''){
	    //echo "Debe Ingresar un Codigo de Barra";
	    js_redireccion("error.php?msn=Debe ingresar un codigo de barra"); //ENVIA A LA PAG. ERRO.PHP
		exit;
	} 
	//Fin: Valida ingreso un codigo de barra 
	
	//Inicio: Valida si selecciono o no la Salida o Entrada

	if ($_POST["acceso"]==''){
	    // echo "Debe Seleccionar si es Entrada o Salida";
	    js_redireccion("error.php?msn=Debe Seleccionar si es Entrada o Salida"); //ENVIA A LA PAG. ERRO.PHP
		exit;
	} 
	//Fin: Valida si selecciono o no la Salida o Entrada
/* ***************************************FIN VALIDACIONES************************************/	
    
     $trozo = substr($_POST["cod"], 0, 1); //toma la primera letra del codigo de barra
	 $trozo2 = substr($_POST["cod"], 1, 13); //toma la primera letra del codigo de barra

	 if ($_POST["acceso"]=='entra')
	 { //INICIO CUANDO ES ENTRADA
	 
		  if ($trozo=='A')
		  { //INICIO CUANDO ES ABONO
			
				$resaccesoab= $con->ejecutar("SELECT * FROM accesoabono WHERE codigo='$_POST[cod]'",$idcon);
			   //$accesoabo = mysql_fetch_array($resaccesoab);
			   $rowabo=mysql_num_rows($resaccesoab);
			   if ($rowabo==0){
				  js_redireccion("error.php?msn=El Abono no Existe"); //ENVIA A LA PAG. ERRO.PHP
				  exit;
			   }else{
			   		$filaabo=mysql_fetch_array($resaccesoab);
					if ($filaabo[$dia] != '0000-00-00 00:00:00'){
						js_redireccion("error.php?msn= La Entrada del día ".substr($dia, 1, 2)." ya fué agotada:<br><br>a las ".substr($filaabo[$dia], 11, 9)."");
					}
					$fecha = date("Y-m-d")." ".(date("H")-1).":".date("i:s");
					//echo "UPDATE accesoabono set $dia = '".$fecha."' WHERE codigo='$_POST[cod]'"; exit;
					$resaccesoab= $con->ejecutar("UPDATE accesoabono set $dia = '".$fecha."' WHERE codigo='$_POST[cod]'",$idcon);
					js_redireccion("bienA.php");
			   }  
		  } //FIN CUANDO ES ABONO
	      else{ //INICIO CUANDO ES ACREDITACION PERSONALIZADA O ROTATIVA
		  
		     if ($trozo=='C'){
				$resaccesoac= $con->ejecutar("SELECT * FROM acreditado WHERE cod_b like '%$trozo2%'",$idcon);
			   }else{
				$resaccesoac= $con->ejecutar("SELECT * FROM acrrot WHERE cod_b = '$_POST[cod]'",$idcon);
			   }	
			   $rowacr=mysql_num_rows($resaccesoac);
	           if ($rowacr==0){ //cuando es 0 no existe
		         js_redireccion("error.php?msn=La Acreditación no existe"); //ENVIA A LA PAG. ERRO.PHP
		         exit;
		       }else{ //inicio acreditacion existe
			       $accesacr = mysql_fetch_array($resaccesoac);
				    if ($accesacr[tipacr]<100){  // inicio acreditaciones personalizadas
			  		    if ($accesacr[control]==1){  // igual a 1, acreditado ya esta dentro
						   js_redireccion("error.php?msn=¡¡¡¡¡ El Acreditado está dentro !!!!!<br><br>La credencial debe ser Decomisada");
				        }else{ // para los casos distintos a 1, acreditado podra entrar
						     if ($trozo=='C'){
							    $resaccesoab= $con->ejecutar("UPDATE acreditado set control = 1, contador = contador + 1 WHERE cod_b like '%$trozo2%'",$idcon);
							    js_redireccion("conte.php?valor=$trozo2");exit;
								
						    }else{
							    $resaccesoab= $con->ejecutar("UPDATE acrrot set control = 1, contador = contador + 1 WHERE cod_b = '$_POST[cod]'",$idcon);
							    js_redireccion("conteR.php?valor=$trozo2");exit;
						    }
					   }	
				  }//fin acreditaciones personalizadas
				  else{//inicio acreditaciones rotativas
				    if ($accesacr[control]==1){
						js_redireccion("error.php?msn=El Acreditado está dentro");exit;					
					}else{
						if ($accesacr[contador]==3){
							js_redireccion("error.php?msn=Ha excedido la cantidad de accesos para el día");exit;			
						}else{
							$resaccesoab= $con->ejecutar("UPDATE acrrot set control = 1, contador = contador + 1 WHERE cod_b = '$_POST[cod]'",$idcon);	
							js_redireccion("conte.php");exit;
						}
					}
				  }
			   
			   }//fin acreditacion existe
	 
	      } //FIN CUANDO ES ACREDITACION PERSONALIZADA O ROTATIVA
	   
	 } //FIN CUANDO ES ENTRADA
	
	
	// INICIO DE CUANDO ES SALIDA
	elseif($_POST["acceso"]=='sale'){
		if ($trozo=='C'){
			$resaccesoab= $con->ejecutar("UPDATE acreditado set control = 0 WHERE cod_b like '%$trozo2%'",$idcon);
			js_redireccion("hl.php");exit;
		}else{
			$resaccesoab= $con->ejecutar("UPDATE acrrot set control = 0 WHERE cod_b = '$_POST[cod]'",$idcon);
		    js_redireccion("hl.php");exit;
		}
	} 
   // FIN DE CUANDO ES SALIDA

?>

