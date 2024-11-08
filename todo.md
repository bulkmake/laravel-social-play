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
- media = array
- type = enum
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