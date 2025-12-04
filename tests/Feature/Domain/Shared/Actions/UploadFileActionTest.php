<?php

namespace Tests\Feature\Domain\Shared\Actions;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use src\Domain\Shared\Actions\UploadFileAction;
use Tests\TestCase;

class UploadFileActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_it_uploads_file_successfully(): void
    {
        $file = UploadedFile::fake()->image('test_image.jpg', 500, 500);

        $action = new UploadFileAction();
        $result = $action->upload($file, false);

        $this->assertNotFalse($result);
        $this->assertStringContainsString('image/', $result);
        $this->assertStringContainsString('.jpg', $result);
    }

    public function test_it_stores_file_in_image_path(): void
    {
        $file = UploadedFile::fake()->image('test_image.jpg', 500, 500);

        $action = new UploadFileAction();
        $result = $action->upload($file, false);

        $this->assertStringStartsWith(UploadFileAction::IMAGE_PATH, $result);
    }

    public function test_it_generates_unique_filename_with_timestamp(): void
    {
        $file = UploadedFile::fake()->image('original_name.jpg', 500, 500);

        $action = new UploadFileAction();
        $result = $action->upload($file, false);

        $this->assertMatchesRegularExpression('/\d+_/', $result);
    }

    public function test_it_preserves_file_extension(): void
    {
        $jpgFile = UploadedFile::fake()->image('test.jpg', 500, 500);
        $pngFile = UploadedFile::fake()->image('test.png', 500, 500);

        $action = new UploadFileAction();

        $jpgResult = $action->upload($jpgFile, false);
        $pngResult = $action->upload($pngFile, false);

        $this->assertStringEndsWith('.jpg', $jpgResult);
        $this->assertStringEndsWith('.png', $pngResult);
    }

    public function test_image_path_constant_is_correct(): void
    {
        $this->assertEquals('image', UploadFileAction::IMAGE_PATH);
    }

    public function test_cropped_image_path_constant_is_correct(): void
    {
        $this->assertEquals('image/cropped', UploadFileAction::CROPPED_IMAGE_PATH);
    }

    public function test_it_converts_filename_to_snake_case(): void
    {
        $file = UploadedFile::fake()->image('Test Image Name.jpg', 500, 500);

        $action = new UploadFileAction();
        $result = $action->upload($file, false);

        $this->assertStringContainsString('test_image_name', $result);
    }

    public function test_resize_parameter_defaults_to_true(): void
    {
        $this->assertTrue(
            (new \ReflectionMethod(UploadFileAction::class, 'upload'))
                ->getParameters()[1]
                ->getDefaultValue()
        );
    }

    public function test_it_returns_string_path(): void
    {
        $file = UploadedFile::fake()->image('test.jpg', 500, 500);

        $action = new UploadFileAction();
        $result = $action->upload($file, false);

        $this->assertIsString($result);
    }
}
