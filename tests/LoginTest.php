<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        // Mock database connection
        $this->conn = $this->getMockBuilder(mysqli::class)
                           ->disableOriginalConstructor()
                           ->getMock();

        // Prepare a mock statement
        $stmt = $this->getMockBuilder(mysqli_stmt::class)
                     ->disableOriginalConstructor()
                     ->getMock();

        // Define the behavior of the mock statement
        $stmt->method('execute')->willReturn(true);
        $stmt->method('store_result')->willReturn(true);
        $stmt->method('bind_param')->willReturn(true);
        $stmt->method('bind_result')->willReturn(true);
        $stmt->method('num_rows')->willReturn(1); // Assume one user found
        $stmt->method('fetch')->willReturn(true);

        // Bind the values for fetch
        $stmt->method('bind_result')->will(
            $this->returnCallback(function (&$id, &$stored_password, &$first_name, &$last_name, &$email) {
                $id = 1;
                $stored_password = 'password123';
                $first_name = 'John';
                $last_name = 'Doe';
                $email = 'john.doe@example.com';
            })
        );

        // Define the behavior of the mock connection
        $this->conn->method('prepare')->willReturn($stmt);
    }

    public function testSuccessfulLogin()
    {
        // Start output buffering
        ob_start();

        // Simulate POST request
        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST['Username'] = 'testuser';
        $_POST['Password'] = 'password123';

        // Include the login.php file
        include 'login.php';

          // Check if headers are sent
          if (headers_sent($file, $line)) {
            ob_end_clean();
            $this->fail("Headers already sent in $file on line $line");
        }

        // Clean the output buffer
        ob_end_clean();

        // Check session variables
        $this->assertEquals(1, $_SESSION['ID']);
        $this->assertEquals('testuser', $_SESSION['Username']);
        $this->assertEquals('John', $_SESSION['FirstName']);
        $this->assertEquals('Doe', $_SESSION['LastName']);
        $this->assertEquals('john.doe@example.com', $_SESSION['Email']);
    }
}

?>
