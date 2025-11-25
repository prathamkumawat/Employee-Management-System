<!-- settings.php -->

<?php
require 'db_connect.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $company_name = $_POST['company_name'];
  $start = $_POST['work_start'];
  $end = $_POST['work_end'];
  $stmt = $conn->prepare('UPDATE settings SET company_name=?, work_start=?, work_end=? WHERE id=1');
  $stmt->execute([$company_name, $start, $end]);
  header('Location: setting.php?saved=1');
  exit;
}
$result = $conn->query('SELECT * FROM settings WHERE id=1');
$settings = $result ? $result->fetch_assoc() : false;

if (!$settings) {
  $settings = [
    'company_name' => '',
    'work_start' => '',
    'work_end' => ''
  ];
}
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Settings</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="sidebar">
    <div class="logo">Employee System</div>
    <nav>
      <a href="index.php">Dashboard</a>
      <a href="employees.php">Employees</a>
      <a href="attendance.php">Attendance</a>
      <a href="setting.php" class="active">Settings</a>
    </nav>
  </div>
  <div class="main">
    <h1>Company Settings</h1>
    <form method="post">
      <div class="form-row">
        <label class="input">Company Name
          <input type="text" name="company_name" value="<?= htmlspecialchars($settings['company_name']) ?>">
        </label>
      </div>
      <div class="form-row">
        <label class="input">Work Start
          <input type="time" name="work_start" value="<?= htmlspecialchars($settings['work_start']) ?>">
        </label>
      </div>
      <div class="form-row">
        <label class="input">Work End
          <input type="time" name="work_end" value="<?= htmlspecialchars($settings['work_end']) ?>">
        </label>
      </div>
      <button class="btn" type="submit">Save</button>
      <a href="index.php" style="margin-left:12px;">Back</a>
    </form>
  </div>
</body>

</html>