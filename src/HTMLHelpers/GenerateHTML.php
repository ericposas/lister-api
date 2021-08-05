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
                . "<style>"
                . "html, body {"
                . "margin: 0;="
                . "}"
                . ".title-bar {"
                . "margin: 0;"
                . "padding: 2rem;"
                . "color: #FFF;"
                . "background-color: #1E88E5;"
                . "}"
                . ".subtitle-content {"
                . "font-size: 1.35rem;"
                . "}"
                . ".username {"
                . "font-weight: bold;"
                . "font-size: 1.25rem;"
                . "}"
                . ".container {"
                . "margin: 1rem;"
                . "}"
                . ".api-token-row {"
                . "margin: 0 0 .5rem 0;"
                . "padding: 1rem;"
                . "border-radius: 3px;"
                . "border: #BDBDBD solid 1px;"
                . "background-color: #E0E0E0;"
                . "word-wrap: break-word;"
                . "}"
                . ".button-hover {"
                . "transition: all .15s;"
                . "}"
                . ".button-hover:hover {"
                . "cursor: pointer;"
                . "transition: all .25s;"
                . "transform: scale(1.05);"
                . "}"
                . ".delete-button {"
                . "color: #fff;"
                . "border: none;"
                . "padding: .5rem;"
                . "font-size: 1rem;"
                . "border-radius: 3px;"
                . "margin: -.5rem 0 0 0;"
                . "background-color: #F44336;"
                . "}"
                . ".generate-button {"
                . "color: #fff;"
                . "border: none;"
                . "padding: .5rem;"
                . "font-size: 1rem;"
                . "border-radius: 3px;"
                . "margin: -.5rem 0 0 0;"
                . "background-color: #1976D2;"
                . "}"
                . ".login-button {"
                . "color: #fff;"
                . "border: none;"
                . "padding: .5rem;"
                . "font-size: 1rem;"
                . "border-radius: 3px;"
                . "margin: .75rem 0 0 0;"
                . "background-color: #1976D2;"
                . "}"
                . ".logout-button {"
                . "border: none;"
                . "color: #fff;"
                . "top: .75rem;"
                . "right: .75rem;"
                . "padding: .5rem;"
                . "font-size: 1rem;"
                . "position: absolute;"
                . "font-size: 1rem;"
                . "border-radius: 3px;"
                . "background-color: #FF5722;"
                . "}"
                . "</style>"
        . "</head>";
    }
    
    public static function getHeaderTitleBar(): string
    {
        return html_entity_decode(""
        . "<h1 class=\"title-bar\">"
            . "Lister API"
            . "<span class=\"subtitle-content\">: Create Lists that hold collections of Items</span>"
        . "</h1>"
        . "");
    }
    
    public static function getLogoutButtonHTML (): string
    {
        return html_entity_decode(""
                . "<a href=\"/logout\">"
                    . "<button class=\"button-hover logout-button\">Log out</button>"
                . "</a>"
                . "");
    }
    
    public static function getLoginButtonHTML (): string
    {
        return ""
        . "<a href=\"/login\">"
                . "<button class=\"button-hover login-button\">"
                    . "Log in"
                . "</button>"
        . "</a>";
    }
    
    public static function getGenerateTokenButtonHTML (): string
    {
        return ""
        . "<a style=\"margin-left: 0rem;\" href=\"/generate-new-token\">"
        .   "<button class=\"button-hover generate-button\" style=\"font-size: 1rem;\">"
            . "Generate New Token"
        . "</button>"
        . "</a>";
    }
    
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
