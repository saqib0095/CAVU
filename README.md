### Overview

An API to allow customers to book parking spaces at Manchester Airport. 
There are 10 parking spaces available for this project, customers are able to park their car as long as they want. 


### Installation
Before starting please run the following commands in the following order. 

php artisan migrate
php artisan db:seed --class=ParkingSpaceSeeder
php artisan db:seed --class=OpeningHoursSeeder
php artisan db:seed --class=ParkingPriceSeeder
php artisan db:seed --class=ReservationSeeder


### EndPoints

/api/getAvailability - is to get how many spaces are available for a specific date and what price it would be to book that date.

 **URL:** `/api/getAvailability`
- **Method:** `GET`
- **Parameters:**
  - `start_date` (required): start date and time when you want your reservation to start E.g (2023-12-09 00:00:00)
  - `end_date` (required): end date and time when you want your reservation to end  E.g (2023-12-15 00:00:00). Has to be greater than start date
- **Response:**
     ```json
  {
    "results": {
        "DATE e.g.(2023-12-09)": {
            "available_spaces":{
                //available parking spaces
            }
        }
    }
  }

/api/uploadReservation - this endpoint allows you to create an reservation

 **URL:** `/api/uploadReservation`
- **Method:** `POST`
- **Parameters:**
  - `start_date` (required): start date and time when you want your reservation to start E.g (2023-12-09 00:00:00)
  - `end_date` (required): end date and time when you want your reservation to end  E.g (2023-12-15 00:00:00). Has to be greater than start date
  - `parking_space_id` (required): the parking space you require
- **Response:**
     ```json
  {
    "message" : "your car has been booked...",
    "reservation_id" : "reservation id number",
    "space_id": "space id",
    "created" : "boolean if reservation has been created"
  }


/api/updateReservation/ID - this endpoint allows you update an reservation if you provide the ID

**URL:** `/api/updateReservation/{id}`
- **Method:** `PUT`
- **Parameters:**
  - `start_date` (required): start date and time you want to update your reservation with
  - `end_date` (required): end date and time you want to update your reservation with
- **Response:**
     ```json
  {
    "message" : "Reservation_id has been updated",
    "reservation_id" : "reservation id number",
    "updatedstart_time": "updated start time",
    "updatedend_time" : "updated end time"
  }

/api/deleteReservation/ID - this endpoint will delete the provided reservation ID

**URL:** `/api/deleteReservation/{id}`
- **Method:** `DELETE`
- **Parameters:**
  N/A
- **Response:**
     ```json
  {
    "message": "Reservation deleted successfully"
  }

### Testing

php artisan test