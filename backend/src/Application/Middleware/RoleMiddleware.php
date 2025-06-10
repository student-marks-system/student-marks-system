<?php
namespace App\Application\Middleware;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slim\Psr7\Response;

class RoleMiddleware
{
    public function __invoke(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        session_start();

        if (!isset($_SESSION['user'])) {
            $res = new Response();
            $res->getBody()->write(json_encode(['error' => 'Unauthorized']));
            return $res->withHeader('Content-Type', 'application/json')->withStatus(401);
        }

      

        return $handler->handle($request->withAttribute('user', $_SESSION['user']));
    }
}
