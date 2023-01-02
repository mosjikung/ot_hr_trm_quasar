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
      oci_commit($conn); 
    }else{
      oci_rollback($conn);
      echo "Connect Data Base error";
    }
  }
  oci_close($conn);
  return $DataRows;
  
}

$app->get('/get_shift', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url


  $_sql = "SELECT * FROM TA_OT";
  
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


  $app->post('/get_user', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
    $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
    $emp_type = isset($_REQUEST['emp_type']) ? $_REQUEST['emp_type'] : '';
    $shift_code = isset($_REQUEST['shift_code']) ? $_REQUEST['shift_code'] : '';
    


  $_sql = "SELECT distinct V.EMP_CODE , V.PREN_CODE  , V.EMP_NAME , V.EMP_LAST_NAME , V.DEPT_CODE , T.DEPT_NAME FROM DB_EMPLOYEE_V V  INNER JOIN TA_DEPARTMENT T ON V.DEPT_CODE = T.DEPT_CODE WHERE EMP_CODE NOT IN (SELECT EMP_CODE FROM TA_REQUEST_OT_D D WHERE  TRUNC(D.START_DATE)  =  TO_DATE('".$start_date."','RRRR/MM/DD') AND START_TIME = '".$start_time."') AND  V.DEPT_CODE = '".$dept_code."' 
  and V.EMP_TYPE = '".$emp_type."' and V.shift_code = '".$shift_code."'";
  
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

  $app->post('/get_user_sec_home', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
    $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
    $emp_type = isset($_REQUEST['emp_type']) ? $_REQUEST['emp_type'] : '';
   
    


  $_sql = "SELECT distinct V.EMP_CODE , V.PREN_CODE  , V.EMP_NAME , V.EMP_LAST_NAME , V.DEPT_CODE , T.DEPT_NAME FROM DB_EMPLOYEE_V V  INNER JOIN TA_DEPARTMENT T ON V.DEPT_CODE = T.DEPT_CODE WHERE EMP_CODE NOT IN (SELECT EMP_CODE FROM TA_REQUEST_OT_D D WHERE  TRUNC(D.START_DATE)  =  TO_DATE('".$start_date."','RRRR/MM/DD') AND START_TIME = '".$start_time."') AND  V.DEPT_CODE = '".$dept_code."' 
  and V.EMP_TYPE = '".$emp_type."'";
  
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


  $app->post('/get_user_approve', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
   

  $_sql = "SELECT * FROM TA_REQUEST_OT_D WHERE REQ_NO = '".$req_no."' order by REQ_NO desc";
  
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

  $app->post('/get_kind_out', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $kind_out = isset($_REQUEST['kind_out']) ? $_REQUEST['kind_out'] : '';
    

  $_sql = "SELECT * FROM TA_OT where OT_NAME = '".$kind_out."'";
  
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

  $app->post('/find_approver', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

  $_sql = "SELECT * FROM TA_DEPARTMENT where DEPT_CODE = '".$dept_code."'";
  
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




  $app->post('/find_duplicate_date', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
    
  $_sql = "SELECT * FROM TA_REQUEST_OT_H where TRUNC(OT_DATE)  =  TO_DATE('".$date."','RRRR/MM/DD')";
  
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

  $app->post('/check_last_data_row', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
    
  $_sql = "select MAX(REQ_NO) AS MAX_REQ_NO from TA_REQUEST_OT_H WHERE TRUNC(OT_DATE)  =  TO_DATE('".$date."','RRRR/MM/DD')";
  
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

  $app->post('/check_before_insert', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $emp_name = isset($_REQUEST['emp_name']) ? $_REQUEST['emp_name'] : '';
    $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
  $_sql = "SELECT * FROM TA_REQUEST_OT_D WHERE EMP_CODE = '".$emp_code."' and EMP_NAME = '".$emp_name."' and START_DATE = TO_DATE('".$date."','RRRR/MM/DD')";
  
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

  $app->post('/insert_ot_data_h', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

      

    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
    $req_date = isset($_REQUEST['req_date']) ? $_REQUEST['req_date'] : '';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $ot_code = isset($_REQUEST['ot_code']) ? $_REQUEST['ot_code'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $dept_app = isset($_REQUEST['dept_app']) ? $_REQUEST['dept_app'] : '';
    $dept_app_emp = isset($_REQUEST['dept_app_emp']) ? $_REQUEST['dept_app_emp'] : '';
    $div_app = isset($_REQUEST['div_dept_app']) ? $_REQUEST['div_dept_app'] : '';
    $div_app_emp = isset($_REQUEST['div_app_emp']) ? $_REQUEST['div_app_emp'] : '';
    $ot_type = isset($_REQUEST['ot_type']) ? $_REQUEST['ot_type'] : '';
    
  $_sql = "INSERT INTO TA_REQUEST_OT_H (REQ_NO , REQ_DATE , REF_DATE , OT_CODE , OT_DATE ,  DEPT_CODE , DEPT_APP , DEPT_APP_EMP , DIVISION_APP , 
  DIVISION_APP_EMP , OT_TYPE , ENTRY_DATE , ENTRY_BY) VALUES ('".$req_no."' , TO_DATE('".$req_date."','RRRR/MM/DD') , TO_DATE('".$req_date."','RRRR/MM/DD') , '".$ot_code."' ,TO_DATE('".$req_date."','RRRR/MM/DD') ,'".$dept_code."' , '".$dept_app."'
  , '".$dept_app_emp."' , '".$div_app."' , '".$div_app_emp."' , '".$ot_type."' , SYSDATE , '".$username."')";
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

  $app->post('/insert_ot_data_d', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
    $emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $emp_name = isset($_REQUEST['emp_name']) ? $_REQUEST['emp_name'] : '';
    $ot_hour = isset($_REQUEST['ot_hour']) ? $_REQUEST['ot_hour'] : '';
    $ot_start = isset($_REQUEST['ot_start']) ? $_REQUEST['ot_start'] : '';
    $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
    $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
    $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
    $end_time = isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : '';
    $entry_by = isset($_REQUEST['entry_by']) ? $_REQUEST['entry_by'] : '';
    
  $_sql = "INSERT INTO TA_REQUEST_OT_D (REQ_NO , EMP_CODE , EMP_NAME , OT_HOUR , OT_START , START_DATE , START_TIME , 
  END_DATE , END_TIME , ENTRY_DATE , ENTRY_BY) VALUES ('".$req_no."' , '".$emp_code."' , '".$emp_name."' , '".$ot_hour."' 
  , '".$ot_start."' ,TO_DATE('".$date."','RRRR/MM/DD') , '".$start_time."' , TO_DATE('".$end_date."','RRRR/MM/DD') , '".$end_time."' , SYSDATE , '".$entry_by."')";
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

  $app->post('/update_ot_data_d', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
    $emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $emp_name = isset($_REQUEST['emp_name']) ? $_REQUEST['emp_name'] : '';
    $ot_hour = isset($_REQUEST['ot_hour']) ? $_REQUEST['ot_hour'] : '';
    $ot_start = isset($_REQUEST['ot_start']) ? $_REQUEST['ot_start'] : '';
    $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
    $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
    $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
    $end_time = isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : '';
    $entry_by = isset($_REQUEST['entry_by']) ? $_REQUEST['entry_by'] : '';
    
  $_sql = "UPDATE TA_REQUEST_OT_D SET REQ_NO = '".$req_no."'
  , EMP_CODE = '".$emp_code."'
  , EMP_NAME = '".$emp_name."'
  , OT_HOUR = '".$ot_hour."'
  , OT_START = '".$ot_start."'
  , START_DATE = TO_DATE('".$date."','RRRR/MM/DD')
  , START_TIME = '".$start_time."'
  , END_DATE = TO_DATE('".$end_date."','RRRR/MM/DD')
  , END_TIME = '".$end_time."'
  , ENTRY_DATE = SYSDATE
  , ENTRY_BY = '".$entry_by."'
  WHERE EMP_CODE = '".$emp_code."' and EMP_NAME = '".$emp_name."' and START_DATE = TO_DATE('".$date."','RRRR/MM/DD')";
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

  $app->post('/update_data_for_dept', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';

    
  $_sql = "UPDATE TA_REQUEST_OT_H SET DEPT_APP = 'Y'
, DEPT_APP_DATE = SYSDATE
  WHERE REQ_NO = '".$req_no."'";
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

  $app->post('/update_data_for_div', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';

    
  $_sql = "UPDATE TA_REQUEST_OT_H SET DIVISION_APP = 'Y'
, DIVISION_APP_DATE = SYSDATE
  WHERE REQ_NO = '".$req_no."'";
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

  $app->post('/update_data_for_hr', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';


    
  $_sql = "UPDATE TA_REQUEST_OT_H SET HR_CONFIRM = 'Y'
, HR_CONFIRM_DATE = SYSDATE , HR_APP_EMP = '".$username."' , STATUS_SAVE = 'Y'
  WHERE REQ_NO = '".$req_no."' AND STATUS_SAVE = 'N'";
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

  $app->post('/check_before_update_data_for_hr', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
    
  $_sql = "SELECT * FROM TA_REQUEST_OT_H WHERE REQ_NO = '".$req_no."' AND STATUS_SAVE = 'Y'";
  
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


  $app->post('/check_n_y_update_data_for_hr', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
    
  $_sql = "SELECT * FROM TA_REQUEST_OT_H WHERE REQ_NO = '".$req_no."' AND HR_CONFIRM = 'Y' AND STATUS_SAVE = 'Y'";
  
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


  

  $app->post('/search_data', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    //$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
  $_sql = "SELECT a.REQ_NO, a.EMP_CODE,a.EMP_NAME, a.OT_HOUR , a.START_TIME ,a.END_TIME,b.OT_DATE, b.DEPT_CODE , b.OT_TYPE , b.DEPT_APP FROM TA_REQUEST_OT_D  a INNER JOIN TA_REQUEST_OT_H b ON a.REQ_NO = b.REQ_NO
  WHERE a.EMP_CODE = '".$emp_code."' and b.DEPT_CODE = '".$dept_code."'";
  
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

  $app->post('/search_data_non_para', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    //$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
    $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
    WHERE H.dept_code = '".$dept_code."' and H.ENTRY_BY = '".$username."'  order by H.REQ_NO desc";
  
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

  $app->post('/approve_data_for_dept', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    //$emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    //$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE H.DEPT_APP_EMP = '".$username."' and H.DEPT_APP = 'N' order by H.REQ_NO desc";
  
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

  $app->post('/approve_data_for_dept_day', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    //$emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    //$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE H.dept_code = '".$dept_code."' and H.DEPT_APP = 'N' AND D.START_TIME ='17:00' order by H.REQ_NO desc";
  
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

  $app->post('/approve_data_for_dept_night', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    //$emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    //$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
    $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
    WHERE H.dept_code = '".$dept_code."' and H.DEPT_APP = 'N' AND D.START_TIME ='05:30' order by H.REQ_NO desc";
  
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

  $app->post('/chose_data_dept', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE H.DEPT_APP_EMP = '".$username."' and H.DEPT_APP = 'N' order by H.REQ_NO desc";
  
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

  $app->post('/chose_data_dept_day', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE H.DEPT_APP_EMP = '".$username."' and H.DEPT_APP = 'N' and D.START_TIME = '17:00' order by H.REQ_NO desc";
  
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


  $app->post('/chose_data_dept_day_null', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE H.DEPT_APP_EMP = '".$username."' and H.DEPT_CODE = '".$dept_code."' and H.DEPT_APP = 'N'  order by H.REQ_NO desc";
  
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

  $app->post('/chose_data_dept_day_null_shift', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE H.DEPT_APP_EMP = '".$username."' and H.DEPT_CODE = '".$dept_code."'  and D.START_TIME = '17:00' and H.DEPT_APP = 'N'  order by H.REQ_NO desc";
  
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

  

  $app->post('/chose_data_dept_night', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE   H.DEPT_APP_EMP = '".$username."' and H.DEPT_CODE = '".$dept_code."' and H.DEPT_APP = 'N'  order by H.REQ_NO desc";
  
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

  $app->post('/chose_data_dept_night_null', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE   H.DEPT_APP_EMP = '".$username."' and H.DEPT_APP = 'N' and D.START_TIME = '05:30' order by H.REQ_NO desc";
  
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

  $app->post('/chose_data_dept_night_null_shift', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE H.DEPT_APP_EMP = '".$username."' and H.DEPT_CODE = '".$dept_code."' and and D.START_TIME = '05:30' and H.DEPT_APP = 'N'  order by H.REQ_NO desc";
  
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

  

  $app->post('/chose_data_div', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select * from TA_REQUEST_OT_H WHERE DIVISION_APP_EMP ='".$username."' order by REQ_NO DESC";
  
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

  $app->post('/chose_data_hr', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name  from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE T.dept_code = '".$dept_code."' and H.DEPT_APP = 'Y' AND H.DIVISION_APP = 'Y' AND H.HR_CONFIRM = 'N'  order by H.REQ_NO desc";
  
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

  $app->post('/approve_data_for_div', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    //$emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    //$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE T.divison_emp_app = '".$username."' and H.DEPT_APP = 'Y' AND H.DIVISION_APP = 'N'  order by H.REQ_NO desc";
  
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

  $app->post('/approve_data_for_hr', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
    //$emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
    //$dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
    //$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

    
  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE  H.DEPT_APP = 'Y' AND H.DIVISION_APP = 'Y' AND HR_CONFIRM = 'N'   order by H.REQ_NO desc";
  
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

  $app->post('/list_for_div_app', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    

  $_sql = "SELECT * FROM TA_DEPARTMENT where DIVISON_EMP_APP = '".$username."' order by DEPT_CODE ASC";
  
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

  $app->post('/list_for_dept_app_day', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    

  $_sql = "SELECT * FROM TA_DEPARTMENT where DEPT_EMP_APP = '".$username."' order by DEPT_CODE ASC ";
  
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

  $app->post('/list_for_dept_app_night', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    

  $_sql = "SELECT * FROM TA_DEPARTMENT where DEPT_EMP_APP = '".$username."' order by DEPT_CODE ASC ";
  
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

  $app->post('/list_for_dept_app', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    

  $_sql = "SELECT * FROM TA_DEPARTMENT where DEPT_EMP_APP = '".$username."' order by DEPT_CODE ASC ";
  
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

  $app->post('/list_for_hr_app', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    

  $_sql = "SELECT DEPT_CODE FROM TA_DEPARTMENT order by DEPT_CODE ASC";
  
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

  $app->post('/main_draw', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
    $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
 


    $_sql = "SELECT * FROM TA_DEPARTMENT WHERE   (DEPT_EMP_APP = '".$username."' 
    or DIVISON_EMP_APP = '".$username."')";
    
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




$app->post('/search_data_confirm', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
  $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';

  $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name , h.ref_doc_no from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
  WHERE  H.DEPT_APP = 'Y' AND H.DIVISION_APP = 'Y' AND HR_CONFIRM = 'Y' AND  
  TRUNC(REQ_DATE) =  TO_DATE('".$date."','RRRR/MM/DD')  order by H.REQ_NO desc";
  
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


  $app->post('/check_user_dup', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

  

    $shift = isset($_REQUEST['value_shift']) ? $_REQUEST['value_shift'] : '';
    $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
    $emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
  
    $_sql = "select * from TA_REQUEST_OT_D where EMP_CODE = '".$emp_code."' and OT_START = '".$shift."' and TRUNC(START_DATE) =  TO_DATE('".$date."','RRRR/MM/DD')";
    
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


    $app->post('/excute_pd', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url
      
      $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';

      $_sql = "BEGIN TRM.AUTO_CONFIRM_HR('".$req_no."'); END;";
    
      
      $DataRows = ConnectDbnoAll($_sql);
      $result_out = new stdClass();
      if ($DataRows !== false) {
    
    
        $result_out->status = (true);
        $result_out->sql = ($_sql);
        $result_out->message = ($req_no);
      } else {
    
        $result_out->status = (false);
        $result_out->sql = ($_sql);
        $result_out->message = ($req_no);
      }
    
      $SearchArray = [];
    
      $resultArray2 = array();
      array_push($resultArray2, $SearchArray);
      $response->getBody()->write(json_encode($result_out));
      });
      $app->post('/value_div_input', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url
        $username = isset($_REQUEST['username']) ? $_REQUEST['username'] : '';
        $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
        
      if($dept_code == "ALL"){
        $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name  from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
      WHERE H.DEPT_APP = 'Y' AND H.DIVISION_APP = 'N' AND H.DIVISION_APP_EMP = '".$username."'  order by H.REQ_NO desc";
      }else{
      $_sql = "select distinct H.req_no,H.req_date,H.ot_code,H.dept_app,H.dept_code,H.ot_type, D.start_time ,T.dept_name  from TA_REQUEST_OT_H H INNER JOIN TA_REQUEST_OT_D D ON  (H.REQ_NO = D.REQ_NO) INNER JOIN TA_DEPARTMENT T ON (H.DEPT_CODE = T.DEPT_CODE)
      WHERE T.dept_code = '".$dept_code."' and H.DEPT_APP = 'Y' AND H.DIVISION_APP = 'N' AND H.DIVISION_APP_EMP = '".$username."'  order by H.REQ_NO desc";
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


$app->post('/del_find_data', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

       

          $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
        
        $_sql = "DELETE FROM TA_REQUEST_OT_H WHERE REQ_NO = '".$req_no."'";
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

        $app->post('/find_del_data', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url
          $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
          $req_date = isset($_REQUEST['req_date']) ? $_REQUEST['req_date'] : '';
          $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
          
      
          $_sql = "select * from TA_REQUEST_OT_H where REQ_NO = '".$req_no."' and TRUNC(REQ_DATE)  =  TO_DATE('".$req_date."','DD/MM/RRRR') and dept_code = '".$dept_code."'";
          
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

   $app->post('/insert_del_data_h', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

    
            $req_no_del = isset($_REQUEST['req_no_del']) ? $_REQUEST['req_no_del'] : '';
            $req_date = isset($_REQUEST['req_date']) ? $_REQUEST['req_date'] : '';
            $ref_no = isset($_REQUEST['ref_no']) ? $_REQUEST['ref_no'] : '';
            $ref_date = isset($_REQUEST['ref_date']) ? $_REQUEST['ref_date'] : '';
            $ot_code = isset($_REQUEST['ot_code']) ? $_REQUEST['ot_code'] : '';
            $ot_date = isset($_REQUEST['ot_date']) ? $_REQUEST['ot_date'] : '';
            $dept_code = isset($_REQUEST['dept_code']) ? $_REQUEST['dept_code'] : '';
            $dept_app = isset($_REQUEST['dept_app']) ? $_REQUEST['dept_app'] : '';
            $dept_app_emp = isset($_REQUEST['dept_app_emp']) ? $_REQUEST['dept_app_emp'] : '';
            $dept_app_date = isset($_REQUEST['dept_app_date']) ? $_REQUEST['dept_app_date'] : '';
            $division_app = isset($_REQUEST['division_app']) ? $_REQUEST['division_app'] : '';
            $division_app_date = isset($_REQUEST['division_app_date']) ? $_REQUEST['division_app_date'] : '';
            $division_app_emp = isset($_REQUEST['division_app_emp']) ? $_REQUEST['division_app_emp'] : '';
            $entry_date = isset($_REQUEST['entry_date']) ? $_REQUEST['entry_date'] : '';
            $entry_by = isset($_REQUEST['entry_by']) ? $_REQUEST['entry_by'] : '';
            $ot_type = isset($_REQUEST['ot_type']) ? $_REQUEST['ot_type'] : '';
            $hr_confirm = isset($_REQUEST['hr_confirm']) ? $_REQUEST['hr_confirm'] : '';
            $hr_confirm_date = isset($_REQUEST['hr_confirm_date']) ? $_REQUEST['hr_confirm_date'] : '';
            $hr_app_emp = isset($_REQUEST['hr_app_emp']) ? $_REQUEST['hr_app_emp'] : '';
            $ref_doc_no = isset($_REQUEST['ref_doc_no']) ? $_REQUEST['ref_doc_no'] : '';
            
          $_sql = "INSERT INTO TA_REQUEST_OT_H_DEL (REQ_NO_DEL , REQ_DATE , REF_NO , REF_DATE , OT_CODE , OT_DATE , DEPT_CODE , DEPT_APP
          , DEPT_APP_EMP , DEPT_APP_DATE , DIVISION_APP , DIVISION_APP_DATE ,  DIVISION_APP_EMP , ENTRY_DATE , ENTRY_BY , OT_TYPE , HR_CONFIRM
          , HR_CONFIRM_DATE , HR_APP_EMP , REF_DOC_NO ) VALUES ('".$req_no_del."' , TO_DATE('".$req_date."','DD/MM/RRRR') , '".$ref_no."'
          , TO_DATE('".$ref_date."','DD/MM/RRRR') , '".$ot_code."' , TO_DATE('".$ot_date."','DD/MM/RRRR') , '".$dept_code."' , '".$dept_app."',
          '".$dept_app_emp."' , TO_DATE('".$dept_app_date."','DD/MM/RRRR') , '".$division_app."' , TO_DATE('".$division_app_date."','DD/MM/RRRR')
          , '".$division_app_emp."' , TO_DATE('".$entry_date."','DD/MM/RRRR') , '".$entry_by."' , '".$ot_type."' , '".$hr_confirm."' ,
          TO_DATE('".$hr_confirm_date."','DD/MM/RRRR') , '".$hr_app_emp."' , '".$ref_doc_no."')";
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

          $app->post('/find_del_data_d', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url
            $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
            
            
        
            $_sql = "select * from TA_REQUEST_OT_D where REQ_NO = '".$req_no."'";
            
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

            $app->post('/insert_del_data_d', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url


              $req_no_del = isset($_REQUEST['req_no_del']) ? $_REQUEST['req_no_del'] : '';
              $emp_code = isset($_REQUEST['emp_code']) ? $_REQUEST['emp_code'] : '';
              $emp_name = isset($_REQUEST['emp_name']) ? $_REQUEST['emp_name'] : '';
              $ot_hour = isset($_REQUEST['ot_hour']) ? $_REQUEST['ot_hour'] : '';
              $ot_start= isset($_REQUEST['ot_start']) ? $_REQUEST['ot_start'] : '';
              $start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : '';
              $start_time = isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : '';
              $end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : '';
              $end_time = isset($_REQUEST['end_time']) ? $_REQUEST['end_time'] : '';
              $entry_date = isset($_REQUEST['entry_date']) ? $_REQUEST['entry_date'] : '';
              $entry_by = isset($_REQUEST['entry_by']) ? $_REQUEST['entry_by'] : '';
         

              
            $_sql = "INSERT INTO TA_REQUEST_OT_D_DEL (REQ_NO_DEL , EMP_CODE , EMP_NAME , OT_HOUR , OT_START , START_DATE , START_TIME
            , END_DATE , END_TIME , ENTRY_DATE , ENTRY_BY) VALUES ('".$req_no_del."' , '".$emp_code."' , '".$emp_name."'
            , '".$ot_hour."' , '".$ot_start."' ,  TO_DATE('".$start_date."','DD/MM/RRRR') , '".$start_time."'  , TO_DATE('".$end_date."','DD/MM/RRRR')
            , '".$end_time."' , TO_DATE('".$entry_date."','DD/MM/RRRR') , '".$entry_by."')";
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

            
        $app->post('/del_find_data_d', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url

       

          $req_no = isset($_REQUEST['req_no']) ? $_REQUEST['req_no'] : '';
        
        $_sql = "DELETE FROM TA_REQUEST_OT_D WHERE REQ_NO = '".$req_no."'";
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


        $app->get('/find_pptx', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url
         

          $file = "create.pptx";
        
          if(!file_exists($file)){
            $result_out = new stdClass();
            $result_out->status = (false);
            $result_out->msg = ("Can't Find Data");
          }else{
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename="'.basename($file).'"');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file));
          readfile($file);
            $result_out = new stdClass();
            $result_out->status = (true);
            $result_out->msg = ("Sucesss");
        }

          $response->getBody()->write(json_encode($result_out)); // สงคำตอบกลับ
        });

        $app->get('/find2_pptx', function (Request $request, Response $response) { // สร้าง route ขึ้นมารองรับการเข้าถึง url
         

          $file = "approve.pptx";
        
          if(!file_exists($file)){
            $result_out = new stdClass();
            $result_out->status = (false);
            $result_out->msg = ("Can't Find Data");
          }else{
          header('Content-Description: File Transfer');
          header('Content-Type: application/octet-stream');
          header('Content-Disposition: attachment; filename="'.basename($file).'"');
          header('Expires: 0');
          header('Cache-Control: must-revalidate');
          header('Pragma: public');
          header('Content-Length: ' . filesize($file));
          readfile($file);
            $result_out = new stdClass();
            $result_out->status = (true);
            $result_out->msg = ("Sucesss");
        }

          $response->getBody()->write(json_encode($result_out)); // สงคำตอบกลับ
        });



        

  $app->run(); //สั่งระบบให้ทำงาน
?>

