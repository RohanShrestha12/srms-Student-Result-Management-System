<?php
session_start();
chdir('../../');
require_once('db/config.php');
require_once('db/connection.php');

// Now include session check after connection is established
require_once('const/check_session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

$id = $_POST['id'];

// Check if account_id is set
if (!isset($account_id)) {
echo '<option disabled>Session expired. Please login again.</option>';
exit;
}

try {
$stmt = $conn->prepare("SELECT * FROM tbl_subject_combinations
  LEFT JOIN tbl_subjects ON tbl_subject_combinations.subject = tbl_subjects.id WHERE tbl_subject_combinations.teacher = ?");
$stmt->execute([$account_id]);
$result = $stmt->fetchAll();
?><option selected disabled value="">Select One</option><?php
foreach($result as $rowx)
{
$cls = unserialize($rowx[1]);

if (in_array($id, $cls))
{
?><option value="<?php echo $rowx[0]; ?>"><?php echo $rowx[6]; ?> </option><?php
}
else
{

}

}

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

}
?>
