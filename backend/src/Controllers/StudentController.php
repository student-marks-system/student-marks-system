<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Illuminate\Database\Capsule\Manager as Capsule;

class StudentController
{
    public function index(Request $request, Response $response): Response
    {
        $students = Capsule::table('users')->where('user_type', 'student')->get();
        $response->getBody()->write(json_encode($students));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function store(Request $request, Response $response): Response
    {
        $data = $request->getParsedBody();
        $id = Capsule::table('users')->insertGetId([
            'username' => $data['username'],
            'email' => $data['email'],
            'password_hash' => $data['password_hash'] ?? 'default123',
            'full_name' => $data['full_name'] ?? '',
            'user_type' => 'student',
            'is_active' => $data['is_active'] ?? true,
        ]);
        $response->getBody()->write(json_encode(['id' => $id]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        Capsule::table('users')
            ->where('id', $args['id'])
            ->where('user_type', 'student')
            ->update([
                'username' => $data['username'],
                'email' => $data['email'],
                'password_hash' => $data['password_hash'] ?? 'default123',
                'full_name' => $data['full_name'] ?? '',
                'is_active' => $data['is_active'] ?? true,
            ]);

        $response->getBody()->write(json_encode(['status' => 'updated']));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function destroy(Request $request, Response $response, array $args): Response
    {
        Capsule::table('users')
            ->where('id', $args['id'])
            ->where('user_type', 'student')
            ->delete();

        $response->getBody()->write(json_encode(['status' => 'deleted']));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
