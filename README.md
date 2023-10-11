# Edusogno PHP Task

### Prerequisites
This project requires the following prerequisites:
* [PHP 8.0+](https://www.php.net/downloads.php)
* [Composer](https://getcomposer.org/)
* [MySQL](https://www.mysql.com/)
* Apache Web Server
* PHPMyAdmin (optional) (https://www.phpmyadmin.net/)

### Installation

1. Clone the repository
    ```bash
    git clone https://github.com/brvhprince/edusogno-task.git
   ```

2. Change to the root directory of the repository
    ```bash
    cd edusogno-task
   ```

3. Run the following command to install the dependencies
    ```bash
    composer install
   ```

4. Create a database and user for the application. Rename the `.env.example` to `.env` in the root folder file and set the following variables:

    ```dotenv
    DB_HOST=localhost # MySQL host
    DB_NAME=edusogno # MySQL database name
    DB_USER=edusogno # MySQL user
    DB_PASSWORD=edusogno  # MySQL password
    ```
5. Import the database schemas located in `migrations/` into your database.

    ```bash
    mysql -u root -p < migrations/db.sql
   ```
6. Run the following sql command to update the `base_url` in the database:

    ```sql
    UPDATE ed_config SET value = 'http://base_url' WHERE name = 'site_url';
    ```
   Replace `base_url` with the base url of your website.

Access the admin panel by visiting https://base_url/admin-cp

Use the following credentials to login:

```
email address: wan@wan.com
password: 12345
```

You can access the client panel by visiting https://base_url

Create a new account to explore the client panel.
