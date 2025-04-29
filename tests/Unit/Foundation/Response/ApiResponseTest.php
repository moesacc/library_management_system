<?php

use App\Foundation\Response\ApiResponse;
use Illuminate\Support\Arr;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

uses(\Tests\TestCase::class);

beforeEach(function () {
    ApiResponse::setDefaultSuccessResponse([
        'success' => true,
    ]);
});

describe('ApiResponse', function () {
    
    it('responds with success', function () {
        $response = ApiResponse::respondWithSuccess();
        $data = $response->getOriginalContent();
        
        expect($response->status())->toBe(SymfonyResponse::HTTP_OK)
            ->and($data['success'])->toBeTrue()
            ->and($data['status'])->toBe(SymfonyResponse::HTTP_OK)
            ->and($data['message'])->toBeNull()
            ->and(isset($data['data']))->toBeTrue(); 
    });

    it('responds with OK message', function () {
        $message = 'Operation completed';
        $response = ApiResponse::respondOk($message);
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_OK)
            ->and(Arr::get($data, 'data.success'))->toBe($message);
    });

    it('responds with created', function () {
        $payload = ['id' => 1];
        $response = ApiResponse::respondCreated($payload, 'Resource created');
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_CREATED)
            ->and($data['message'])->toBe('Resource created')
            ->and($data['data'])->toBe($payload);
    });

    it('responds with not found', function () {
        $response = ApiResponse::respondNotFound('Not Found');
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_NOT_FOUND)
            ->and(Arr::get($data, 'data.error'))->toBe('Not Found');
    });

    it('responds with unauthenticated', function () {
        $response = ApiResponse::respondUnAuthenticated();
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_UNAUTHORIZED)
            ->and(Arr::get($data, 'data.error'))->toBe('Unauthenticated');
    });

    it('responds with forbidden', function () {
        $response = ApiResponse::respondForbidden('Access Denied');
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_FORBIDDEN)
            ->and(Arr::get($data, 'data.error'))->toBe('Access Denied');
    });

    it('responds with error', function () {
        $response = ApiResponse::respondError('Something went wrong');
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_BAD_REQUEST)
            ->and(Arr::get($data, 'data.error'))->toBe('Something went wrong');
    });

    it('responds with failed validation', function () {
        $response = ApiResponse::respondFailedValidation('Validation failed', 'validation_error');
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->and(Arr::get($data, 'data.validation_error'))->toBe('Validation failed');
    });

    it('responds with teapot', function () {
        $response = ApiResponse::respondTeapot();
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_I_AM_A_TEAPOT)
            ->and(Arr::get($data, 'data.message'))->toBe("I'm a teapot");
    });

    it('responds with no content', function () {
        $response = ApiResponse::respondNoContent();
        $data = $response->getOriginalContent();

        expect($response->status())->toBe(SymfonyResponse::HTTP_NO_CONTENT)
            ->and($data['status'])->toBe(204)
            ->and($data['success'])->toBeTrue()
            ->and($data['message'])->toBeNull()
            ->and(isset($data['data']))->toBeFalse(); // 'data' key should NOT exist
    });

    it('sets default success response', function () {
        ApiResponse::setDefaultSuccessResponse(['status' => 'ok']);
        
        $response = ApiResponse::respondWithSuccess();
        $data = $response->getOriginalContent();

        expect($data['data'])->toBe(['status' => 'ok']);
    });

});
