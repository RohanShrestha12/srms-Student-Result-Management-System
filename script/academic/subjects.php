<?php
chdir('../');
session_start();
require_once('db/config.php');
require_once('const/school.php');
require_once('const/check_session.php');
if ($res == "1" && $level == "1") {}else{header("location:../");}
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<head>
<title>SRMS - Subjects</title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<base href="../">
<link rel="stylesheet" type="text/css" href="css/main.css">
<link rel="icon" href="images/icon.ico">
<link rel="stylesheet" type="text/css" href="cdn.jsdelivr.net/npm/bootstrap-icons%401.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="cdn.datatables.net/v/bs5/dt-1.13.4/datatables.min.css">
<link type="text/css" rel="stylesheet" href="loader/waitMe.css">
</head>
<body class="app sidebar-mini">

<header class="app-header"><a class="app-header__logo" href="javascript:void(0);">SRMS</a>
<a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>

<ul class="app-nav">

<li class="dropdown"><a class="app-nav__item" href="#" data-bs-toggle="dropdown" aria-label="Open Profile Menu"><i class="bi bi-person fs-4"></i></a>
<ul class="dropdown-menu settings-menu dropdown-menu-right">
<li><a class="dropdown-item" href="academic/profile"><i class="bi bi-person me-2 fs-5"></i> Profile</a></li>
<li><a class="dropdown-item" href="logout"><i class="bi bi-box-arrow-right me-2 fs-5"></i> Logout</a></li>
</ul>
</li>
</ul>
</header>

<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">
<div class="app-sidebar__user">
<div>
<p class="app-sidebar__user-name"><?php echo $fname.' '.$lname; ?></p>
<p class="app-sidebar__user-designation">Academic</p>
</div>
</div>
<ul class="app-menu">
<li><a class="app-menu__item" href="academic"><i class="app-menu__icon feather icon-monitor"></i><span class="app-menu__label">Dashboard</span></a></li>
<li><a class="app-menu__item" href="academic/terms"><i class="app-menu__icon feather icon-folder"></i><span class="app-menu__label">Academic Terms</span></a></li>

<li><a class="app-menu__item" href="academic/classes"><i class="app-menu__icon feather icon-home"></i><span class="app-menu__label">Classes</span></a></li>
<li><a class="app-menu__item active" href="academic/subjects"><i class="app-menu__icon feather icon-book"></i><span class="app-menu__label">Subjects</span></a></li>
<li><a class="app-menu__item" href="academic/combinations"><i class="app-menu__icon feather icon-book-open"></i><span class="app-menu__label">Subject Combinations</span></a></li>
<li class="treeview"><a class="app-menu__item" href="javascript:void(0);" data-toggle="treeview"><i class="app-menu__icon feather icon-users"></i><span class="app-menu__label">Students</span><i class="treeview-indicator bi bi-chevron-right"></i></a>
<ul class="treeview-menu">
<li><a class="treeview-item" href="academic/promote_students"><i class="icon bi bi-circle-fill"></i> Promote Students</a></li>
</ul>
</li>

<li class="treeview"><a class="app-menu__item" href="javascript:void(0);" data-toggle="treeview"><i class="app-menu__icon feather icon-file-text"></i><span class="app-menu__label">Examination Results</span><i class="treeview-indicator bi bi-chevron-right"></i></a>
<ul class="treeview-menu">

<li><a class="treeview-item" href="academic/manage_results"><i class="icon bi bi-circle-fill"></i> Manage Results</a></li>
<li><a class="treeview-item" href="academic/individual_results"><i class="icon bi bi-circle-fill"></i> Individual Results</a></li>
</ul>
</li>
<li><a class="app-menu__item" href="academic/report"><i class="app-menu__icon feather icon-bar-chart-2"></i><span class="app-menu__label">Report Tool</span></a></li>
<li><a class="app-menu__item" href="academic/grading-system"><i class="app-menu__icon feather icon-award"></i><span class="app-menu__label">Grading System</span></a></li>
<li><a class="app-menu__item" href="academic/announcement"><i class="app-menu__icon feather icon-bell"></i><span class="app-menu__label">Announcements</span></a></li>
</ul>
</aside>
<main class="app-content">
<div class="app-title">
<div>
<h1>Subjects</h1>
</div>
<ul class="app-breadcrumb breadcrumb">
<li class="breadcrumb-item"><button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#addModal">Add</button></li>
</ul>
</div>

<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="addModalLabel">Add Subject</h5>
</div>
<div class="modal-body">
<form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/new_subject">
<div class="mb-3">
<label class="form-label">Subject Name</label>
<input required name="name" class="form-control" type="text" placeholder="Enter Subject Name">
</div>

<button type="submit" name="submit" value="1" class="btn btn-primary app_btn">Add</button>
<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
</form>
</div>

</div>
</div>
</div>

<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="editModalLabel">Edit Subject</h5>
</div>
<div class="modal-body">
<form class="app_frm" method="POST" autocomplete="OFF" action="academic/core/update_subject">
<div class="mb-3">
<label class="form-label">Subject Name</label>
<input id="name" required name="name" class="form-control" type="text" placeholder="Enter Subject Name">
</div>
<input type="hidden" name="id" id="id">
<button type="submit" name="submit" value="1" class="btn btn-primary app_btn">Save</button>
<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
</form>
</div>

</div>
</div>
</div>

<div class="row">
<div class="col-md-12">
<div class="tile">
<div class="tile-body">
<div class="table-responsive">
<h3 class="tile-title">Subjects</h3>
<table class="table table-hover table-bordered" id="srmsTable">
<thead>
<tr>
<th>Name</th>
<th width="120" align="center"></th>
</tr>
</thead>
<tbody>
<?php

try {
$conn = new PDO('mysql:host='.DBHost.';dbname='.DBName.';charset='.DBCharset.';collation='.DBCollation.';prefix='.DBPrefix.'', DBUser, DBPass);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$stmt = $conn->prepare("SELECT * FROM tbl_subjects");
$stmt->execute();
$result = $stmt->fetchAll();

foreach($result as $row)
{
?>
<textarea style="display:none;" id="subject_<?php echo $row[0]; ?>"><?php echo $row[1]; ?></textarea>
<tr>
<td><?php echo $row[1]; ?></td>
<td align="center">
<a onclick="set_subject('<?php echo $row[0]; ?>');" class="btn btn-primary btn-sm" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal">Edit</a>
<a onclick="del('academic/core/drop_subject?id=<?php echo $row[0]; ?>', 'Delete Subject?');" class="btn btn-danger btn-sm" href="javascript:void(0);">Delete</a>
</td>
</tr>
<?php
}

}catch(PDOException $e)
{
echo "Connection failed: " . $e->getMessage();
}

?>

</tbody>
</table>
</div>
</div>
</div>
</div>
</div>

</main>

<script src="js/jquery-3.7.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
<script src="loader/waitMe.js"></script>
<script src="js/sweetalert2@11.js"></script>
<script src="js/forms.js"></script>
<script type="text/javascript" src="js/plugins/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/plugins/dataTables.bootstrap.min.html"></script>
<script type="text/javascript">$('#srmsTable').DataTable({"sort" : false});</script>
<?php require_once('const/check-reply.php'); ?>
</body>

</html>
