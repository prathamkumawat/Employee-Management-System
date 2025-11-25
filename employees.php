<!-- employees.php -->

<?php
session_start();
if (!isset($_SESSION["user_id"])) { header("Location: login.php"); exit; }

require "db_connect.php";

/* ---------------------- ADD / EDIT EMPLOYEE ---------------------- */

$action = $_GET['action'] ?? '';

if ($action === 'add' || $action === 'edit') {

    $id = $_GET['id'] ?? null;
    $employee = null;

    if ($id) {
        // Fetch existing employee
        $stmt = $conn->prepare("SELECT * FROM employees WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $employee = $stmt->get_result()->fetch_assoc();
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {

        $id = $_POST["id"] ?? null;
        $name = $_POST["name"];
        $email = $_POST["email"];
        $department = $_POST["department"];
        $phone = $_POST["phone"];

        // Update employee
        if (!empty($id)) {

            $stmt = $conn->prepare("UPDATE employees 
                                    SET name=?, email=?, department=?, phone=? 
                                    WHERE id=?");
            $stmt->bind_param("ssssi", $name, $email, $department, $phone, $id);
            $stmt->execute();

        } else {
            // Add employee
            $stmt = $conn->prepare("INSERT INTO employees (name,email,department,phone) 
                                    VALUES (?,?,?,?)");
            $stmt->bind_param("ssss", $name, $email, $department, $phone);
            $stmt->execute();
        }

        header("Location: employees.php");
        exit;
    }
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title><?= $employee ? "Edit Employee" : "Add Employee" ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">EMS</div>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="employees.php" class="active">Employees</a>
        <a href="attendance.php">Attendance</a>
        <a href="settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </nav>
</div>

<div class="main">
    <h1><?= $employee ? "Edit Employee" : "Add Employee" ?></h1>

    <form method="post">

        <input type="hidden" name="id" value="<?= $employee['id'] ?? '' ?>">

        <label class="input-label">Name</label>
        <input type="text" name="name" class="input-field"
            value="<?= $employee['name'] ?? '' ?>" required>

        <label class="input-label">Email</label>
        <input type="email" name="email" class="input-field"
            value="<?= $employee['email'] ?? '' ?>" required>

        <label class="input-label">Department</label>
        <input type="text" name="department" class="input-field"
            value="<?= $employee['department'] ?? '' ?>">

        <label class="input-label">Phone</label>
        <input type="text" name="phone" class="input-field"
            value="<?= $employee['phone'] ?? '' ?>">

        <button class="btn auth-btn" type="submit">Save</button>
        <a href="employees.php" class="btn" style="background:#666;margin-left:10px;">Back</a>

    </form>
</div>

</body>
</html>

<?php
exit;
}

/* ---------------------- DELETE EMPLOYEE ---------------------- */

if ($action === 'delete' && !empty($_GET['id'])) {

    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM employees WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: employees.php");
    exit;
}

/* ---------------------- EMPLOYEES LIST PAGE ---------------------- */

$result = $conn->query("SELECT * FROM employees ORDER BY id DESC");
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Employees</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="sidebar">
    <div class="logo">EMS</div>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="employees.php" class="active">Employees</a>
        <a href="attendance.php">Attendance</a>
        <a href="setting.php">Settings</a>
        <a href="logout.php">Logout</a>
    </nav>
</div>

<div class="main">
    <h1>Employees</h1>

    <a class="btn" href="employees.php?action=add">Add Employee</a>

    <table class="list">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Department</th><th>Phone</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td><?= $row['email'] ?></td>
                <td><?= $row['department'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td>
                    <a href="employees.php?action=edit&id=<?= $row['id'] ?>">Edit</a> |
                    <a href="employees.php?action=delete&id=<?= $row['id'] ?>"
                        onclick="return confirm('Delete this employee?')">Delete</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
