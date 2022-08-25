<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\TestCase;

class UploadTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_upload_framework_logs_are_writeable()
    {
        $this->assertDirectoryIsWritable('public/uploads');
        $this->assertDirectoryIsWritable('storage/framework');
        $this->assertDirectoryIsWritable('storage/logs');
    }
}
