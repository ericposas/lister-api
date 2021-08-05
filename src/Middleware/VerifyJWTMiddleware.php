<?php

namespace PHPapp\Middleware;

use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

# jwt token parsing -- move to middleware __invoke-able class
use Auth0\SDK\Helpers\JWKFetcher;
use Auth0\SDK\Helpers\Tokens\AsymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\SymmetricVerifier;
use Auth0\SDK\Helpers\Tokens\IdTokenVerifier;

$dotenvPath = __DIR__ . "/../..";

\Dotenv\Dotenv::createImmutable($dotenvPath)->load();
if ($_ENV["ENV"] === "local") {
    \Dotenv\Dotenv::createImmutable($dotenvPath, ".env.local")->load();
} else {
    \Dotenv\Dotenv::createImmutable($dotenvPath, ".env.stage")->load();
}

/**
 * Description of VerifyJWTMiddleware
 *
 * @author webdev00
 */
class VerifyJWTMiddleware extends \PHPapp\EntityManagerResource {

    public function __invoke(Request $request, RequestHandler $handler) {
    
        $response = new \Slim\Psr7\Response();

        $api_token = \PHPapp\Helpers\GetAuthorizationTokenFromHeader::getToken($request);

        if (empty($api_token)) {
            $response->getBody()->write("Log in or sign up to get a jwt token by hitting the endpoint /get-token, then you can make API calls");
            return $response;
        }

        # check the token against the token_whitelist table
        # reject if the token is not found
        $em = $this->getEntityManager();
        $repo = $em->getRepository(\PHPapp\Entity\WhitelistedToken::class);
        $whitelisted_token = $repo->findBy([ "jwt" => $api_token ]);
        if (empty($whitelisted_token[0])) {
            $response->getBody()->write((string)json_encode([
                "message" => "You cannot access resources because this token was not found in our whitelist, sorry."
            ]));
            return $response->withAddedHeader("content-type", "application/json");
        }


        $token_issuer = "https://{$_ENV["AUTH0_DOMAIN"]}/";
        $jwks_fetcher = new JWKFetcher();
        $jwks = $jwks_fetcher->getKeys("{$token_issuer}.well-known/jwks.json");
        $signature_verifier = new AsymmetricVerifier($jwks);

        $token_verifier = new IdTokenVerifier(
                $token_issuer,
                $_ENV["AUTH0_CLIENT_ID"],
                $signature_verifier
        );

        try {
            $decoded_token = $token_verifier->verify($api_token, ["nonce", "max_age"]);
            $sub = $decoded_token["sub"];

            # check the "sub" claim in the supplied token
            if ($sub !== $_ENV["AUTH0_SUB"]) {
                $response->getBody()->write((string)[
                    "message" => "sub claim does not match the expected value"
                ]);
                return $response;
            }

            // check jwt, if good, then go ahead and allow api call to go through
            if (isset($decoded_token)) {
                $handled = (string)$handler->handle($request)->getBody();
                $response = new \Slim\Psr7\Response();
                $response->getBody()->write("{$handled}");
                return $response->withAddedHeader("content-type", "application/json");
            } else {
                $response->getBody()->write((string) json_encode([
                    "user" => "user has no token in session, is not logged in"
                ]));
                return $response->withAddedHeader("content-type", "application/json");
            }

        } catch (Exception $ex) {
            $response->getBody()->write("Caught Exception - {$ex->getMessage()}");
            return $response;
        }
    
    }
    
}
