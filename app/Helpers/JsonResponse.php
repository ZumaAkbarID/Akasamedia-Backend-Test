<?php

namespace App\Helpers;

class JsonResponse
{
  public static function response(string $status = 'success', string $message = 'Success', int $code = 200, array $data = [], $pagination = null)
  {
    $response = [
      'status' => $status,
      'message' => $message,
    ];

    if (!empty($data)) {
      $response['data'] = $data;
    }

    if (!is_null($pagination)) {
      $response['pagination'] = [
        [
          'previous_page_url' => $pagination->previousPageUrl(),
          'next_page_url' => $pagination->nextPageUrl(),
          'current_page' => $pagination->currentPage(),
          'last_page' => $pagination->lastPage(),
          'per_page' => $pagination->perPage(),
          'total' => $pagination->total()
        ]
      ];
    }

    return response()->json($response, $code);
  }
}
