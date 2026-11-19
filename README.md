# Week 13 - Photo Gallery Assessment

## Project Overview

This project is a simple Photo Gallery System developed using Laravel and PostgreSQL. It allows users to upload images with captions, view uploaded photos in a gallery, and delete photos. The system uses Laravel's public storage disk to store image files and PostgreSQL to store their information.

## Technologies Used

- PHP and Laravel
- PostgreSQL
- HTML and CSS
- Laravel Blade
- Git and GitHub

## Features

- Upload images with required captions
- Validate image type and size (maximum 2 MB)
- Reject invalid and missing images
- Generate unique filenames for uploaded photos
- Display uploaded photos from newest to oldest
- Delete both image files and database records
- Display validation and success messages

## How to Run the Project

1. Install PHP, Composer, and PostgreSQL.
2. Clone the repository.
3. Run `composer install`.
4. Copy `.env.example` to `.env`.
5. Configure the PostgreSQL connection in `.env`.
6. Run `php artisan key:generate`.
7. Run `php artisan migrate:fresh`.
8. Run `php artisan storage:link`.
9. Run `php artisan serve`.
10. Open `http://127.0.0.1:8000/photos`.

**Note:** `migrate:fresh` removes existing database tables. Use a dedicated database for this project.

## Assessment Questions

### 1. Why do we keep the path in PostgreSQL and not the file itself?

We store the file path in PostgreSQL because the actual image is saved in Laravel's storage folder. Keeping only the path and other details in the database makes the records easier to manage and avoids storing large image files directly in the database.

### 2. Why do we not use the original file name to store the file?

We generate a unique filename to avoid conflicts when different users upload images with the same name. It also helps prevent unsafe or unexpected filenames from being used as storage paths.

### 3. What would be left behind if we deleted only the database row?

If we deleted only the database row, the actual image would still remain in the storage folder. This would create an unused file that takes up storage space and is no longer connected to a photo record.

## Validation Tests

| Test | Expected Result |
|---|---|
| Valid image | Upload succeeds |
| Renamed text file | Upload rejected |
| Image larger than 2 MB | Upload rejected |
| No image selected | Upload rejected |
| Delete photo | File and record removed |

## Storage and Database

Uploaded images are stored in `storage/app/public/photos`, while their captions, paths, original filenames, and sizes are saved in the PostgreSQL `photos` table.

The application uses a public storage link so the images can be displayed in the browser.