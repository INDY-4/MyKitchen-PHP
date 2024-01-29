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

## Kitchens
### POST kitchens/create
variable | datatype | desc
--- | --- | ---
kitchen_owner_id | int | The id of the user who will own this kitchen
kitchen_name | string (100) | The title of the kitchen
kitchen_working_hours | json (100) | json object of the working hours for the kitchen
kitchen_uses_cash | string (yes/no) | Simple yes or no for if the kitchen uses cash
kitchen_uses_card | string (yes/no) | Simple yes or no for if the kitchen uses card

```json
// kitchen_working_hours
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