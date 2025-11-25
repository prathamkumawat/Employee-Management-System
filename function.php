<!-- function.php -->

<?php
require_once "db_connect.php";

/* USERS */
function getUserByEmail($email)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->execute([$email]);
    return $stmt->fetch();
}

/* EMPLOYEES */
function getEmployees()
{
    global $conn;
    return $conn->query("SELECT * FROM employees ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
}

function getEmployee($id)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id=?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function saveEmployee($data)
{
    global $conn;
    if (!empty($data["id"])) {
        $stmt = $conn->prepare(
            "UPDATE employees SET name=?, email=?, department=?, phone=? WHERE id=?"
        );
        return $stmt->execute([
            $data["name"],
            $data["email"],
            $data["department"],
            $data["phone"],
            $data["id"]
        ]);
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO employees (name,email,department,phone) VALUES (?,?,?,?)"
        );
        return $stmt->execute([
            $data["name"],
            $data["email"],
            $data["department"],
            $data["phone"]
        ]);
    }
}

function deleteEmployee($id)
{
    global $conn;
    $stmt = $conn->prepare("DELETE FROM employees WHERE id=?");
    return $stmt->execute([$id]);
}

/* ATTENDANCE */
function markAttendance($empId, $status, $date, $note)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO attendance(employee_id,status,mark_date,note)
         VALUES(?,?,?,?)
         ON DUPLICATE KEY UPDATE status=?, note=?"
    );
    return $stmt->execute([$empId, $status, $date, $note, $status, $note]);
}

function getTodayAttendance($date)
{
    global $conn;
    $stmt = $conn->prepare(
        "SELECT a.*, e.name 
         FROM attendance a 
         JOIN employees e ON a.employee_id=e.id 
         WHERE a.mark_date=?"
    );
    $stmt->execute([$date]);
    $result = $stmt->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
