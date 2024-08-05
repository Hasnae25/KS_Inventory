<?php

use PHPUnit\Framework\TestCase;

class MinimalTest extends TestCase
{
    public function testSessionStart()
    {
        // Start output buffering
        ob_start();

        // Check if headers are sent before starting the session
        if (headers_sent($file, $line)) {
            ob_end_clean();
            $this->fail("Headers already sent before starting session in $file on line $line");
        }

        // Start the session
        session_start();

        // Clean the output buffer
        ob_end_clean();

        // Check if headers are sent after starting the session
        if (headers_sent($file, $line)) {
            $this->fail("Headers already sent after starting session in $file on line $line");
        }

        // Assert that the session was started
        $this->assertNotEmpty(session_id(), "Session ID should not be empty");
    }
}

?>
