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
        
        echo \PHPapp\HTMLHelpers\GenerateHTML::getHeadContent();
        echo \PHPapp\HTMLHelpers\GenerateHTML::getHeaderTitleBar();
        echo ""
        . "<div style=\""
                . "color: #fff;"
                . "top: 1.25rem;"
                . "right: 6rem;"
                . "text-align: right;"
                . "position: absolute;"
                . "font-weight: bold;"
                . "\">"
                . "{$userInfo["name"]}"
        . "</div>"
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
        
        if (empty($existingApiUser)) {
            $html = "<div>Please login to manage your tokens</div>"
                  . \PHPapp\HTMLHelpers\GenerateHTML::getLoginButtonHTML();
            $response->getBody()->write(html_entity_decode($html));
            return $response;
        } else {
            echo \PHPapp\HTMLHelpers\GenerateHTML::getLogoutButtonHTML();
        }
        
        if (empty($existingWhitelistedToken)) {
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
            $tokId = $existingToken->getId();
            echo ""
            . "<div class=\"api-token-row\">"
            .       "<div>{$existingToken->getJWT()}</div>"
            . \PHPapp\HTMLHelpers\GenerateHTML::getDeleteTokenButtonHTML($existingToken->getId())
            . "</div>";
        }
        
        echo "</div>"; // closes .container 

        return $response;

    }
    
}
