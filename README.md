# bustaposts
The best[^1] social media platform, not endorsed by Busta Rhymes.

## Setup
### SQL
```
CREATE DATABASE bp;
CREATE TABLE users (
    id int AUTO_INCREMENT PRIMARY KEY,
    handle varchar(16),
    email varchar(40),
    password varchar(70)
);
CREATE TABLE posts (
    postId int AUTO_INCREMENT PRIMARY KEY,
    postData varchar(160),
    posterHandle varchar(16),
    posterId int
);
```

### PHP
Create a settings.php file in the root directory and specify the following variables:
- $sqlUser
- $sqlPass

[^1]: just don't look too close at the code