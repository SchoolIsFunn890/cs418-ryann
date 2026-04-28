<?php
    header("X-Frame-Options: DENY");
header("Content-Security-Policy: frame-ancestors 'none';");
$connection = new mysqli(
    "sql202.infinityfree.com",
    "if0_41571960",
    "athEOCnHsx9DWn",
    "if0_41571960_database"
);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

$query = "
    SELECT 
        u.first,
        u.last,
        u.email,
        s.current_term,
        t.status
    FROM users u
    LEFT JOIN students s 
        ON u.email = s.email
    LEFT JOIN terms t 
        ON t.email = u.email
       AND t.term = s.current_term
";

$result = $connection->query($query);

$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = [
        "first"        => $row["first"],
        "last"         => $row["last"],
        "email"        => $row["email"],
        "current_term" => $row["current_term"] ?? null,
        "status"       => $row["status"] ?? null
    ];
}

echo json_encode($users);

$connection->close();
?>
