<?php 
require_once  '../includes/db.php';
ob_start();
$id = $_POST['id'];
$table = $_POST['tablename'];
$sql="DELETE FROM $table WHERE id='$id'";
$result =mysqli_query($conn,$sql);
if($result){
    echo json_encode(['success' => true, 'message' => 'Deleted successfully']);
}else{
    echo json_encode(['success' => false, 'message' => 'Something went wrong']);
}
 ?>