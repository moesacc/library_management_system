<?php

namespace App\Foundation\Response;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    private static ?array $defaultSuccessData = [
        'success' => true,
    ];

    public static function respondNotFound(
        string|Exception $message,
        ?string $key = 'error'
    ): JsonResponse {
        return self::apiResponse(
            data: [$key => self::morphMessage($message)],
            code: Response::HTTP_NOT_FOUND
        );
    }

    public static function respondWithSuccess(
        array|Arrayable|JsonSerializable|null $contents = null
    ): JsonResponse {
        $contents = self::morphToArray($contents) ?? [];

        $data = $contents === []
            ? self::$defaultSuccessData
            : $contents;

        return self::apiResponse(data: $data);
    }

    public static function setDefaultSuccessResponse(?array $content = null): void
    {
        self::$defaultSuccessData = $content ?? [];
    }

    public static function respondOk(string $message): JsonResponse
    {
        return self::respondWithSuccess(contents: ['success' => $message]);
    }

    public static function respondUnAuthenticated(?string $message = null): JsonResponse
    {
        return self::apiResponse(
            data: ['error' => $message ?? 'Unauthenticated'],
            code: Response::HTTP_UNAUTHORIZED
        );
    }

    public static function respondForbidden(?string $message = null): JsonResponse
    {
        return self::apiResponse(
            data: ['error' => $message ?? 'Forbidden'],
            code: Response::HTTP_FORBIDDEN
        );
    }

    public static function respondError(?string $message = null): JsonResponse
    {
        return self::apiResponse(
            data: ['error' => $message ?? 'Error'],
            code: Response::HTTP_BAD_REQUEST
        );
    }

    public static function respondCreated(
        array|Arrayable|JsonSerializable|null $data = null,
        string|null $message = null
    ): JsonResponse {
        $data ??= [];

        return self::apiResponse(
            data: self::morphToArray($data),
            message: $message,
            code: Response::HTTP_CREATED
        );
    }

    public static function respondFailedValidation(
        string|Exception $message,
        ?string $key = 'message'
    ): JsonResponse {
        return self::apiResponse(
            data: [$key => self::morphMessage($message)],
            code: Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public static function respondTeapot(): JsonResponse
    {
        return self::apiResponse(
            data: ['message' => 'I\'m a teapot'],
            code: Response::HTTP_I_AM_A_TEAPOT
        );
    }

    public static function respondNoContent(
        array|Arrayable|JsonSerializable|null $data = null
    ): JsonResponse {
        $data ??= [];
        $data = self::morphToArray($data);

        return self::apiResponse(
            data: $data,
            code: Response::HTTP_NO_CONTENT
        );
    }

    private static function apiResponse(
        array $data = [],
        ?string $message = null,
        ?array $errors = null,
        int $code = 200
    ): JsonResponse {
        $response = [
            'status'  => $code,
            'success' => $errors ? false : true,
            'message' => $message ?? null,
        ];

        if (!$errors) {
            if (!empty($data)) {
                $response['data'] = $data;
            }
        } else {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    private static function morphToArray(array|Arrayable|JsonSerializable|null $data): ?array
    {
        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        if ($data instanceof JsonSerializable) {
            return $data->jsonSerialize();
        }

        return $data;
    }

    private static function morphMessage(string|Exception $message): string
    {
        return $message instanceof Exception
          ? $message->getMessage()
          : $message;
    }
}
