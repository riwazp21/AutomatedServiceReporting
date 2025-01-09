# Service Hours Management System

## Overview
This project is a web-based system designed to manage and track service hours for students. The application features a secure login system, user and admin functionality, and a relational database with three interconnected tables: `Registration`, `Organization`, and `Service`.

## Database Structure
1. **Registration**: Stores user information, including student details.
2. **Organization**: Stores organization details where service hours are completed.
3. **Service**: Maintains records of service hours, linking student IDs (from `Registration`) and organization IDs (from `Organization`).

### Relationships
- There is a **one-to-many relationship** among the tables.
- The `Service` table takes `student_id` (from `Registration`) and `organization_id` (from `Organization`) to log new records of service hours for students.

## Features

### User Functionality
- **Login Page**: The first page to load on the website. Users can:
  - **Login** to access the main site.
  - **Create an Account** to register as a new user.
- **User Homepage**:
  - Displays the history of service hours completed by the user.
  - Shows details of the organizations and hours logged.

### Admin Functionality
- The admin has complete control over the database records, including the ability to:
  - **Fetch**, **Update**, **Delete**, and **Insert** records.
- **Admin Credentials**:
  - Username: `admin`
  - Password: `admin`

### Security and Validation
- **Session Management**:
  - Ensures that users cannot access the main site without logging in with correct credentials.
  - Users are restricted to the login page until a valid session is established.
- **Password Validation**:
  - Passwords in the `Create Account` page are validated using regex to ensure they contain at least one digit.
  - Passwords are securely hashed before being stored in the database.
- **Input Validation**:
  - Admin input for `year` and `hours` in the `Insert Record` page is validated using regex to ensure only digit values are entered.

## How It Works
1. Users start at the **Login Page**:
   - Login with valid credentials or create a new account.
2. Logged-in users are redirected to their **Homepage**:
   - View their service hour history and details.
3. Admins can manage the database:
   - Perform CRUD operations (Create, Read, Update, Delete) on records in the database.

## Technologies Used
- **Backend**: PHP, MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Security**: Password hashing, session management, regex-based validation

## Future Enhancements
- Add email notifications for users after logging service hours.
- Enable graphical reports for users to visualize their service hour history.
- Implement role-based access control for more granular permissions.

## Instructions
1. Clone the repository.
2. Set up the database and import the provided SQL schema.
3. Configure database connection settings in the backend code.
4. Launch the application in a local or live server environment.

## Authors
- Developed by Riwaz Poudel




