<?php
namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginAction
{
    public function __invoke(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';

        $pdo = new \PDO("mysql:host=localhost;dbname=course_marks_db", "root", "");
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            $response->getBody()->write(json_encode(['status' => 'error', 'message' => 'Invalid credentials']));
            return $response->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

        // Save user info in session (mock RBAC, can replace with JWT later)
        session_start();
        $_SESSION['user'] = [
            'id' => $user['id'],
            'username' => $user['username'],
            'user_type' => $user['user_type']
        ];

        $response->getBody()->write(json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'role' => $user['user_type']
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
