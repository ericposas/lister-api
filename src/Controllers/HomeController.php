<?php

namespace PHPapp\Controllers;

use Auth0\SDK\Auth0;
use Slim\Http\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Description of HomeController
 *
 * @author webdev00
 */
class HomeController extends \PHPapp\EntityManagerResource {
    
    public function __invoke(Request $request, Response $response) {
        
        $auth0Config = \PHPapp\Helpers\AuthConfig::getConfig();
        $auth0 = new Auth0($auth0Config);

        $userInfo = $auth0->getUser();

        echo "<h1>Lister API</h1>";

        if (!$userInfo) {
            $html = "<div>"
                    . "<div>Log in to manage your API tokens</div>"
                    . "<div>"
                    . "<a href=\"/login\"><button style=\"font-size: 1rem; margin: .75rem 0 0 0;\">Dashboard</button></a>"
                    . "</div>"
                  . "</div>";
        } else {
            
            # upon successful login redirect, save or update our APIUser
            $em = $this->getEntityManager();
            $repo = $em->getRepository(\PHPapp\Entity\APIUser::class);
            $existingApiUser = $repo->findBy([ "name" => $userInfo["name"] ]);
            
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
                # set existingApiUser to newly created one
                $existingApiUser = $apiUser;

            } else {
                $existingApiUser = $existingApiUser[0];
                # api user is already in our database, so update them
                $existingApiUser->setName($userInfo["name"])
                        ->setNickname($userInfo["nickname"])
                        ->setPicture($userInfo["picture"])
                        ->setEmail($userInfo["email"])
                        ->setEmailVerified($userInfo["email_verified"])
                        ->setUpdatedAt($userInfo["updated_at"]);
            }
            
            $html = "<div>"
                    . "<div>You are now logged in as {$auth0->getUser()["name"]}<div>"
                    . "<div>"
                            . "<a href=\"/my-tokens\"><button style=\"font-size: 1rem; margin: .75rem 0 0 0\">Go to my tokens</button></a>"
                    . "</div>"
                  . "</div>";
        }

                
        $response->getBody()->write($html);
        return $response;
    }
    
}
