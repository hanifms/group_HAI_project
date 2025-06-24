<?php

namespace Tests\Unit\Helpers;

use App\Helpers\SecurityHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SecurityHelperTest extends TestCase
{
    /**
     * Test that sanitizeInput properly removes HTML tags and converts special characters.
     */
    public function test_sanitize_input_removes_html_tags_and_converts_special_chars(): void
    {
        // Input with HTML tags and special characters
        $input = '<script>alert("XSS Attack");</script> & <b>Bold text</b>';

        // Call sanitizeInput method
        $sanitized = SecurityHelper::sanitizeInput($input);

        // Assert that HTML tags are removed and special characters are converted
        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringNotContainsString('</script>', $sanitized);
        $this->assertStringNotContainsString('<b>', $sanitized);
        $this->assertStringContainsString('&amp;', $sanitized);
    }

    /**
     * Test that sanitizeInput returns the original input when not a string.
     */
    public function test_sanitize_input_returns_original_when_not_string(): void
    {
        // Non-string input
        $input = ['test' => 'value'];

        // Call sanitizeInput method
        $sanitized = SecurityHelper::sanitizeInput($input);

        // Assert that the original input is returned
        $this->assertSame($input, $sanitized);
    }

    /**
     * Test that isValidEmail correctly validates email addresses.
     */
    public function test_is_valid_email_correctly_validates_email_addresses(): void
    {
        // Valid email addresses
        $this->assertTrue(SecurityHelper::isValidEmail('user@example.com'));
        $this->assertTrue(SecurityHelper::isValidEmail('user.name@example.co.uk'));

        // Invalid email addresses
        $this->assertFalse(SecurityHelper::isValidEmail('invalid-email'));
        $this->assertFalse(SecurityHelper::isValidEmail('user@'));
        $this->assertFalse(SecurityHelper::isValidEmail('@example.com'));
        $this->assertFalse(SecurityHelper::isValidEmail('user@.com'));
    }

    /**
     * Test that generateToken creates a string of correct length.
     */
    public function test_generate_token_creates_string_of_correct_length(): void
    {
        // Generate token with default length
        $token = SecurityHelper::generateToken();

        // Assert that the token is a string and has correct length (32 bytes = 64 hex characters)
        $this->assertIsString($token);
        $this->assertEquals(64, strlen($token));

        // Generate token with custom length
        $customToken = SecurityHelper::generateToken(16);

        // Assert that the custom token has correct length (16 bytes = 32 hex characters)
        $this->assertEquals(32, strlen($customToken));
    }

    /**
     * Test that validatePassword correctly identifies valid passwords.
     */
    public function test_validate_password_accepts_valid_passwords(): void
    {
        // Valid password with all required elements
        $validPassword = 'StrongP@ss123';

        // Validate password
        $result = SecurityHelper::validatePassword($validPassword);

        // Assert that the password is valid and there are no errors
        $this->assertTrue($result['valid']);
        $this->assertEmpty($result['errors']);
    }

    /**
     * Test that validatePassword correctly identifies invalid passwords.
     */
    public function test_validate_password_rejects_invalid_passwords(): void
    {
        // Test cases with various invalid passwords
        $testCases = [
            'short' => 'Abc1@', // Too short
            'no_uppercase' => 'password123@', // Missing uppercase
            'no_lowercase' => 'PASSWORD123@', // Missing lowercase
            'no_number' => 'Password@', // Missing number
            'no_special' => 'Password123' // Missing special character
        ];

        foreach ($testCases as $case => $password) {
            // Validate password
            $result = SecurityHelper::validatePassword($password);

            // Assert that the password is invalid and there are errors
            $this->assertFalse($result['valid'], "Failed for case: $case");
            $this->assertNotEmpty($result['errors'], "No errors for case: $case");
        }
    }

    /**
     * Test that validateFileUpload correctly validates file uploads.
     * Skipped due to GD extension requirements.
     */
    public function test_validate_file_upload_accepts_valid_files(): void
    {
        $this->markTestSkipped('Skipped due to missing GD extension in test environment.');

        // Create a mock UploadedFile
        $mockFile = $this->createMock(UploadedFile::class);
        $mockFile->method('isValid')->willReturn(true);
        $mockFile->method('getClientOriginalExtension')->willReturn('jpg');
        $mockFile->method('getSize')->willReturn(500 * 1024); // 500 KB

        // Allowed extensions and max size
        $allowedExtensions = ['jpg', 'jpeg', 'png'];
        $maxSize = 1024 * 1024; // 1MB

        // Validate file
        $result = SecurityHelper::validateFileUpload($mockFile, $allowedExtensions, $maxSize);

        // Assert that the file is valid
        $this->assertTrue($result['valid']);
        $this->assertEquals('File is valid', $result['message']);
    }

    /**
     * Test that validateFileUpload rejects files with invalid extensions.
     */
    public function test_validate_file_upload_rejects_invalid_extensions(): void
    {
        // Create a mock UploadedFile
        $mockFile = $this->createMock(UploadedFile::class);
        $mockFile->method('isValid')->willReturn(true);
        $mockFile->method('getClientOriginalExtension')->willReturn('pdf');

        // Allowed extensions (not including PDF)
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        // Validate file
        $result = SecurityHelper::validateFileUpload($mockFile, $allowedExtensions);

        // Assert that the file is rejected due to invalid extension
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('File type not allowed', $result['message']);
    }

    /**
     * Test that validateFileUpload rejects files that exceed the maximum size.
     */
    public function test_validate_file_upload_rejects_oversized_files(): void
    {
        // Create a mock UploadedFile
        $mockFile = $this->createMock(UploadedFile::class);
        $mockFile->method('isValid')->willReturn(true);
        $mockFile->method('getClientOriginalExtension')->willReturn('jpg');
        $mockFile->method('getSize')->willReturn(3 * 1024 * 1024); // 3 MB

        // Allowed extensions
        $allowedExtensions = ['jpg', 'jpeg', 'png'];

        // Max size (1MB)
        $maxSize = 1024 * 1024;

        // Validate file
        $result = SecurityHelper::validateFileUpload($mockFile, $allowedExtensions, $maxSize);

        // Assert that the file is rejected due to size
        $this->assertFalse($result['valid']);
        $this->assertStringContainsString('File size exceeds', $result['message']);
    }

    /**
     * Test that logSecurityEvent properly logs security events.
     */
    public function test_log_security_event_logs_properly(): void
    {
        // Mock the logger
        Log::shouldReceive('channel')
            ->once()
            ->with('security')
            ->andReturnSelf()
            ->shouldReceive('info')
            ->once()
            ->with(\Mockery::on(function ($json) {
                $data = json_decode($json, true);
                return isset($data['timestamp']) &&
                       isset($data['ip']) &&
                       isset($data['user_agent']) &&
                       $data['type'] === 'login_attempt' &&
                       $data['username'] === 'testuser';
            }));

        // Log a security event
        SecurityHelper::logSecurityEvent('login_attempt', ['username' => 'testuser']);

        // No assertion needed since we're using Mockery expectations
    }
}
