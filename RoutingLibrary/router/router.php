<?php

require('conf.php');
$S_AD = $_SERVER['SERVER_ADDR'];
$R_AD = $_SERVER['REMOTE_ADDR'];

$is_local = substr($S_AD,0,mb_strrpos($S_AD,'.')) == substr($R_AD,0,mb_strrpos($R_AD,'.'));

$file_pass = $is_local ? $local_file_pass : $notlocal_file_pass;

$pass = $_SERVER["REQUEST_URI"];
$pass = substr($pass,strpos($pass,'/') + 1);
$pass = substr($pass,strpos($pass,'/'));
$querys = strstr($pass, '?');
$pass = substr($pass,0,strcspn($pass,'?'));
$pass_files = mb_split('/', $pass);


if($redirect != ROUTER_REDIRECT_NO){
  if($redirect == ROUTER_REDIRECT_NOSLASH && $pass_files[count($pass_files)-1] == ''){
    array_pop($pass_files);
    array_shift($pass_files);
    $redirect = $file_pass.implode('/',$pass_files);
    //echo $redirect;
    header('Location:'.$redirect);
    exit;
  }else if($redirect == ROUTER_REDIRECT_SLASH && $pass_files[count($pass_files)-1] != ''){
    array_shift($pass_files);
    $redirect = $file_pass.implode('/',$pass_files).'/';
    //echo $redirect;
    header('Location:'.$redirect);
    exit;
  }
}


foreach ($routes as $key0 => $route) {
  $route_file = $route[0];
  $route_pass = $route[1];
  $route_files = mb_split('/', $route_file);

  $results = array();

  if(count($route_files) == count($pass_files)){
    foreach ($pass_files as $key1 => $pass_file) {
      $results[] = ($pass_file == $route_files[$key1] || isset($route_files[$key1][0]) && $route_files[$key1][0] == '$');
    }
  }else{
    $results[] = false;
  }

  if(!in_array(false,$results)){
    $result_pass = $file_pass.$route_pass;
    foreach ($route_files as $key2 => $route_file) {
      if(isset($route_file[0]) && $route_file[0] == '$'){
        $var_name = '';
        for ($i=1; $i < strlen($route_file); $i++) {
          $var_name .= $route_file[$i];
        }
        $var_value = $pass_files[$key2];
        $result_pass = str_replace('$'.$var_name,$var_value,$result_pass);
      }
    }
    if(strpos($result_pass,'?') !== false){
      $result_pass .= '&'.ltrim($querys, '?');
    }else{
      $result_pass .= $querys;
    }
    echo file_get_contents($result_pass);
    return;
  }
}

header("HTTP/1.0 404 Not Found");


?>
