# TravelEase

MIT122 Interactive Web Design and Development  
Assignment 2 – TravelEase Airline Booking Web Information System

## Team Members

- Ruzhi Deng – Frontend / UI
- Anusha Vemulapalli – Database / Admin
- Yaxing Wang – PHP Backend / Customer Booking

## Main Functions

TravelEase currently supports:

- Search flights
- View flight results
- User registration
- User login and logout
- Book flights
- View My Bookings
- Admin dashboard
- Add flights
- Edit flights
- Delete flights
- MySQL database connection

## How to Run TravelEase

### 1. Install WAMP

Install and start WAMP Server.

Make sure Apache and MySQL are running.

### 2. Download the Project

Download or clone this repository.

Place the TravelEase folder inside:

C:\wamp64\www\

The project should be located at:

C:\wamp64\www\TravelEase\

### 3. Import the Database

Open:

http://localhost/phpmyadmin/

Create a database called:

travelease

Then import:

travelease.sql

### 4. Check Database Configuration

The database configuration file is:

config/database.php

Default local settings:

Host: localhost  
Database: travelease  
Username: root  
Password: empty

Change these settings if your local MySQL configuration is different.

### 5. Run the Website

Open:

http://localhost/TravelEase/

## Testing

Please test the following workflow:

Search Flight → Results → Register/Login → Booking → My Bookings

For admin testing:

Login as an admin user → Admin Dashboard → Add/Edit/Delete Flight

## Feedback

Please check:

- Whether the website runs correctly
- Whether flight search works
- Whether registration and login work
- Whether booking works
- Whether My Bookings displays correctly
- Whether Admin flight management works
- Any bugs or UI improvements