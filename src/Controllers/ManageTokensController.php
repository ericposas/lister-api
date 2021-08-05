<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use PHPapp\Entity\APIUser;

class ManageTokensController extends \PHPapp\EntityManagerResource {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
        $auth0 = new Auth0($auth0Config);
        
	$userInfo = $auth0->getUser();

        echo "<h1>Lister API</h1>";
        
        # upon successful login, save or update our APIUser
        $em = $this->getEntityManager();
        $apiUserRepo = $em->getRepository(APIUser::class);
        $tokRepo = $em->getRepository(\PHPapp\Entity\WhitelistedToken::class);
        
        $existingWhitelistedToken = $tokRepo->findBy([
            "jwt" => $auth0->getIdToken()
        ]);

        $existingApiUser = $apiUserRepo->findBy([
            "name" => $auth0->getUser()["name"]
        ]);
        $existingApiUser = $existingApiUser[0];

        if (empty($existingWhitelistedToken)) {
            $existingWhitelistedToken = $existingWhitelistedToken[0];
            $deletedTokensRepo = $em->getRepository(\PHPapp\Entity\DeletedToken::class);
            $deletedTokenRecords = $deletedTokensRepo->findBy([ "jwt" => $auth0->getIdToken() ]);
            if (empty($deletedTokenRecords) && isset($existingApiUser)) {
                $token = new \PHPapp\Entity\WhitelistedToken();
                $token->setJWT($auth0->getIdToken());
                $token->addOwner($existingApiUser);
            } else {
//                    echo "Looks like this token has already been deleted.<br>";
//                    echo "Please generate a new one.";
//                    return $response;

                echo "<div>You have no tokens</div><br>";
                echo "<a style=\"margin-left: 0rem;\" href=\"/generate-new-token\"><button style=\"font-size: 1rem;\">Generate New Token</button></a>";
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
        echo "<a style=\"margin-left: 0rem;\" href=\"/generate-new-token\"><button style=\"font-size: 1rem;\">Generate New Token</button></a><br><br>";
        echo "<div>Your API Token{$_s}:</div>";
        foreach ($existingTokens as $existingToken) {
            $tokId = $existingToken->getId();
            echo "<br><div>{$existingToken->getJWT()}</div>";
            echo "<br><a href=\"/delete-token/{$existingToken->getId()}\"><button>Delete Token</button></a><br>";
        }

        return $response;

    }
    
}
