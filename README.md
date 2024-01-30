# MyKitchen-PHP
This is the php API which the frontend of MyKitchen will talk to.

## Responses
### Status Codes
status | meaning | extras
--- | --- | ---
1 | success | 
0 | failure | error: tells you what went wrong, missing: tells you missing variable data

# Endpoints
## Users
### POST users/create
variable | datatype | desc
--- | --- | ---
user_name | string (50) | The unique username which will define a user: indy4ksu
user_pass | string (32, md5) | The md5'd password of a user. md5 this BEFORE sending
user_email | string (255) | The email address of a user: indy4ksu@gmail.com

### GET users/select
variable | datatype | desc
--- | --- | ---
id ? | int | The id of the user you want to get information on
username ? | int | The username of the user you want to get information on
email ? | int | The email of the user you want to get information on
Returns
variable | desc
user_id | the id of the user
user_name | the username of the user, not formal
user_pass | the md5 password of the user
user_banner_url | the url to a banner image for the user
user_email | the email of the user
user_created_date | the date and time the user was created

## Kitchens
### POST kitchens/create
variable | datatype | desc
--- | --- | ---
kitchen_owner_id | int | The id of the user who will own this kitchen
kitchen_name | string (100) | The title of the kitchen
kitchen_working_hours | json (100) | json object of the working hours for the kitchen
kitchen_uses_cash | string (yes/no) | Simple yes or no for if the kitchen uses cash
kitchen_uses_card | string (yes/no) | Simple yes or no for if the kitchen uses card

### GET kitchens/select
variable | datatype | desc
--- | --- | ---
kitchen_id | int | The id of the kitchen you want to get information on
Returns
variable | desc
kitchen_id | id of the kitchen
kitchen_owner | id of the user who owns the kitchen
kitchen_name | Title of the kitchen
kitchen_working_hours | json of the available hours someone can order from the kitchen
kitchen_is_active | boolean for whether the kitchen will be open/closed regardless of working hours
kitchen_uses_cash | boolean for whether the kitchen takes cash or not
kitchen_uses_card | boolean for whether the kitchen takes card or not
kitchen_created_date | the date and time the kitchen was created

### POST kitchens/update
variable | datatype | desc
--- | --- | ---
kitchen_id | int | The id of the kitchen you want to modify
kitchen_name | string (100) | The title of the kitchen
kitchen_working_hours | json (100) | json object of the working hours for the kitchen
kitchen_is_active | string (yes/no) | Simple yes or no for if the kitchen is taking orders or deactivated
kitchen_uses_cash | string (yes/no) | Simple yes or no for if the kitchen uses cash
kitchen_uses_card | string (yes/no) | Simple yes or no for if the kitchen uses card

### POST kitchens/delete
variable | datatype | desc
--- | --- | ---
kitchen_id | int | The id of the kitchen you want to delete

`kitchen_working_hours` json
```json
{
  "activeHours": {
    "Monday": {
      "start": "09:00 AM",
      "end": "05:00 PM"
    },
    "Tuesday": {
      "start": "09:00 AM",
      "end": "05:00 PM"
    },
    "Wednesday": {
      "start": "09:00 AM",
      "end": "05:00 PM"
    },
    "Thursday": {
      "start": "09:00 AM",
      "end": "05:00 PM"
    },
    "Friday": {
      "start": "09:00 AM",
      "end": "05:00 PM"
    },
    "Saturday": {
      "start": null,
      "end": null,
      "closed": true
    },
    "Sunday": {
      "start": null,
      "end": null,
      "closed": true
    }
  }
}
```

## Orders
### POST orders/create
variable | datatype | desc
--- | --- | ---
order_kitchen_id | int | The id of the kitchen the order was purchased from
order_user_id | int | The id of the user who purchased the order
order_products | json (1000) | json object of the products in the kitchen with product_id and product_price
order_total | float | Decimal value for the price of the order. total >= 0
order_status | string (sent / payment_waiting / in_progress / cooked / done) | One of the options specified which describes the order status

### GET orders/select
variable | datatype | desc
--- | --- | ---
id ? | int | The id of the order you want to get information on
kitchen ? | int | The id of the kitchen you want to get the orders of
user ? | int | The id of the user you want to get the orders of
Returns
variable | desc
order_id | id of the order returned
order_kitchen_id | id of the kitchen the order belongs to
order_user_id | id of the user the order belongs to
order_products | json of the products in the order
order_total | the total value of the order
order_status | the status of the order
order_created_date | the date and time the order was created

### POST orders/update
variable | datatype | desc
--- | --- | ---
order_id | int | The id of the order you want to update
order_products | json (1000) | json object of the products in the kitchen with product_id and product_price
order_total | float | Decimal value for the price of the order. total >= 0
order_status | string (sent / payment_waiting / in_progress / cooked / done) | One of the options specified which describes the order status

### POST orders/delete
variable | datatype | desc
--- | --- | ---
order_id | int | The id of the order you want to delete

`order_products` json
```json
{
    "products": [
        {
            "product_id": "145",
            "product_price": 29.99
        },
        {
            "product_id": "381",
            "product_price": 19.95
        },
        {
            "product_id": "93",
            "product_price": 39.99
        }
    ]
}
```

## Products
### POST products/create
variable | datatype | desc
--- | --- | ---
product_kitchen_id | int | The id of the kitchen who owns the product
product_title | string (255) | Title of the product
product_desc ? | string (1000) | Description of the product
product_price | float | Price of the product, number only
product_category ? | string (100) | The category the product belongs in
product_tags ? | string (500, csv) | The tags associated with the product, comma separated a-Z0-9
product_image_url ? | string (255) | The url to an image for the product (unsplash)

### GET products/select
variable | datatype | desc
--- | --- | ---
id | int | The id of the product you want to get information on
kitchen_id | int | The id of the kitchen you want the products of
Returns
variable | desc
product_id | the id of the product
product_kitchen_id | the id of the kitchen who owns the product
product_title | the title of the product
product_desc | the description of the product
product_price | the decimal price of the product , no $
product_category | the category of the product
product_tags | comma separated tags for the product
product_image_url | a url to the image for the product
product_created_date | the date and time the product was created

## Addresses
### POST addresses/create
variable | datatype | desc
--- | --- | ---
address_owner | int | The id of the user or kitchen
address_type | string (user / kitchen) | Tells if the address is for a user or kitchen
address_line1 | string (100) | Address line 1
address_line2 ? | string (100) | Address line 2
address_city | string (50) | Address city
address_state | string (30) | Can be 2 letter code (GA) or full state name (Georgia)
address_zip | string (15) | Address zip code (30062)
address_phone | string (30) | Only if user: Phone number of the user

### GET addresses/select
variable | datatype | desc
--- | --- | ---
owner_id | int | The id of the user/kitchen you want to get the addresses of
address_type | string (user / kitchen) | the type of owner, is it a kitchen or a user?
Returns
variable | desc
address_id | the id of the address
address_owner | the id of the owner of the address
address_type | the user/kitchen type of the owner
address_line1 | the standard line 1 of an address
address_line2 | the standard line 2 of an address
address_city | the standard city of an address
address_state | the standard state of an address
address_zip | the standard zip code of an address
address_phone | only if user: the phone number of the user
address_created_date | the date and time the address was created

## Delivery Methods
### POST delivery_methods/create
variable | datatype | desc
--- | --- | ---
kdm_owner | int | The id of the kitchen who owns the delivery method
kdm_type | string (local_pickup / delivery) | The options for the delivery type. Local pick-up means it is not a delivery.
kdm_range | int (0-255) | The mile range to allow people to order. If they are further away, then discourage them.

### GET delivery_methods/select
variable | datatype | desc
--- | --- | ---
kitchen_id | int | The id of the kitchen you want to get the delivery methods of
Returns
variable | desc
kdm_id | the id of the delivery method
kdm_owner | the id of the kitchen that owns the delivery method
kdm_type | the type of the delivery method (local pickup / delivery)
kdm_range | the mile range (0-255) for recommended service
kdm_created_date | the date and time the delivery method was created