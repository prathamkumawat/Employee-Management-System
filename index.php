<!-- index.php -->

<?php
session_start();
if (!isset($_SESSION["user_id"])) {
  header("Location: login.php");
  exit;
}

require "function.php";

$employees = getEmployees();
$today = date("Y-m-d");
$todayAttendance = getTodayAttendance($today);

$statusCount = ["Present" => 0, "On Leave" => 0, "Half Day" => 0];
foreach ($todayAttendance as $r) {
  $statusCount[$r["status"]]++;
}
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>

  <div class="sidebar">
    <div class="logo">EMS</div>
    <nav>
      <a href="index.php" class="active">Dashboard</a>
      <a href="employees.php">Employees</a>
      <a href="attendance.php">Attendance</a>
      <a href="setting.php">Settings</a>
      <a href="logout.php">Logout</a>
    </nav>
  </div>

  <div class="main">
    <header>
      <h1>Welcome, <?= $_SESSION['user_name']; ?></h1>
    </header>

    <section class="overview">
      <div class="card">
        <div class="label">Total Employees</div>
        <div class="value"><?= count($employees) ?></div>
      </div>
      <div class="card">
        <div class="label">Present</div>
        <div class="value"><?= $statusCount["Present"] ?></div>
      </div>
      <div class="card">
        <div class="label">On Leave</div>
        <div class="value"><?= $statusCount["On Leave"] ?></div>
      </div>
      <div class="card">
        <div class="label">Half Day</div>
        <div class="value"><?= $statusCount["Half Day"] ?></div>
      </div>
    </section>

    <a class="btn" href="employees.php?action=add">Add Employee</a>

    <table class="list">
      <thead>
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Dept</th>
          <th>Phone</th>
          <!-- <th>Action</th> -->
        </tr>
      </thead>
      <tbody>
        <?php foreach ($employees as $emp): ?>
          <tr>
            <td><?= $emp["name"] ?></td>
            <td><?= $emp["email"] ?></td>
            <td><?= $emp["department"] ?></td>
            <td><?= $emp["phone"] ?></td>
            <!-- <td>
              <a href="employees.php?action=edit&id=<?= $emp['id'] ?>">Edit</a> |
              <a href="employees.php?action=delete&id=<?= $emp['id'] ?>">Delete</a>
            </td> -->
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>

</body>

</html>