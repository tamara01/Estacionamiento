<?php
session_start();
$_SESSION['mensaje'];

class estacionamiento
{

	public static function Guardar($patente,$foto)
	{
		//Abre el archivo para sólo escritura. La escritura comenzará al final del archivo, sin afectar al contenido previo. 
		//Si el fichero no existe se intenta crear.
		$archivo=fopen("archivos/estacionados.txt", "a");
		$ahora = date("d-m-Y H:i:s");

		$renglon=$patente."=>".$ahora. "=>" . $foto . "\n";
		fwrite($archivo, $renglon); 		 
		fclose($archivo);
		//estacionamiento::CrearTablaEstacionados();
		$_SESSION['mensaje']=" Se ingresó la Patente [ $patente ]";
		/*habría que validar que no se repita!*/

	}

	//Esta función la creo para que vuelva a escribir (en estacionados.txt)el listado de autos ingresados menos el que saqué
	public static function GuardarListado($listado)
	{//Abre un archivo para sólo escritura. Si no existe, crea uno nuevo. Si existe, borra el contenido.
		//necesito borrarlo de txt para volver a escribirlo
		$archivo=fopen("archivos/estacionados.txt", "w");
		foreach ($listado as $auto) {
			if(trim($auto[0]) != "")
				{
					$reglon = $auto[0] . "=>" . $auto[1] . "=>" . $auto[2]; //
					fwrite($archivo, $reglon);
				}
		}	
		fclose($archivo);
	}

	public static function Sacar($patente)//
	{
		$ListaLeida = estacionamiento::Leer();
		$listaAutosGuardados = array();

		foreach ($ListaLeida as $autito) {

			if(trim($autito[0]) != trim($patente)){
				$listaAutosGuardados[] = $autito;
			}
			else
			{		//como es: $autito[0] == $patente		
				$archivo=fopen("archivos/facturacion.txt", "a");		
				$ahora=date("Y-m-d H:i:s"); 
				$diferencia = strtotime($ahora) - strtotime($autito[1]);
				$costo = (double)$diferencia * 5;
				$renglon=$patente."=>"."$".$costo. "=>". $autito[2] ."\n"; 
				fwrite($archivo, $renglon); 		 
				fclose($archivo);
			}
		}
		
		estacionamiento::GuardarListado($listaAutosGuardados);//
		$_SESSION['mensaje'] = "Patente [ $patente ] debe abonar $ $costo";
	}
		
	public static function Leer()
	{
		$ListaDeAutosLeida=   array();
		$archivo=fopen("archivos/estacionados.txt", "r");//Abre el archivo sólo para lectura.La lectura comienza al inicio del archivo.

		//Hago un bucle para recorrer el archivo línea a línea hasta el final del archivo 	
		while(!feof($archivo))
		{	//extraigo una línea del archivo y la guardo
			$renglon=fgets($archivo);  //http://www.w3schools.com/php/func_filesystem_fgets.asp
			//explode separa la cadena mediante un separador que no puede ser vacío
			//esta función recibe una cadena de caracteres como argumento y regresa un array con las subcadenas que la forman
			$arrayAuto=explode("=>", $renglon);  //http://www.w3schools.com/php/func_string_explode.asp
			
			$arrayAuto[0]=trim($arrayAuto[0]);
			if($arrayAuto[0]!="")
				$ListaDeAutosLeida[]=$arrayAuto;
		}

		fclose($archivo);
		return $ListaDeAutosLeida; 
		//[0]:[0]Pat[1]Hora / [1]:[0]Pat[1]Hora / [2]:[0]Pat[1]Hora / [3]:[0]Pat[1]Hora / [4+]:[0]Pat[1]Hora etc

	}


	public static function CrearTablaEstacionados()//
	{	// file_exists Comprueba si existe un fichero o directorio
		if(file_exists("archivos/estacionados.txt"))
			{
				$cadena=" <table border=1><th> patente </th><th> Importe </th><th> Foto </th>";

				$archivo=fopen("archivos/estacionados.txt", "r");

			    while(!feof($archivo))
			    {
				      $renglon=fgets($archivo);
				      
				      $arrayAuto=explode("=>", $renglon);
				                    //trim devuelve una cadena con los espacios en blanco eliminados del inicio y final
				      $arrayAuto[0]=trim($arrayAuto[0]);
				      if($arrayAuto[0]!="")
				       $cadena =$cadena."<tr> <td> ".$arrayAuto[0]."</td> <td>  ".$arrayAuto[1] ."</td><td><img src=".$arrayAuto[2]." height=50px width=70px/></td> </tr>" ; 
				}
		   		$cadena =$cadena." </table>";
		    	fclose($archivo);

		    	//Abre el archivo sólo para escritura,comienza al inicio y elimina el contenido previo. Si el archivo no existe lo crea
				$archivo=fopen("archivos/tablaestacionados.php", "w"); 
				fwrite($archivo, $cadena);
				fclose($archivo);
			}	
		else
			{
				$cadena= "no hay facturación";

				$archivo=fopen("archivos/tablaestacionados.php", "w");
				fwrite($archivo, $cadena);
				fclose($archivo);
			}
	}

	public static function CrearJSAutocompletar()
	{		
			$cadena="";

			$archivo=fopen("archivos/estacionados.txt", "r");

		    while(!feof($archivo))
		    {
			      $archAux=fgets($archivo);
			      //http://www.w3schools.com/php/func_filesystem_fgets.asp
			      $auto=explode("=>", $archAux);
			      //http://www.w3schools.com/php/func_string_explode.asp
			      $auto[0]=trim($auto[0]);

			      if($auto[0]!="")
			      {
			      	 $auto[1]=trim($auto[1]);
			      $cadena=$cadena." {value: \"".$auto[0]."\" , data: \" ".$auto[1]." \" }, \n"; 
		 


			      }
			}
		    fclose($archivo);

			 $archivoJS="$(function(){
			  var patentes = [ \n\r
			  ". $cadena."
			   
			  ];
			  
			  // setup autocomplete function pulling from patentes[] array
			  $('#autocomplete').autocomplete({
			    lookup: patentes,
			    onSelect: function (suggestion) {
			      var thehtml = '<strong>patente: </strong> ' + suggestion.value + ' <br> <strong>ingreso: </strong> ' + suggestion.data;
			      $('#outputcontent').html(thehtml);
			         $('#botonIngreso').css('display','none');
      						console.log('aca llego');
			    }
			  });
			  

			});";
			
			$archivo=fopen("js/funcionAutoCompletar.js", "w");
			fwrite($archivo, $archivoJS);
	}



		public static function CrearTablaFacturado()
	{
			if(file_exists("archivos/facturacion.txt"))
			{
				$cadena=" <table border=1><th> patente </th><th> Importe </th><th> Foto </th>";

				$archivo=fopen("archivos/facturacion.txt", "r");

			    while(!feof($archivo))
			    {
				      $archAux=fgets($archivo);
				      //http://www.w3schools.com/php/func_filesystem_fgets.asp
				      $auto=explode("=>", $archAux);
				      //http://www.w3schools.com/php/func_string_explode.asp
				      $auto[0]=trim($auto[0]);
				      //var_dump($auto);
				      //die();
				      if($auto[0]!="")
						 $cadena =$cadena."<tr> <td> ".$auto[0]."</td> <td>  ".$auto[1] ."</td> <td> <img src=".$auto[2]." height=50px width=70px /></td> </tr>" ; 				}

		   		$cadena =$cadena." </table>";
		    	fclose($archivo);

				$archivo=fopen("archivos/tablaFacturacion.php", "w");
				fwrite($archivo, $cadena);

			}	
			else
			{
				$cadena= "no hay facturación";

				$archivo=fopen("archivos/tablaFacturacion.php", "w");
				fwrite($archivo, $cadena);
			}
	}
}
 //http://www.w3schools.com/php/func_filesystem_fgets.asp
     //http://www.w3schools.com/php/func_string_explode.asp
?>