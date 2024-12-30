# Task Manager Application

### A simple Laravel-based web application for managing tasks, with features such as creating, editing, deleting, and reordering tasks using drag-and-drop functionality. Projects can also be associated with functions for better organization.

# Features
* Task Management: Create, edit, delete, and reorder tasks.
* Drag-and-Drop Reordering: Automatically updates task priorities based on the order.
* Project Association: Filter tasks by associated projects.
* Database Interaction: Stores tasks and projects in a MySQL database.


# Installation
## Prerequisites
### Ensure the following are installed on your system:
* PHP >= 8.1
* Composer
* Laravel 11
* MySQL 

## Step:
1. Unzip file.
2. Run `composer install`.
3. Copy `.env.example` to `.env` and update the database credentials.
4. Run `php artisan migrate` to set up the database.

## Populating the Database with Dummy Data
### You can use Laravel Tinker to populate the database with fake data to test the application.

## Steps:
* Open Tinker : `php artisan tinker`.
* Run the following in Tinker to create dummy projects:
`$faker = \Faker\Factory::create();`

`for ($i = 0; $i < 5; $i++) {
    \App\Models\Project::create(['name' => $faker->words(2, true)]);
}`

* Assign tasks to the created projects: `$projectIds = \App\Models\Project::pluck('id')->toArray();`

`for ($i = 0; $i < 20; $i++) {
    \App\Models\Task::create([
        'name' => $faker->sentence(3),
        'priority' => $i + 1,
        'project_id' => $faker->randomElement($projectIds),
    ]);
}`

* Verify Data: check your database to see all data or use 
`\App\Models\Project::all();
\App\Models\Task::all();` to see data.

## Running the Application
1. Start the server using `php artisan serve`.
2. Access the application at `http://127.0.0.1:8000/tasks`.

## Credit
### Developed by Tosin Akinbowa.
