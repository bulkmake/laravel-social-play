<?php

return [
    'meta_access_token' => env('META_FB_IG_ACCESS_TOKEN'), // ex: EAAXXZBz47VXXXXX...VERY_LONG_STRING...
    'fb_page_id' => env('FACEBOOK_PAGE_ID'), //ex:  102XXXX02XXXX95
    'ig_user_id' => env('INSTAGRAM_USER_ID'),// ex: 122XXXX02XXXX65

    'pinterest_access_token' => env('PINTEREST_ACCESS_TOKEN'), // ex: PINXXZBz47VXXXXX...VERY_LONG_STRING...

    'google_client_secrets_json' => env('GOOGLE_CLIENT_SECRETS_JSON'), // file path from app root. ex: 'google-auth-token.json'
    'google_auth_token_json' => env('GOOGLE_AUTH_TOKEN_JSON')// file path from app root. ex: 'google-client_secrets.json'
];