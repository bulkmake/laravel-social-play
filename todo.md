Post
----
Types
- Single Image =  Single Product - Single Variant
- Images + Video Carousel  =  Single Product + Multi Product - Multi Variant
- Single Video or Reel = Multi Product
- Stories 24h status

Platforms
- Google Business
- FB
- Instagram
- Pinterest

SocialPost_Item
- id
- item_id
- item_type
- social_post_id

SocailPosts
- id
- caption
- cta_link
- media = json
- type = string
- scheduled_at = date
- responses = json
- status = enum(draft,scheduled,published,error)

```json
[
    'fb':{
        'post_id': '',
        'published_at: '',
        'error': '',
    },
    'insta':{
        'post_id': '',
        'published_at: '',
        'error': '',
    },
]
```

Actions
- Add Post
- Delete Post
- Preview Post
- Create Post
- Publish Now - Retry - Repost


CLI package
- a command to post an item
    - publish - reel,stories,carousel,single
    - retry
    - repost
    - delete
- a command to post items which are scheduled
- create post type:
    - carousel from media items = photo+video
    - reel from media items
    - video from media items
    - stories


bulkmake laravel-social-play


## How to install
1. Include inside composer.json:

```json
"repositories": {
    "bulkmake/laravel-social-play": {
        "type": "path",
        "url": "packages/bulkmake/laravel-social-play",
        "options": {
            "symlink": true
        }
    }
}
"require": {
    "bulkmake/laravel-social-play": "dev-master"
}
```

2. Run migrations via:
```
php artisan migrate
```

3. Publish config via:
```
php artisan vendor:publish --tag=social-play.config
```

4. Available commands:
```
php artisan socialplay:example
```