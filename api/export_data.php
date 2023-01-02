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

$app->post('/export_data_hr_all', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : '';
    $end = isset($_REQUEST['end']) ? $_REQUEST['end'] : '';
    

    
  $_sql = "select distinct D.emp_code,V.pren_code,D.emp_name,H.dept_code,T.dept_name,H.ot_date,
  D.start_time,D.end_time,T.dept_emp_app,T.divison_emp_app, hr_get_emp_name(T.DEPT_EMP_APP) AS DEPT_APP_NAME, hr_get_emp_name(T.DIVISON_EMP_APP) AS DIVISION_APP_NAME
  from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) 
  INNER JOIN DB_EMPLOYEE_V V ON (D.EMP_CODE = V.EMP_CODE)
  INNER JOIN TA_DEPARTMENT T ON (T.DEPT_CODE = V.DEPT_CODE) 
  WHERE TRUNC(OT_DATE) between TO_DATE('".$start."','RRRR/MM/DD') 
  and TO_DATE('".$end."','RRRR/MM/DD') ORDER BY OT_DATE ASC";
  
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
  