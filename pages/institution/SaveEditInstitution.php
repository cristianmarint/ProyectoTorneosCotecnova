<?php
  require_once '../ConnectionDB.php';

foreach ($_REQUEST as $var => $val){$$var =  $val;} //obtener todos los datos provenientes del formulario


  $sql = "UPDATE instituto SET nit = '$EditInstitutionNit', codigo_dane =  '$EditInstitutionDane', nombre_institucion = '$EditInstitutionName', id_municipio = '$EditInstitutionCiudad', tipo_de_educacion = '$EditInstitutionTipoEducacion', direccion = '$EditInstitutionDireccion', telefono = '$EditInstitutionTelefono'
          WHERE nit = '$oldNit'";

  $result = mysqli_query($conexion, $sql);

  if($result){
    $data['result'] = 'ok';
  }else{
    $data['result'] = 'error';
  }


  echo json_encode($data);
 ?>