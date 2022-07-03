<?php

use Illuminate\Support\Facades\Storage;

/**
 * Función que valida un array de datos
 * @param {Array} $data Reglas de validación
 * @return {bool} 
 */
function validate($data = []){
  $errors = [];
  //echo '<pre>';
  foreach($data as $name => $cont){
    $value = $cont[0];
    $rules = explode('|', $cont[1]);
    foreach($rules as $rule){
      $type = explode(':', $rule);
      switch($type[0]){
        case 'required':
          $res = requiredVal($value);
          if(isset($res[0]) && !$res[0]){
            if(!isset($errors[$name])){
              $errors[$name] = isset($res[1]) && $res[1] ? $res[1] : 'Error';
            }
          }
          break;
        case 'min':
          $res = minVal($value, $type[1]);
          if(isset($res[0]) && !$res[0]){
            if(!isset($errors[$name])){
              $errors[$name] = isset($res[1]) && $res[1] ? $res[1] : 'Error';
            }
          }
          break;
        case 'max':
          $res = maxVal($value, $type[1]);
          if(isset($res[0]) && !$res[0]){
            if(!isset($errors[$name])){
              $errors[$name] = isset($res[1]) && $res[1] ? $res[1] : 'Error';
            }
          }
          break;
      }
    }
  }
  //echo '</pre>';
  return $errors;
}

/**
 * Función que valida si un valor tiene contenido
 * @param {Array} $value Valor como un string
 * @return {bool} 
 */
function requiredVal($value = null){
  $res = [true, ''];
  if($value == '' || $value == null){
    $res[0] = false;
    $res[1] = 'Valor requerido';
  }
  return $res;
}

/**
 * Función que valida si un valor tiene un mínimo de valor
 * @param {string} $value Valor como un string
 * @param {int} $min Mínimo del valor
 * @return {bool} 
 */
function minVal($value = null, $min = 0){
  $res = [true, ''];
  if(strlen($value) < $min){
    $res[0] = false;
    $res[1] = "Mínimo {$min} carácteres";
  }
  return $res;
}

/**
 * Función que valida si un valor tiene un máximo de valor
 * @param {string} $value Valor como un string
 * @param {int} $max Máximo del valor
 * @return {bool} 
 */
function maxVal($value = null, $max = 0){
  $res = [true, ''];
  if(strlen($value) > $max){
    $res[0] = false;
    $res[1] = "Máximo {$max} carácteres";
  }
  return $res;
}

/**
 * Crea un directorio en la carpeta data
 * @return {bool} 
 */
function makeDir($type=""){
  $type = $type ? "{$type}/" : "";
  $local = env('APP_ENV') == "local";
  $pathAr = ["absolute"=>"", "relative"=>""];
  $milisec = round(microtime(true) * 1000);
  $pathName = uniqueId();
  $pathToRel = "data/{$type}".date('Y')."/".date('m')."/".date('d')."/".date('H')."/".date('i')."/".date('s')."/".$milisec."/".$pathName."/";
  //$pathToAbs = public_path()."/".$pathToRel;
  $pathToAbs = $local ? public_path()."/".$pathToRel : base_path()."/public_html/".$pathToRel;
  //Storage::makeDirectory($pathTo);
  mkdir($pathToAbs, 0777, true);
  $pathAr["relative"] = $pathToRel;
  $pathAr["absolute"] = $pathToAbs;
  return $pathAr;
}

/**
 * Generación de identificador único
 * fuente: https://programmerclick.com/article/2709943394/#:~:text=La%20función%20PHP%20uniqid%20(),gran%20cantidad%20de%20datos%20repetidos.
 * @return identificador único
 */
function uniqueId(){
  return md5(uniqid(md5(microtime(true)),true));
}

?>