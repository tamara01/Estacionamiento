<?php
require"clases/estacionamiento.php";

$patente = $_POST['patente'];
$accion = $_POST['estacionar'];
$foto = $_FILES['archivo']['name'];


if($accion=="ingreso")
{
	//1-estacionamiento::Guardar( $patente);
//	$nomext=explode(".", $foto);//me devuelve un array con el nombre y la extension
	//move_uploaded_file(origen,destinoFinal)
//	move_uploaded_file($_FILES['archivo']['tmp_name'],"fotitos/$patente.$nomext[1]");
													//guardo en la carpeta fotitos con el nombre de la patente y la extension de la foto

//	estacionamiento::Guardar( $patente,"fotitos/$patente.$nomext[1]");
	$nomext=explode(".", $foto);//me devuelve un array con el nombre y la extension

	move_uploaded_file($_FILES['archivo']['tmp_name'],"fotitos/$patente.$nomext[1]");

	estacionamiento::Guardar( $patente,"fotitos/$patente.$nomext[1]");

}
else
{
	estacionamiento::Sacar($patente);

		//var_dump($datos);
}

header("location:index.php");


/*Función		Descripción												Sintaxis
	copy		Copia un archivo										copy($origen,$destino)
	rename		Cambia el nombre del archivo de $antes a $despues		rename($antes,$despues)
	unlink		Borra el archivo										unlink($archivo)
	fgets 		lee línea a línea el contenido de un archivo txt, por lo tanto su uso debe ser incluidos en un bucle. 


*/
?>
