# Lister API TODO next

- Add ability to update user info at Auth0 and mirror in our own db 
- Integrate Swagger documentation using: https://github.com/zircote/swagger-php \
	and pointing swagger UI to swagger endpoint: https://github.com/swagger-api/swagger-ui
- Group related endpoints and add the VerifyJWTMiddleware to the group
- Place auth0->getUser() auth checks in ManageTokenController and HomeController into a middleware function 
- Look into Symfony's Twig templates for html/php sections
