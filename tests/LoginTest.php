<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->exec("CREATE TABLE ks_user (
            ID INTEGER PRIMARY KEY AUTOINCREMENT,
            Username TEXT,
            Password TEXT,
            FirstName TEXT,
            LastName TEXT,
            Email TEXT,
            roles_id INTEGER
        )");

        $this->pdo->exec("INSERT INTO ks_user (Username, Password, FirstName, LastName, Email, roles_id)
                          VALUES ('testuser', 'testpass', 'John', 'Doe', 'john.doe@example.com', 2)");

        global $conn;
        $conn = $this->pdo;
    }

    public function testLoginSuccess()
    {
        $_POST['Username'] = 'testuser';
        $_POST['Password'] = 'testpass';

        $_SESSION = []; // Start with a clean session

        ob_start(); // Start output buffering
        include 'login.php';
        ob_end_clean(); // Clean the buffer and discard the output

        $this->assertArrayHasKey('ID', $_SESSION, "Session ID is not set.");
        $this->assertEquals('testuser', $_SESSION['Username'], "The username in the session is incorrect.");
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        session_destroy();
        $this->pdo = null;
    }
}
