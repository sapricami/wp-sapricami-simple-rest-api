# Better REST_APIs for Mobile Apps

[![Build Under Construction](https://img.shields.io/badge/Build-Under%20Construction-red.svg)](https://github.com/sapricami/wp-sapricami-simple-rest-api)

A Simple Rest Api plugin for wordpress build to take mobile app developer's woes away.

## Endpoints

### Posts 

`/wp-json/sap/v1/posts`

| Get Paramaters | default value | options                                                                                   | required |
|----------------|---------------|-------------------------------------------------------------------------------------------|----------|
| per_page       | 10            | any integer                                                                               | no       |
| page_no        | 1             | any integer                                                                               | no       |
| orderby        | date          | [LINK](https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters) | no       |
| order          | DESC          | [LINK](https://codex.wordpress.org/Class_Reference/WP_Query#Order_.26_Orderby_Parameters) | no       |
| thumb          | thumbnail     | [LINK](https://codex.wordpress.org/Post_Thumbnails#Thumbnail_Sizes)                       | no       |

`/wp-json/sap/v1/post/:ID`

where `ID` is the post id.

| Get Paramaters | default value | options                                                             | required |
|----------------|---------------|---------------------------------------------------------------------|----------|
| thumb          | thumbnail     | [LINK](https://codex.wordpress.org/Post_Thumbnails#Thumbnail_Sizes) | no       |

### Categories

`/wp-json/sap/v1/categories`

| Get Paramaters | default value | options | required |
|----------------|---------------|---------|----------|
| orderby        | name          | unknown | no       |
| order          | ASC           | unknown | no       |

`/wp-json/sap/v1/categories/hierarchical`

| Get Paramaters | default value | options | required |
|----------------|---------------|---------|----------|
| orderby        | name          | unknown | no       |
| order          | ASC           | unknown | no       |

### Author Data

`/wp-json/sap/v1/author/:ID`

where `ID` is the id of the author.


## Requirements 

Wordpress 4+

## Usage

Download the project as zip and uncompress in `wp-content\plugins` folder of your wordpress installation. Activate plugin from wp-admin

## Docs
[Postman Docs](https://documenter.getpostman.com/view/2685399/S11BxMCm)

## Developed By
[Ankur Singh](https://ankursinghagra.github.io/)

## References
[Wordpress Guide Used](https://developer.wordpress.org/rest-api/extending-the-rest-api/adding-custom-endpoints/)

## Version History

### 0.0.2 Beta
- added support for thumbnail sizes
- added single post route with comments

### 0.0.1 Beta
- First build
