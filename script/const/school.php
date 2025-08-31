<?php
// Initialize $conn as null to prevent undefined variable errors
$conn = null;

try
{
$conn = new PDO('mysql:host='.DBHost.';port='.DBPort.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT * FROM tbl_school LIMIT 1");
$stmt->execute();
$result = $stmt->fetchAll();
foreach($result as $row)
{
DEFINE('WBName', $row[1]);
DEFINE('WBLogo', $row[2]);
DEFINE('WBResSys', $row[3]);
DEFINE('WBResAvi', $row[4]);
}

}catch(PDOException $e)
{
// Set default values if database connection fails
if (!defined('WBName')) DEFINE('WBName', 'SRMS');
if (!defined('WBLogo')) DEFINE('WBLogo', 'default_logo.png');
if (!defined('WBResSys')) DEFINE('WBResSys', '1');
if (!defined('WBResAvi')) DEFINE('WBResAvi', '1');
}
?>
