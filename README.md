# Symfony7 eCommerce Project

This eCommerce platform project is developed with Symfony7 for educational purposes.

## Getting Started

These instructions will guide you to get a copy of the project and running on your local machine.

### Features

- Full-featured shopping cart
- Product reviews and ratings
- Top products carousel
- User profile with order history
- Admin product management
- Admin user management
- Admin Order details page
- Mark orders as delivered option
- Checkout process (shipping, payment method, etc)
- Stripe / credit card integration

### Usage

#### Environment Variables

Create a `.env` file at the root and add the following:

APP_ENV=dev
APP_SECRET=xxxxxxxxxxxxxxxxxxxxxx
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/toyscom?serverVersion=5.7"


#### Mailjet API Key

Update the `$api_key` and `$api_key_secret` variables to match your Mailjet API Key in `/src/Classe/Mail.php`.

#### Install Dependencies (backend)

```bash
composer install
symfony console doctrine:migrations:migrate

#### Run

Run the command below and then open http://localhost:8000/ in your browser:

`symfony server:start``



