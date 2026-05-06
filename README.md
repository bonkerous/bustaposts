![Logo made by whatislove](img/bustaPostss.jpg)

The best[^1] social media platform, not endorsed by Busta Rhymes.

## Setup

### Requirements
* Some sort of webserver that supports PHP
* An internet connection
* Hopes and prayers

### SQL
```
CREATE DATABASE bp;
CREATE TABLE users (
    id int AUTO_INCREMENT PRIMARY KEY,
    handle varchar(16),
    email varchar(40),
    password varchar(70),
    banned bool,
    banReason mediumtext
);
CREATE TABLE posts (
    postId int AUTO_INCREMENT PRIMARY KEY,
    postData varchar(160),
    postDate timestamp DEFAULT CURRENT_TIMESTAMP,
    posterHandle varchar(16),
    posterId int,
    replyingTo int
);
CREATE TABLE news (
    newsPoster varchar(16),
    newsData mediumtext,
    newsDate datetime
);
```

### PHP
Create a settings.php file in the root directory and specify the following variables:
- $sqlUser
- $sqlPass
- $siteDomain
- $sysopEmail

[^1]: just don't look too close at the code