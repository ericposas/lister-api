# Lister API TODO next

- Update GET /users/{id} endpoint to include all of a User's associated data
- Change api paths to /api/v1/ at base 
- Learn how to use Slim's, or rather PHP-DI's Container for Dependency Injection
- Move Twig templates and (possibly) EntityManager to the Dependency Container 
- Integrate Swagger documentation using: https://github.com/zircote/swagger-php \
	and pointing swagger UI to swagger endpoint: https://github.com/swagger-api/swagger-ui
- Group related endpoints and add the VerifyJWTMiddleware to the group
- Place auth0->getUser() auth checks in ManageTokenController and HomeController into a middleware function 
- Add ability to update user info at Auth0 and mirror in our own db 


## Notes
- So, in PHP frameworks (esp. in API routing), the Dependency Container is meant to set up dependencies for use within the Controllers
- Should really move business logic out of the controllers, by creating a "Service" layer and placing the logic there -- This is essentially moving the business logic to the Entity ExtendedRespositories class
