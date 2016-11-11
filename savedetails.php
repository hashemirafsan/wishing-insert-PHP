<?php
/**
 * Save details API
 **/

 error_reporting(E_ALL&~E_NOTICE);
 ob_start();

class SaveDetails{

  public function __construct(){
    $actionType = $_POST['actionType'];
    switch ($actionType){
      case "savedetails": self::saveDetails(); break;
      default : self::invalidAccess();
    }
  }

  protected function invalidAccess(){
      self::showError("1", "Invalid Access");
  }

  protected function showError($code, $message=''){
    $outputArray['message'] = $message;
    $outputArray['code'] = $code;
    $outputArray['data'] = '';
    echo json_encode($outputArray);
  }

  protected function showSuccess($data = ''){
    $outputArray['message'] = "success";
    $outputArray['code'] = "0";
    $outputArray['data'] = $data;
    echo json_encode($outputArray);
  }

  protected function saveDetails(){
      include 'include/db.php';
      $user_id = $_POST['user_id'];
      $place_id = $_POST['place_id'];
      $address = $_POST['address'];
      $rating = $_POST['rating'];
      $phone = $_POST['phone'];
      $name = $_POST['User_name'];
      $latitude = $_POST['latitude'];
      $longitude = $_POST['longitude'];
      $grade = $_POST['grade'];
      $cuisine = $_POST['cuisine'];
      $reviews_count = $_POST['reviews_count'];
      $data = array();
      $table = array();
      $newTableID = array();
      $newData = array();
      $newColumn = array();
      $newValues = array();

      /*Full table column name will be in $table variable */
      $table = ["name","grade","phone","cuisine","latitude","longitude","total_rating","count","place_id","user_id","address"];
      /*POST METHOD data's array is $data*/
      $data = [$name,$grade,$phone,$cuisine,$latitude,$longitude,$rating,$reviews_count,$place_id,$user_id,$address];

      for($i = 0 ; $i < count($data) ; $i++){
        /**
        * Checking which variable not empty and create a new array list which are contain data
        **/
        if(!empty($data[$i])){
          array_push($newTableID,$i);
        }
      }

      for ($j = 0 ; $j < count($newTableID) ; $j++){
        $getData =  $newTableID[$j];           
        $newDataFromGetData = $data[$getData];
        $newColumnFromGetCol = $table[$getData];
        $newValuesFromGetCol = ":".$table[$getData];
        array_push($newData,$newDataFromGetData);
        array_push($newColumn,$newColumnFromGetCol);
        array_push($newValues,$newValuesFromGetCol);
      }

      $newDataAfterInput = implode(",",$newData);
      $newColumnAfterInput = implode(",",$newColumn);
      $newValuesAfterInput = implode(",",$newValues);

      try {
        $stmt2 = $db->prepare("INSERT INTO restaurants ($newColumnAfterInput) VALUES ($newValuesAfterInput)");
        for($k=0 ; $k < count($newData) ; $k++){
          if($newColumn[$k] == $table[4] || $newColumn[$k] == $table[5] || $newColumn[$k] == $table[6] || $newColumn[$k] == $table[7]){
            $stmt2->bindParam($newValues[$k],$newData[$k],PDO::PARAM_INT);
          } else{
            $stmt2->bindParam($newValues[$k],$newData[$k],PDO::PARAM_STR);
          }
        }
        if($stmt2->execute()){
          $outputArray = array();
          $outputArray['success'] = "successfully done the entry";
          showSuccess($outputArray);
        } else {
          $outputArray = array();
          $code = "100";
          $outputArray['success'] = "something problem please try again!!";
          showError($code,$outputArray);
        }
      } catch(Exception $e){
        showError("100", $e->getMessage());
        $db = null;
        exit();
    }
  }
}

$work = new SaveDetails();

?>
