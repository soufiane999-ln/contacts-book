<h1>Contacts-book Api</h1>

# Tech-stack
- PHP 8.1
- Symfony 6.1.4
- Mysql
- Doctrine

# Set up the project locally
<ol>
<li>Clone the repository(please make sure to have the required version of php)</li>
<li>Edit the database connection variable in .env files </li>
<li>Install the required dependencies of the project, by executing the command 'composer install' </li>
<li>
run the command: composer prepare, that will set up the database & data(fixtures) for the dev and test environments
</li>

<li>
Generate the private and public .files for JWT authentication, them copy them to the folder "config/jwt", the used passphrase is "password"
</li>

</ol>


# Test the project locally
<ol>
<li>
As the database for the test environment was already set in the previous step(by executing composer prepare), 
so basically you will need just to execute the tests, by running: php bin/phpunit
</li>
</ol>

# API
<ol>
<li>
    GET: {server}:{port}/api/doc
</li>
<li>
    POST: {server}:{port}/customers/{idCustomer}/contacts
</li>
<li>
    GET: {server}:{port}/customers/{idCustomer}/contacts/{name}
</li>
<li>
    PUT: {server}:{port}/customers/{idCustomer}/contacts/{idContact}
</li>
<li>
    DELETE: {server}:{port}/customers/{idCustomer}/contacts/{idContact}
</li>
<li>
    GET: {server}:{port}/customers/{idCustomer}/contacts/{idContact}
</li>
</ol>