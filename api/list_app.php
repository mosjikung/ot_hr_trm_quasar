<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Set-Cookie: cross-site-cookie=whatever; SameSite=None; Secure; SameSite=Strict; SameSite=Lax");
require_once __DIR__ . '/../api/web_config.php';

set_time_limit ( 60000 );

use \Psr\Http\Message\ResponseInterface as Response; // ไลบราลี้สำหรับจัดการคำร้องขอ
use \Psr\Http\Message\ServerRequestInterface as Request; // ไลบราลี้สำหรับจัดการคำตอบกลับ

require './vendor/autoload.php'; // ดึงไฟ์ autoload.php เข้ามา
//include_once './class.oracle.php'; // Class Connect Oracle
include_once './util.php'; // ดึงไฟ์ util.php เข้ามา
include_once './web_config.php';
$app = new \Slim\App; // สร้าง object หลักของระบบ

date_default_timezone_set("Asia/Bangkok");

function ConnectDbAll($_sql){
  $DataRows = array();
  $conn = ConnectedDBSO();
  if(!$conn){
    $_err = oci_error();
    echo $_err;
  }else{
    $objParse = oci_parse($conn,$_sql);
    $objEx = oci_execute($objParse);
    if($objEx){
      $objResult = oci_fetch_all($objParse,$DataRows,null,null, OCI_FETCHSTATEMENT_BY_ROW);
    }else{
      echo "Connect Data Base error";
    }
  }
  oci_close($conn);
  return $DataRows;
 
}
function ConnectDbnoAll($_sql){
  $DataRows = array();
  $conn = ConnectedDBSO();
  if(!$conn){
    $_err = oci_error();
    echo $_err;
  }else{
    $objParse = oci_parse($conn,$_sql);
    $objEx = oci_execute($objParse);
    if($objEx){
      
    }else{
      echo "Connect Data Base error";
    }
  }
  oci_close($conn);
  return $DataRows;
  
}

$app->get('/get_list', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url


  $_sql = "SELECT * FROM TA_DEPARTMENT";
  
 $result_out = new stdClass();
    $DataRows = [];
    $resultArray = array(); //data
    $DataRows = ConnectDbAll($_sql);
  
    if($DataRows != false){
    foreach ($DataRows as $row){
      array_push($resultArray,$row);
    }
    $result_out->data =  $resultArray;
    $result_out->status = (true);
    $result_out->sql = $_sql;
  }else{
    $result_out->data =  $resultArray;
    $result_out->status = (false);
    $result_out->sql = $_sql;
  }
  
    $SearchArray = [
  
    ];
  
  $resultArray2 = array();
  array_push($resultArray2,$SearchArray);  
  $response->getBody()->write(json_encode($result_out)); // สงคำตอบกลับ
    return $response; // ส่งคำตอบกลับร้า
  });

  $app->post('/insert_dept', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $dept_name = isset($_REQUEST['dept_name']) ? $_REQUEST['dept_name'] : '';
    $dept_emp_app = isset($_REQUEST['dept_emp_app']) ? $_REQUEST['dept_emp_app'] : '';
    $divison_emp_app = isset($_REQUEST['divison_emp_app']) ? $_REQUEST['divison_emp_app'] : '';
    $shift = isset($_REQUEST['rshift']) ? $_REQUEST['shift'] : '';
  
  $_sql = "INSERT INTO TA_DEPARTMENT (DEPT_CODE , DEPT_NAME , DEPT_EMP_APP , DIVISON_EMP_APP , SHIFT) 
  VALUES ('".$dept_code."','".$dept_name."','".$dept_emp_app."','".$divison_emp_app."','".$shift."')";
  $_sql2 = "COMMIT";
  
    $result_out = new stdClass();
    $DataRows = ConnectDbnoAll($_sql,$_sql2);

    if ($DataRows !== false) {
  
  
      $result_out->status = (true);
      $result_out->sql = ($_sql);
    } else {
  
      $result_out->status = (false);
      $result_out->sql = ($_sql);
    }
  
    $SearchArray = [];
  
    $resultArray2 = array();
    array_push($resultArray2, $SearchArray);
    $response->getBody()->write(json_encode($result_out)); // สงคำตอบกลับ
  });

  $app->post('/check_value_prepare_update', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $dept_name = isset($_REQUEST['dept_name']) ? $_REQUEST['dept_name'] : '';
    $dept_emp_app = isset($_REQUEST['dept_emp_app']) ? $_REQUEST['dept_emp_app'] : '';
    $divison_emp_app = isset($_REQUEST['divison_emp_app']) ? $_REQUEST['divison_emp_app'] : '';


    
    if($dept_emp_app == "is null" && $divison_emp_app == "is null"){
      $_sql = "SELECT * FROM TA_DEPARTMENT WHERE DEPT_CODE = '".$dept_code."' and DEPT_NAME = '".$dept_name."' and DEPT_EMP_APP  ".$dept_emp_app."
      and DIVISON_EMP_APP  ".$divison_emp_app." ";
    }
    else if($dept_emp_app == "is null"){
      $_sql = "SELECT * FROM TA_DEPARTMENT WHERE DEPT_CODE = '".$dept_code."' and DEPT_NAME = '".$dept_name."' and DEPT_EMP_APP  ".$dept_emp_app."
      and DIVISON_EMP_APP = '".$divison_emp_app."'";
    }
    else if($divison_emp_app == "is null"){
      $_sql = "SELECT * FROM TA_DEPARTMENT WHERE DEPT_CODE = '".$dept_code."' and DEPT_NAME = '".$dept_name."' and DEPT_EMP_APP = '".$dept_emp_app."'
      and DIVISON_EMP_APP ".$divison_emp_app."";
    }else{
      $_sql = "SELECT * FROM TA_DEPARTMENT WHERE DEPT_CODE = '".$dept_code."' and DEPT_NAME = '".$dept_name."' and DEPT_EMP_APP = '".$dept_emp_app."'
      and DIVISON_EMP_APP = '".$divison_emp_app."'";
    }

  
   
    
   $result_out = new stdClass();
      $DataRows = [];
      $resultArray = array(); //data
      $DataRows = ConnectDbAll($_sql);
    
      if($DataRows != false){
      foreach ($DataRows as $row){
        array_push($resultArray,$row);
      }
      $result_out->data =  $resultArray;
      $result_out->status = (true);
      $result_out->sql = $_sql;
    }else{
      $result_out->data =  $resultArray;
      $result_out->status = (false);
      $result_out->sql = $_sql;
    }
    
      $SearchArray = [
    
      ];
    
    $resultArray2 = array();
    array_push($resultArray2,$SearchArray);  
    $response->getBody()->write(json_encode($result_out)); // สงคำตอบกลับ
      return $response; // ส่งคำตอบกลับร้า
    });

    $app->post('/update_value_department', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

      $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
      $dept_name = isset($_REQUEST['dept_name']) ? $_REQUEST['dept_name'] : '';
      $dept_emp_app = isset($_REQUEST['dept_emp_app']) ? $_REQUEST['dept_emp_app'] : '';
      $divison_emp_app = isset($_REQUEST['divison_emp_app']) ? $_REQUEST['divison_emp_app'] : '';
      $shift= isset($_REQUEST['shift']) ? $_REQUEST['shift'] : '';

      $dept_code_old = isset($_REQUEST['dept_code_old']) ? $_REQUEST['dept_code_old'] : '';
      $dept_name_old  = isset($_REQUEST['dept_name_old']) ? $_REQUEST['dept_name_old'] : '';
      $dept_emp_app_old  = isset($_REQUEST['dept_emp_app_old']) ? $_REQUEST['dept_emp_app_old'] : '';
      $divison_emp_app_old  = isset($_REQUEST['divison_emp_app_old']) ? $_REQUEST['divison_emp_app_old'] : '';
      $shift_old  = isset($_REQUEST['shift_old']) ? $_REQUEST['shift_old'] : '';
      
      if($dept_emp_app == "null"){
        $_sql ="UPDATE TA_DEPARTMENT SET DEPT_CODE = '".$dept_code."'
      , DEPT_NAME = '".$dept_name."'
      , DEPT_EMP_APP = ''
      , DIVISON_EMP_APP = '".$divison_emp_app."'
      , SHIFT = '".$shift."'
      WHERE DEPT_CODE = '".$dept_code_old."'
      and DEPT_NAME = '".$dept_name_old."'
      and DEPT_EMP_APP $dept_emp_app_old
      and DIVISON_EMP_APP = '".$divison_emp_app_old."'";
      }else if($divison_emp_app == "null"){
        $_sql ="UPDATE TA_DEPARTMENT SET DEPT_CODE = '".$dept_code."'
      , DEPT_NAME = '".$dept_name."'
      , DEPT_EMP_APP = '".$dept_emp_app."'
      , DIVISON_EMP_APP = ''
      , SHIFT = '".$shift."'
      WHERE DEPT_CODE = '".$dept_code_old."'
      and DEPT_NAME = '".$dept_name_old."'
      and DEPT_EMP_APP = '".$dept_emp_app_old."'
      and DIVISON_EMP_APP $divison_emp_app_old";
      }else{
      $_sql ="UPDATE TA_DEPARTMENT SET DEPT_CODE = '".$dept_code."'
      , DEPT_NAME = '".$dept_name."'
      , DEPT_EMP_APP = '".$dept_emp_app."'
      , DIVISON_EMP_APP = '".$divison_emp_app."'
      , SHIFT = '".$shift."'
      WHERE DEPT_CODE = '".$dept_code_old."'
      and DEPT_NAME = '".$dept_name_old."'
      and DEPT_EMP_APP = '".$dept_emp_app_old."'
      and DIVISON_EMP_APP = '".$divison_emp_app_old."'";
      }
      $_sql2 ="COMMIT";

      
      $result_out = new stdClass();
      $DataRows = ConnectDbnoAll($_sql,$_sql2);
  
      if ($DataRows !== false) {
    
    
        $result_out->status = (true);
        $result_out->sql = ($_sql);
      } else {
    
        $result_out->status = (false);
        $result_out->sql = ($_sql);
      }
    
      $SearchArray = [];
    
      $resultArray2 = array();
      array_push($resultArray2, $SearchArray);
      $response->getBody()->write(json_encode($result_out)); // สงคำตอบกลับ
      });

      $app->post('/check_item_db_department', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url
        $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
        $dept_name = isset($_REQUEST['dept_name']) ? $_REQUEST['dept_name'] : '';

        $_sql = "SELECT * FROM DB_DEPARTMENT_V WHERE DEPT_CODE = '".$dept_code."' and DEPT_NAME = '".$dept_name."'";
        
       $result_out = new stdClass();
          $DataRows = [];
          $resultArray = array(); //data
          $DataRows = ConnectDbAll($_sql);
        
          if($DataRows != false){
          foreach ($DataRows as $row){
            array_push($resultArray,$row);
          }
          $result_out->data =  $resultArray;
          $result_out->status = (true);
          $result_out->sql = $_sql;
        }else{
          $result_out->data =  $resultArray;
          $result_out->status = (false);
          $result_out->sql = $_sql;
        }
        
          $SearchArray = [
        
          ];
        
        $resultArray2 = array();
        array_push($resultArray2,$SearchArray);  
        $response->getBody()->write(json_encode($result_out)); // สงคำตอบกลับ
          return $response; // ส่งคำตอบกลับร้า
        });

  $app->run(); //สั่งระบบให้ทำงาน
  ?>
  