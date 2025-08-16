<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait ApiResponse
{
    /**
     * Success response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function successResponse($data = null, $message = 'تمت العملية بنجاح', $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ], $statusCode);
    }

    /**
     * Error response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @param  mixed  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorResponse($message = 'حدث خطأ ما', $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString()
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation error response.
     *
     * @param  mixed  $errors
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function validationErrorResponse($errors, $message = 'بيانات غير صحيحة'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }

    /**
     * Not found response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function notFoundResponse($message = 'المورد غير موجود'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Unauthorized response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function unauthorizedResponse($message = 'غير مصرح لك بالوصول'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Forbidden response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function forbiddenResponse($message = 'ممنوع الوصول'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }

    /**
     * Server error response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function serverErrorResponse($message = 'خطأ في الخادم'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }

    /**
     * Created response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createdResponse($data = null, $message = 'تم الإنشاء بنجاح'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Updated response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function updatedResponse($data = null, $message = 'تم التحديث بنجاح'): JsonResponse
    {
        return $this->successResponse($data, $message, 200);
    }

    /**
     * Deleted response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function deletedResponse($message = 'تم الحذف بنجاح'): JsonResponse
    {
        return $this->successResponse(null, $message, 200);
    }

    /**
     * Paginated response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function paginatedResponse($data, $message = 'تم جلب البيانات بنجاح'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data->items(),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'has_more_pages' => $data->hasMorePages()
            ],
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, 200);
    }

    /**
     * Collection response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function collectionResponse($data, $message = 'تم جلب البيانات بنجاح'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'count' => is_countable($data) ? count($data) : 0,
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, 200);
    }

    /**
     * Resource response.
     *
     * @param  mixed  $data
     * @param  string  $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function resourceResponse($data, $message = 'تم جلب البيانات بنجاح'): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
            'timestamp' => now()->toISOString()
        ];

        return response()->json($response, 200);
    }

    /**
     * No content response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * Conflict response.
     *
     * @param  string  $message
     * @param  mixed  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    protected function conflictResponse($message = 'تعارض في البيانات', $errors = null): JsonResponse
    {
        return $this->errorResponse($message, 409, $errors);
    }

    /**
     * Too many requests response.
     *
     * @param  string  $message
     * @param  int  $retryAfter
     * @return \Illuminate\Http\JsonResponse
     */
    protected function tooManyRequestsResponse($message = 'كثير من الطلبات', $retryAfter = 60): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'retry_after' => $retryAfter,
            'timestamp' => now()->toISOString()
        ], 429);
    }

    /**
     * Maintenance mode response.
     *
     * @param  string  $message
     * @param  int  $retryAfter
     * @return \Illuminate\Http\JsonResponse
     */
    protected function maintenanceResponse($message = 'النظام في وضع الصيانة', $retryAfter = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'timestamp' => now()->toISOString()
        ];

        if ($retryAfter !== null) {
            $response['retry_after'] = $retryAfter;
        }

        return response()->json($response, 503);
    }
}