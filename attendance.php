<!-- attendance.php -->

<?php
require 'function.php';
$employees = getEmployees();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $date = $_POST['date'] ?? date('Y-m-d');
  foreach ($_POST['attendance'] as $empId => $status) {
    $note = $_POST['note'][$empId] ?? null;
    markAttendance($empId, $status, $date, $note);
  }
  header('Location: attendance.php?date=' . $date);
  exit;
}
$date = $_GET['date'] ?? date('Y-m-d');
$todayAttendance = getTodayAttendance($date);
$attById = [];
foreach ($todayAttendance as $r)
  $attById[$r['employee_id']] = $r;
?>
<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <title>Attendance</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="sidebar">
    <div class="logo">Employee System</div>
    <nav>
      <a href="index.php">Dashboard</a>
      <a href="employees.php">Employees</a>
      <a href="attendance.php" class="active">Attendance</a>
      <a href="setting.php">Settings</a>
    </nav>
  </div>
  <div class="main">
    <h1>Attendance / Leave</h1>
    <form method="post">
      <div class="form-row">
        <label class="input">Date
          <input type="date" name="date" value="<?= htmlspecialchars($date) ?>">
        </label>
      </div>

      <table class="list">
        <thead>
          <tr>
            <th>Name</th>
            <th>Department</th>
            <th>Status</th>
            <th>Note</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($employees as $e): ?>
            <tr>
              <td><?= htmlspecialchars($e['name']) ?></td>
              <td><?= htmlspecialchars($e['department']) ?></td>
              <td>
                <?php $cur = $attById[$e['id']]['status'] ?? ''; ?>
                <label><input type="radio" name="attendance[<?= $e['id'] ?>]" value="Present" <?= $cur === 'Present' ? 'checked' : '' ?>> Present</label>
                <label><input type="radio" name="attendance[<?= $e['id'] ?>]" value="On Leave" <?= $cur === 'On Leave' ? 'checked' : '' ?>> On Leave</label>
                <label><input type="radio" name="attendance[<?= $e['id'] ?>]" value="Half Day" <?= $cur === 'Half Day' ? 'checked' : '' ?>> Half Day</label>
              </td>
              <td><input type="text" name="note[<?= $e['id'] ?>]"
                  value="<?= htmlspecialchars($attById[$e['id']]['note'] ?? '') ?>"></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="margin-top:12px;">
        <button class="btn" type="submit">Save Attendance</button>
        <a href="index.php" style="margin-left:12px;">Back</a>
      </div>
    </form>
  </div>
  <script src="main.js"></script>
</body>

</html>