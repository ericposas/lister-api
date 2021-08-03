<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use PHPapp\Entity\APIUser;

class LoginController extends \PHPapp\EntityManagerResource {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
        $auth0 = new Auth0($auth0Config);
        
	$userInfo = $auth0->getUser();

	if (!$userInfo) {
            $auth0->login();
	} else {
            # upon successful login, save or update our APIUser
            $em = $this->getEntityManager();
            $repo = $em->getRepository(\PHPapp\Entity\APIUser::class);
            $tokRepo = $em->getRepository(\PHPapp\Entity\WhitelistedToken::class);
            $existingApiUser = $repo->findBy([ "name" => $userInfo["name"] ]);
            $existingWhitelistedToken = $tokRepo->findBy([
                "jwt" => $auth0->getIdToken()
            ]);
            
            if (!$existingApiUser) {
                echo "No user by the name {$userInfo["name"]} <br><br>";
                echo "Creating user and saving to database...";
                $apiUser = new APIUser();
                $apiUser->setName($userInfo["name"])
                        ->setNickname($userInfo["nickname"])
                        ->setPicture($userInfo["picture"])
                        ->setEmail($userInfo["email"])
                        ->setEmailVerified($userInfo["email_verified"])
                        ->setUpdatedAt($userInfo["updated_at"]);
                $token = new \PHPapp\Entity\WhitelistedToken();
                $token->setJWT($auth0->getIdToken());
                $token->addOwner($apiUser);
                $em->persist($apiUser);
                $em->flush();
                # display new token
                $tokens[] = $auth0->getIdToken();
            } else {
                $existingApiUser = $existingApiUser[0];
                echo "User is already in Lister API's database, updating..."
                . "<br>"
                        . "<br>";
                # api user is already in our database, so update them
                $existingApiUser->setName($userInfo["name"])
                        ->setNickname($userInfo["nickname"])
                        ->setPicture($userInfo["picture"])
                        ->setEmail($userInfo["email"])
                        ->setEmailVerified($userInfo["email_verified"])
                        ->setUpdatedAt($userInfo["updated_at"]);
                if (empty($existingWhitelistedToken)) {
                    $newToken = new \PHPapp\Entity\WhitelistedToken();
                    $newToken->setJWT($auth0->getIdToken());
                    $newToken->addOwner($existingApiUser);
                    $em->flush();
                }
                # check whitelisted_tokens for incoming jwt value
                # if different, set a new token in user's tokens
                # -- display all tokens
                $existingTokens = $existingApiUser->getTokens();
                foreach($existingTokens as $key => $existingToken) {
                    $tokens[] = $existingToken->getJWT();
                }
            }
            //
            
//            foreach($userInfo as $key => $data) {
//                $userData[$key] = $data;
//            }
            
//            $userDataString = (string)json_encode($userData);
            
//            $response->getBody()->write((string) json_encode([
//                "tokens" => $tokens
//            ]));
            
            if ($existingApiUser) {
                $_s = count($existingTokens) > 1 ? "s" : "";
                echo ""
                . "<div>Your API Token{$_s}:</div>";
                foreach ($existingTokens as $existingToken) {
                    echo ""
                    . "<br>"
                    . "<div>{$existingToken->getJWT()}</div>";
                }
            } else {
                echo ""
                . "<div>Your Token:</div>"
                . "<br>"
                . "<div>{$auth0->getIdToken()}</div>";
            }
            
            return $response;
	}

    }
    
}
