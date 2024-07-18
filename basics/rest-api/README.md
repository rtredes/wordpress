# WordPress REST API CRUD Operations

This project demonstrates basic CRUD (Create, Read, Update, Delete) operations using the WordPress REST API.

## Introduction

This project provides PHP functions to interact with the WordPress REST API for performing CRUD operations on posts. It includes functions for creating, reading, updating, and deleting posts via API requests.

## Setup

To use this project, follow these steps:

1. **Enable REST API**: Ensure the WordPress REST API is enabled on your WordPress site.

2. **Authentication**: Replace `'username:password'` in API requests with your WordPress username and password for basic authentication. Consider using more secure authentication methods for production.

3. **WordPress Environment**: Update URLs (`http://yoursite.com/wp-json/wp/v2/`) in PHP functions (`create_post_via_api()`, `get_posts_via_api()`, etc.) to match your WordPress site's domain and REST API endpoint.