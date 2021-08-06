<?php

namespace PHPapp\HTMLHelpers;

/**
 * Description of GenerateHTML
 *
 * @author webdev00
 */
class GenerateHTML {
    
    /**
     * @return string Returns the <head> tag content
     */
    public static function getHeadContent(): string {
        return ""
        . "<head>"
            . "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">"
            . "<link rel=\"stylesheet\" href=\"./public/css/main.css\" />"
            . "<script src=\"./public/js/main.js\"></script>"
        . "</head>";
    }
    
    /**
     * @param string $jwtString JWT string to display in the row
     * @param string $id Id of the token -- for delete button
     * @return string Returns html for a single JWT token row
     */
    public static function getTokenRow($jwtString, $id): string {
        return ""
        . "<div class=\"api-token-row\">"
            . "<div class=\"api-token-row__inner\">"
                . "{$jwtString}"
            . "</div>"
            . "<div class=\"api-token-row__tooltip api-token-row__tooltip--hidden\">"
                . "Copy API Token"
            . "</div>"
            . \PHPapp\HTMLHelpers\GenerateHTML::getDeleteTokenButtonHTML($id)
        . "</div>";
    }
    
    /**
     * @return string Gets the html string for the Header Title bar
     */
    public static function getHeaderTitleBar(): string
    {
        return html_entity_decode(""
        . "<h1 class=\"header__titlebar\">"
            . "Lister API"
            . "<span class=\"header__subtitle\">: Create Lists that hold collections of Items</span>"
        . "</h1>"
        . "");
    }
    
    /**
     * @return string Gets html string for the logout button
     */
    public static function getLogoutButtonHTML (): string
    {
        return html_entity_decode(""
                . "<a href=\"/logout\">"
                    . "<button class=\"button-hover logout-button\">Log out</button>"
                . "</a>"
                . "");
    }
    
    /**
     * @return string Gets the html content for creating a login button
     */
    public static function getLoginButtonHTML (): string
    {
        return ""
        . "<a href=\"/login\">"
                . "<button class=\"button-hover login-button\">"
                    . "Log in"
                . "</button>"
        . "</a>";
    }
    
    /**
     * @return string Gets the html content for creating a "generate token" button
     */
    public static function getGenerateTokenButtonHTML (): string
    {
        return ""
        . "<a style=\"margin-left: 0rem;\" href=\"/generate-new-token\">"
        .   "<button class=\"button-hover generate-button\" style=\"font-size: 1rem;\">"
            . "Generate New Token"
        . "</button>"
        . "</a>";
    }
    
    /**
     * @param int $id Takes token id, used to specify which token to delete
     * @return string returns html content for the delete token button
     */
    public static function getDeleteTokenButtonHTML (int $id): string
    {
        return ""
        . "<br>"
        .   "<a href=\"/delete-token/{$id}\">"
        .       "<button class=\"button-hover delete-button\">Delete Token</button>"
        .   "</a>"
        . "<br>";
    }
    
}
