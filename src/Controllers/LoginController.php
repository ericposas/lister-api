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
                echo "Creating user and saving to database... <br><br>";
                $apiUser = new APIUser();
                $apiUser->setName($userInfo["name"])
                        ->setNickname($userInfo["nickname"])
                        ->setPicture($userInfo["picture"])
                        ->setEmail($userInfo["email"])
                        ->setEmailVerified($userInfo["email_verified"])
                        ->setUpdatedAt($userInfo["updated_at"]);
                $em->persist($apiUser);
                #$em->flush();
                # display new token
                $tokens[] = $auth0->getIdToken();
                # set existingApiUser to newly created one
                $existingApiUser = $apiUser;
                
            } else {
                $existingApiUser = $existingApiUser[0];
                echo "User is already in Lister API's database, updated existing user. <br><br>";
                # api user is already in our database, so update them
                $existingApiUser->setName($userInfo["name"])
                        ->setNickname($userInfo["nickname"])
                        ->setPicture($userInfo["picture"])
                        ->setEmail($userInfo["email"])
                        ->setEmailVerified($userInfo["email_verified"])
                        ->setUpdatedAt($userInfo["updated_at"]);
            }
            
            if (empty($existingWhitelistedToken)) {
                $existingWhitelistedToken = $existingWhitelistedToken[0];
                $deletedTokensRepo = $em->getRepository(\PHPapp\Entity\DeletedToken::class);
                $deletedTokenRecords = $deletedTokensRepo->findBy([ "jwt" => $auth0->getIdToken() ]);
                if (empty($deletedTokenRecords)) {
                    $token = new \PHPapp\Entity\WhitelistedToken();
                    $token->setJWT($auth0->getIdToken());
                    $token->addOwner($existingApiUser);
                } else {
                    echo "Looks like this token has already been deleted.<br>";
                    echo "Please generate a new one.";
                    return $response;
                }
            }
            
            $em->flush();
            
            # check whitelisted_tokens for incoming jwt value
            # if different, set a new token in user's tokens
            # -- display all tokens
            $existingTokens = $existingApiUser->getTokens();
            foreach($existingTokens as $key => $existingToken) {
                $tokens[] = $existingToken->getJWT();
            }
            
            $_s = count($existingTokens) > 1 ? "s" : "";
            echo ""
            . "<div>Your API Token{$_s}:</div>";
            foreach ($existingTokens as $existingToken) {
                $tokId = $existingToken->getId();
                echo "<br><div>{$existingToken->getJWT()}</div>";
                echo "<br><a href=\"/delete-token/{$existingToken->getId()}\"><button>Delete Token</button></a>";
                echo "<a href=\"\"><button>Generate New Token</button></a>";
            }
            
            return $response;
	}

    }
    
}
