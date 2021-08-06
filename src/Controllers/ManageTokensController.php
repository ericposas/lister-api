<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use PHPapp\Entity\APIUser;

class ManageTokensController extends \PHPapp\EntityManagerResource {
    
    public function __invoke(Request $request, Response $response) {
        
        session_start();
        if (empty($_SESSION["log_count"])) {
            $_SESSION["log_count"] = 1;
        } else {
            $_SESSION["log_count"]++;
        }
        
//        echo $_SESSION["log_count"];
        
        $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
        $auth0 = new Auth0($auth0Config);
        
	$userInfo = $auth0->getUser();
        
        echo \PHPapp\HTMLHelpers\GenerateHTML::getHeadContent();
        echo \PHPapp\HTMLHelpers\GenerateHTML::getHeaderTitleBar();
        echo ""
        . "<div class=\"username--right\">{$userInfo["name"]}</div>"
        . "<br>"
        . "<div class=\"container\">";
        
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
        
        if (empty($existingApiUser) || empty($userInfo)) {
            unset($_SESSION["log_count"]);
            return $response->withRedirect("/");
        } else {
            echo \PHPapp\HTMLHelpers\GenerateHTML::getLogoutButtonHTML();
        }
        
        if ($_SESSION["log_count"] > 1) {
            $queryParams = $request->getQueryParams();
//            echo count($queryParams);
            if (empty($existingWhitelistedToken) && (int)count($queryParams) !== 0) {
                $existingWhitelistedToken = $existingWhitelistedToken[0];
                $deletedTokensRepo = $em->getRepository(\PHPapp\Entity\DeletedToken::class);
                $deletedTokenRecords = $deletedTokensRepo->findBy([ "jwt" => $auth0->getIdToken() ]);
                if (empty($deletedTokenRecords) && isset($existingApiUser)) {
                    $token = new \PHPapp\Entity\WhitelistedToken();
                    $token->setJWT($auth0->getIdToken());
                    $token->addOwner($existingApiUser);
                } else if(empty ($existingApiUser->getTokens()) || count($existingApiUser->getTokens()) == 0) {
                    echo "<div>You have no tokens</div><br>"
                    . \PHPapp\HTMLHelpers\GenerateHTML::getGenerateTokenButtonHTML();
                    return $response;
                }
            }
        } else {
            return $response->withRedirect("/my-tokens");
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
        echo \PHPapp\HTMLHelpers\GenerateHTML::getGenerateTokenButtonHTML()
                . "<br><br>"
                . "<h3>"
                . "Your API Token{$_s}:"
                . "</h3>";
        foreach ($existingTokens as $existingToken) {
            echo \PHPapp\HTMLHelpers\GenerateHTML::getTokenRow($existingToken->getJWT(), $existingToken->getId());
        }
        
        echo "</div>"; // closes .container 

        return $response;

    }
    
}
