<?php
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

test('the application returns a successful response', function () {
    $response = $this->get('/up');

    $response->assertStatus(200);
});

test('the environment is testing', function () {
    expect(App::environment())->toBe('testing');
});

test('all test files follow PSR-4 autoloading', function () {
    $testFiles = File::allFiles(base_path('tests'));

    foreach ($testFiles as $file) {
        $path = $file->getRelativePathname();

        expect($path)->toMatch('/^[\w\/]+\.php$/'); // 
    }
});


test('all app files follow PSR-4 autoloading', function () {
    $testFiles = File::allFiles(base_path('app'));

    foreach ($testFiles as $file) {
        $path = $file->getRelativePathname();

        expect($path)->toMatch('/^[\w\/]+\.php$/'); // 
    }
});


// test('api docs can access',function(){
//     $response = $this->get('/docs/api#/');
//     $response->assertStatus(200);
// });