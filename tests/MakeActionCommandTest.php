<?php

declare(strict_types=1);

namespace Mimisk\LaravelToolbox\Tests;

use Illuminate\Console\Command;

class MakeActionCommandTest extends TestCase
{
    public function test_it_creates_an_action_class(): void
    {
        $target = app_path('Actions/CreateUserAction.php');

        if (file_exists($target)) {
            unlink($target);
        }

        $this->artisan('make:action', ['name' => 'CreateUserAction'])
            ->assertExitCode(Command::SUCCESS);

        $this->assertFileExists($target);

        $contents = (string) file_get_contents($target);

        $this->assertStringContainsString('namespace App\\Actions;', $contents);
        $this->assertStringContainsString('class CreateUserAction', $contents);
        $this->assertStringContainsString('public function handle(): void', $contents);

        unlink($target);

        $directory = app_path('Actions');
        if (is_dir($directory) && count(scandir($directory)) === 2) {
            rmdir($directory);
        }
    }

    public function test_it_creates_nested_action_class(): void
    {
        $target = app_path('Actions/User/CreateUserAction.php');

        if (file_exists($target)) {
            unlink($target);
        }

        $this->artisan('make:action', ['name' => 'User/CreateUserAction'])
            ->assertExitCode(Command::SUCCESS);

        $this->assertFileExists($target);

        $contents = (string) file_get_contents($target);

        $this->assertStringContainsString('namespace App\\Actions\\User;', $contents);
        $this->assertStringContainsString('class CreateUserAction', $contents);

        unlink($target);

        $nestedDirectory = app_path('Actions/User');
        if (is_dir($nestedDirectory) && count(scandir($nestedDirectory)) === 2) {
            rmdir($nestedDirectory);
        }

        $baseDirectory = app_path('Actions');
        if (is_dir($baseDirectory) && count(scandir($baseDirectory)) === 2) {
            rmdir($baseDirectory);
        }
    }

    public function test_it_aborts_when_action_exists(): void
    {
        $target = app_path('Actions/ExistingAction.php');
        $directory = dirname($target);

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($target, "<?php\n");

        $this->artisan('make:action', ['name' => 'ExistingAction'])
            ->assertExitCode(Command::FAILURE);

        $this->assertFileExists($target);

        unlink($target);

        if (is_dir($directory) && count(scandir($directory)) === 2) {
            rmdir($directory);
        }
    }
}
