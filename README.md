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
```json
{
  "status": 1,
  "data": [
    {
      "user_id": "4",
      "user_name": "nick",
      "user_pass": "bruh",
      "user_banner_url": null,
      "user_email": "nick@zoty.us",
      "user_created_date": "2024-01-28 17:28:04"
    }
  ]
}
```

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
id | int | The id of the kitchen you want to get information on

Returns
```json
{
  "status": 1,
  "data": [
    {
      "kitchen_id": "5",
      "kitchen_owner": "4",
      "kitchen_name": "Deni's Cool Kitchen",
      "kitchen_is_active": "1",
      "kitchen_uses_cash": "0",
      "kitchen_uses_card": "0",
      "kitchen_created_date": "2024-01-29 21:52:10",
      "kitchen_working_hours": {
        "activeHours": {
          "monday": {
            "start": "09:00 AM",
            "end": "05:00 PM",
            "closed": false
          },
          "tuesday": {
            "start": "09:00 AM",
            "end": "05:00 PM",
            "closed": false
          },
          "wednesday": {
            "start": "09:00 AM",
            "end": "05:00 PM",
            "closed": false
          },
          "thursday": {
            "start": "09:00 AM",
            "end": "05:00 PM",
            "closed": false
          },
          "friday": {
            "start": "09:00 AM",
            "end": "05:00 PM",
            "closed": false
          },
          "saturday": {
            "start": null,
            "end": null,
            "closed": true
          },
          "sunday": {
            "start": null,
            "end": null,
            "closed": true
          }
        }
      }
    }
  ]
}
```

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
    "monday": {
      "start": "09:00 AM",
      "end": "05:00 PM",
      "closed": false
    },
    "tuesday": {
      "start": "09:00 AM",
      "end": "05:00 PM",
      "closed": false
    },
    "wednesday": {
      "start": "09:00 AM",
      "end": "05:00 PM",
      "closed": false
    },
    "thursday": {
      "start": "09:00 AM",
      "end": "05:00 PM",
      "closed": false
    },
    "friday": {
      "start": "09:00 AM",
      "end": "05:00 PM",
      "closed": false
    },
    "saturday": {
      "start": null,
      "end": null,
      "closed": true
    },
    "sunday": {
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
```json
{
  "status": 1,
  "data": [
    {
      "order_id": "1",
      "order_kitchen_id": "3",
      "order_user_id": "4",
      "order_products": [
        {
          "product_id": "2",
          "product_price": "6.9"
        },
        {
          "product_id": "1",
          "product_price": "5"
        }
      ],
      "order_total": "53.6",
      "order_status": "in_progress",
      "order_created_date": "2024-01-29 11:26:45"
    }
  ]
}
```

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
```json
{
  "status": 1,
  "data": [
    {
      "product_id": "2",
      "product_kitchen_id": "3",
      "product_title": "Pastrami Burger",
      "product_desc": "You get a pastrami ball it up put it on da grill burger it.",
      "product_price": "6.9",
      "product_category": "Handhelds",
      "product_tags": "pastrami,burger,cheese",
      "product_image_url": "https://images.unsplash.com/photo-1697384874178-3f2afa6bed7b?q=80&w=1887&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
      "product_created_date": "2024-01-29 12:17:57"
    }
  ]
}
```

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
**Kitchen**
```json
{
  "status": 1,
  "data": [
    {
      "address_id": "4",
      "address_owner": "3",
      "address_type": "kitchen",
      "address_line1": "308 Negra Arroyo Lane",
      "address_line2": "",
      "address_city": "Albuquerque",
      "address_state": "New Mexico",
      "address_zip": "87104",
      "address_phone": "",
      "address_created_date": "2024-01-29 21:06:07"
    }
  ]
}
```
**User**
```json
{
  "status": 1,
  "data": [
    {
      "address_id": "3",
      "address_owner": "4",
      "address_type": "user",
      "address_line1": "308 Negra Arroyo Lane",
      "address_line2": "",
      "address_city": "Albuquerque",
      "address_state": "New Mexico",
      "address_zip": "87104",
      "address_phone": "871-908-3981",
      "address_created_date": "2024-01-29 21:06:07"
    }
  ]
}
```

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
```json
{
  "status": 1,
  "data": [
    {
      "kdm_id": "4",
      "kdm_owner": "5",
      "kdm_type": "local_pickup",
      "kdm_range": "15",
      "kdm_created_date": "2024-01-30 13:56:46"
    }
  ]
}
```