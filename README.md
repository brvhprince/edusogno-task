# Edusogno PHP Task

### Prerequisites
This project requires the following prerequisites:
* [PHP 8.0+](https://www.php.net/downloads.php)
* [Composer](https://getcomposer.org/)
* [MySQL](https://www.mysql.com/)
* A Web Server (e.g. Apache or Nginx)
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
