<?php
namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Illuminate\Database\Capsule\Manager as Capsule;

class NotificationController
{
    /**
     * GET /notifications?user_id={id}
     */
    public function index(Request $request, Response $response): Response
    {
        $userId = $request->getQueryParams()['user_id'] ?? null;
        if (!$userId) {
            $response->getBody()->write(json_encode(['error' => 'User ID required']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $notifications = Capsule::table('notifications')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $response->getBody()->write(json_encode($notifications));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }

    /**
     * DELETE /notifications/{id}?user_id={id}
     */
    public function delete(Request $request, Response $response, array $args): Response
    {
        $id = $args['id'] ?? null;
        $userId = $request->getQueryParams()['user_id'] ?? null;

        if (!$userId) {
            $response->getBody()->write(json_encode(['error' => 'User ID required']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        if (!$id) {
            $response->getBody()->write(json_encode(['error' => 'Notification ID required']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $notification = Capsule::table('notifications')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            $response->getBody()->write(json_encode(['error' => 'Notification not found or unauthorized']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        Capsule::table('notifications')
            ->where('id', $id)
            ->delete();

        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
    }
}