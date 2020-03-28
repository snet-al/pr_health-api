# E-Commerce API
> URL: [https://e-comm-api.inovacion.al](https://e-comm-api.inovacion.al)

> URL: [https://e-comm-api-staging.inovacion.al/](https://e-comm-api-staging.inovacion.al/)

> URL: [https://ecommerce.inovacion.al/](https://ecommerce.inovacion.al/)
 (base image url)


# API

## Login Flow

- The users in the application can login using 3 methods : 

1. Username and password
2. Facebook
3. Gmail

In order for a user to login to our application he first has to sign up/register. The registration methods available are : 

1. Register by filling the form
2. Facebook
3. Gmail

When developing the mobile and web applications we should keep in mind the following : 

1 - When registering via the form the user has to fill the form presented to him in the mobile or web application. The system for each user among other data keeps track of his provider and provider id. The provider in this first case is "local". The provider id is the id that the system will generate automatically for the user trying ti register.

2 - When registering via facebook the mobile and web app developers should make sure they pass in the registration body request among other fields:
<br>
"provider":"facebook",<br> 
"provider_id" : "{TheFacebookProfileId}",<br> 
"access_token":"{The token received by facebook}"

3 - When registering via gmail the mobile and web app developers should make sure they pass in the registration body request among other fields:
 <br>
 "provider":"gmail",<br>
 "provider_id" : "{TheGmailProfileId}",<br>
 "access_token":"{The token received by gmail}"

### The login Process
1 - When login in via  username and password the user has to enter his username and password that he used during his registration process

2 - When login via facebook the mobile and web app developers should make sure they pass in the login body request among other fields: 
<br>
"provider":"facebook",<br>
"provider_id" : "{TheFacebookProfileId}",<br>
"access_token":"{The token received by facebook}.

3 - When login via gmail the mobile and web app developers should make sure they pass in the login body request among other fields : "provider":"gmail", "provider_id" : "{TheGmailProfileId}", "access_token":"{The token received by gmail}".


### Getting the token for accessing our system. 

-In order to avoid multiple user interactions both in the cases when the user registers in any of the methods or when he logs in using any of the methods the system in the response will automatically grant him an access token.


## Password Authorization
### Getting Access Token via login

-   Request Type: `POST`
-   URL: `/api/login`
-   Body params:
```json
{
  "device_type":"web",
  "email":"test@gmail.com",
  "password":"test",
  "provider":"local"
}
```

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 1558,
            "uuid": "9f8fb386-5738-49ea-8012-6ee1c9983e68",
            "first_name": "Paula",
            "last_name": "Strori",
            "email": "paulaas01@gmail.com",
            "birthday": "1996-07-18",
            "is_active": true,
            "client": {
                "id": 1589,
                "uuid": "badc9bab-d3ba-4840-b07f-fd4a693118b6",
                "name": "Paula Strori",
                "phone": "+355681035467",
                "mobile_phone": "+355681035467",
                "gender": null,
                "points": 0,
                "is_confirmed": false,
                "is_phone_confirmed": false,
                "subscribe_to_menu": false,
                "subscribe_to_newsletter": false,
                "nr_of_orders": 0,
                "phone_confirmation_code": 614791
            },
            "photo_path": null,
            "photo_thmb_path": null,
            "full_photo_path": null,
            "full_photo_thmb_path": null,
            "addresses": [],
            "is_confirmed": false,
            "is_email_confirmed": false,
            "bearer": {
                "token_type": "Bearer",
                "expires_in": 31535999,
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImUyOWIzOWIyODdhYmZiZWI4ZmRkM2FhNTkwN2VjNmRmOTk2MDVjYmZhZDE1ZTkyODk3MjEwMTE1NTBiZGJhMzAxNjIwN2QxNzFmMzM3YzVmIn0.eyJhdWQiOiIzNiIsImp0aSI6ImUyOWIzOWIyODdhYmZiZWI4ZmRkM2FhNTkwN2VjNmRmOTk2MDVjYmZhZDE1ZTkyODk3MjEwMTE1NTBiZGJhMzAxNjIwN2QxNzFmMzM3YzVmIiwiaWF0IjoxNTgyNzk2NzU0LCJuYmYiOjE1ODI3OTY3NTQsImV4cCI6MTYxNDMzMjc1Mywic3ViIjoiMTU1OCIsInNjb3BlcyI6WyIqIl19.XymiC0mWG1lUsH99O59gszE34EUXc5TIQUnhisMoedBT3aOHbdyC_4qWWfS0Y5-PH8V0ByuMR_Sx6f94rW5f2A5IIEbSfQTyA8nAG_ldR3WdhYCzLVjHG2VGBJby_wnR-L0rhRusNBbZI6JqdFWsoksKwYHfoI5W3XnAqtWmtunB_U-2J1JNDyka5W2qch9LsV64lOI9vu_Da9eTab6g04R8wMAgDZYrV9bzzqiVHe-Xe9EbGU2j5WjHMyVMjV4dG0JFfebc4WXsqfQD-N-bGF6XMyb_orWSVkhiwmTjAvEyYbX4ZcrLppjt5wJBmFdcUjXLSv4DOYEZOKuXVGnScoLyGydHvPnuscrMzDXHMDrVj1EMLsdb4pIdhQWZoD0N9OwR8D2dHV_VbRSY5yWkW5zv6UPWRue1ld47yicrNKAHdQ-EU1JyQKImXpts7Y1ujGdYHSx4-cT_NtSoOsChghRg4Cvjicux4ZGqSNfO40doQVQV1EVaRaeVFzFWrZl3Zfiq0rc4TTWyoB2DKKy9KaO0kccQBs4GcLmO4x26IJShNXyRKmQZ291rkHFHUD302Pg7JQCO_zZH46aISoTCTU35nQtYM7wQ0BKu5wycVtdlR1EXDpbVR-hwATH20MLZ3iiO6G3RWyYfC2hEH1dX62hjv5SzqleqxFtQkeNVjbY",
                "refresh_token": "def50200a005e6d057a25e549b06f9c6e686d464dd5e550cbf87254505379b4cecc9d8393f4372ccb3b44766af9d230ece6b011e7faeb64e7a6987fa8535050d32ca268238f72c079d2e43184c4adb4f591161378418b09db781f16d261e5602e0fb4fc83ca4520d1c96afb5b6a0d55bbf085773ce561a853a43e70c53c07678cb00687562e167a440215bd0ac7c69477c2704e2918f275c7d123fc838de349520045958972fc2cd5a52440a4cac52748dbc2aac1d0bcfe21141eb8db071f0380b7272661021aa1362671fd03c657efc1ac44693184df24ae5143d54c4222a94fe2e6b0e16546d751032fc27b1e752ec876f06b27e6269d592a750b324186041b836313188a62e6b2b20565b718df197b1e6715a04078e931a43973c5c3b30bf6581afef9637b99f8e9e9f77a38ee269e6df9b301198e37961c6d4fed2efc98c6799ff6ac9ed721697ba9fc017929d944e58e3e6719ee7ee923a5f8f0d104ee35bb3b4bdeeea011e"
            }
        }
    ],
    "errors": []
}
```

Each consecutive request should include the `Authorization` header, using the bearer scheme:

```
Authorization: Bearer the-access-token
```
Access tokens are valid for **_90 days_**. Once the token has expired, it can be refreshed by using the refresh token:

### Refreshing tokens
- Request Type: `POST`
- URL: `/oauth/token`
- Body params:

```json
{
    "grant_type": "refresh_token",
    "refresh_token": "the-refresh-token",
    "client_id": "35",
    "client_secret": "hwAhCumqim2vIPiCWidNGW5qfk8WSZ9ed0nMIkKS",
    "scope": "*"
}
```

Refresh tokes expire in **_180 days_**. The response with have the same format as the one getting an access token.
A new access token and refresh token will be sent back.

## General Configuration

### Getting General Configuration

- Request Type: `GET`
- URL: `/api/global-configuration`

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "require_email_verification": true,
            "require_phone_number_verification": true,
            "allow_guest_checkout": true
        }
    ],
    "errors": []
}
```
::: danger Failed Response :::

General Error
Division by zero
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Division by zero"
    ]
}
```

## Register

### Register by filling the form

- Request Type: `POST`
- URL: `/api/register`
- Body params:

```json
{
	"first_name" : "Paula",
	"last_name" : "Strori",
	"email" : "paulaas01@gmail.com",
	"birthday" : "1996-07-18",
	"password" : "paola123",
	"password_confirmation" : "paola123",
	"phone" : "+355681035467",
	"mobile_phone" : "+355687235161",
	"provider" : "local"
}
```
- provider should be "local"

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 1558,
            "uuid": "9f8fb386-5738-49ea-8012-6ee1c9983e68",
            "first_name": "Paula",
            "last_name": "Strori",
            "email": "paulaas01@gmail.com",
            "birthday": "1996-07-18",
            "is_active": true,
            "client": {
                "id": 1589,
                "uuid": "badc9bab-d3ba-4840-b07f-fd4a693118b6",
                "name": "Paula Strori",
                "phone": "+355681035467",
                "mobile_phone": "+355681035467",
                "gender": null,
                "points": 0,
                "is_confirmed": false,
                "is_phone_confirmed": false,
                "subscribe_to_menu": false,
                "subscribe_to_newsletter": false,
                "nr_of_orders": 0,
                "phone_confirmation_code": 614791
            },
            "photo_path": null,
            "photo_thmb_path": null,
            "full_photo_path": null,
            "full_photo_thmb_path": null,
            "addresses": [],
            "is_confirmed": false,
            "is_email_confirmed": false,
            "bearer": {
                "token_type": "Bearer",
                "expires_in": 31535999,
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImUyOWIzOWIyODdhYmZiZWI4ZmRkM2FhNTkwN2VjNmRmOTk2MDVjYmZhZDE1ZTkyODk3MjEwMTE1NTBiZGJhMzAxNjIwN2QxNzFmMzM3YzVmIn0.eyJhdWQiOiIzNiIsImp0aSI6ImUyOWIzOWIyODdhYmZiZWI4ZmRkM2FhNTkwN2VjNmRmOTk2MDVjYmZhZDE1ZTkyODk3MjEwMTE1NTBiZGJhMzAxNjIwN2QxNzFmMzM3YzVmIiwiaWF0IjoxNTgyNzk2NzU0LCJuYmYiOjE1ODI3OTY3NTQsImV4cCI6MTYxNDMzMjc1Mywic3ViIjoiMTU1OCIsInNjb3BlcyI6WyIqIl19.XymiC0mWG1lUsH99O59gszE34EUXc5TIQUnhisMoedBT3aOHbdyC_4qWWfS0Y5-PH8V0ByuMR_Sx6f94rW5f2A5IIEbSfQTyA8nAG_ldR3WdhYCzLVjHG2VGBJby_wnR-L0rhRusNBbZI6JqdFWsoksKwYHfoI5W3XnAqtWmtunB_U-2J1JNDyka5W2qch9LsV64lOI9vu_Da9eTab6g04R8wMAgDZYrV9bzzqiVHe-Xe9EbGU2j5WjHMyVMjV4dG0JFfebc4WXsqfQD-N-bGF6XMyb_orWSVkhiwmTjAvEyYbX4ZcrLppjt5wJBmFdcUjXLSv4DOYEZOKuXVGnScoLyGydHvPnuscrMzDXHMDrVj1EMLsdb4pIdhQWZoD0N9OwR8D2dHV_VbRSY5yWkW5zv6UPWRue1ld47yicrNKAHdQ-EU1JyQKImXpts7Y1ujGdYHSx4-cT_NtSoOsChghRg4Cvjicux4ZGqSNfO40doQVQV1EVaRaeVFzFWrZl3Zfiq0rc4TTWyoB2DKKy9KaO0kccQBs4GcLmO4x26IJShNXyRKmQZ291rkHFHUD302Pg7JQCO_zZH46aISoTCTU35nQtYM7wQ0BKu5wycVtdlR1EXDpbVR-hwATH20MLZ3iiO6G3RWyYfC2hEH1dX62hjv5SzqleqxFtQkeNVjbY",
                "refresh_token": "def50200a005e6d057a25e549b06f9c6e686d464dd5e550cbf87254505379b4cecc9d8393f4372ccb3b44766af9d230ece6b011e7faeb64e7a6987fa8535050d32ca268238f72c079d2e43184c4adb4f591161378418b09db781f16d261e5602e0fb4fc83ca4520d1c96afb5b6a0d55bbf085773ce561a853a43e70c53c07678cb00687562e167a440215bd0ac7c69477c2704e2918f275c7d123fc838de349520045958972fc2cd5a52440a4cac52748dbc2aac1d0bcfe21141eb8db071f0380b7272661021aa1362671fd03c657efc1ac44693184df24ae5143d54c4222a94fe2e6b0e16546d751032fc27b1e752ec876f06b27e6269d592a750b324186041b836313188a62e6b2b20565b718df197b1e6715a04078e931a43973c5c3b30bf6581afef9637b99f8e9e9f77a38ee269e6df9b301198e37961c6d4fed2efc98c6799ff6ac9ed721697ba9fc017929d944e58e3e6719ee7ee923a5f8f0d104ee35bb3b4bdeeea011e"
            }
        }
    ],
    "errors": []
}
```
::: danger Failed Response :::

General Error
Email field is not included.

```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
        [
            "The email field is required."
        ]
    ]
}
```

::: danger Failed Response :::

Provider field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
        [
            "The provider field is required.",
            "The access token field is required."
        ]
    ]
}
```

::: danger Failed Response :::

Password field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
        [
            "The password field is required."
        ]
    ]
}
```

::: danger Failed Response :::

Confirm Password field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
        [
            "The password confirmation does not match."
        ]
    ]
}
```

### Register with facebook
- Request Type: `POST`
- URL: `/api/register`
- Body params:

```json
{
	"email" : "muceku1@hotmail.com",
	"first_name" : "Armando",
	"last_name": "Muceku",
	"provider":"facebook",
	"device_type" : "android",
	"access_token" : "EAAFKkkxqOrcBAOBU9GhespZCpvy7obCZCSkY1ZCA6s2LrxSZBZA4URvXlkaCCMuKTZAGIspA2EPUwkuOaObFX4ddixfgzBEOEsmjZB3oqqWZBkVeXd2hctRgmju1IeiAKEhKb770w7FSdENH6rZCqNIyfgelkDhumfSnivDK1nHwgjiumIe0JVTNX"
}
```

- provider should be "facebook".
- access token will be used as password
- device type can be : "android", "ios", "web"

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 1445,
            "uuid": "b9609980-d2c7-4a2d-99bd-d3188b3163cc",
            "first_name": "Armando",
            "last_name": "Muceku",
            "email": "muceku1@hotmail.com",
            "birthday": "1996-07-18",
            "is_active": true,
            "client": {
                "id": 1486,
                "uuid": "366966f9-f964-4f22-931c-8c5438d3ca2c",
                "phone": "+35569144738f523",
                "mobile_phone": "+3556491478523d3453",
                "gender": "Male",
                "points": 0,
                "is_confirmed": true,
                "is_phone_confirmed": true,
                "subscribe_to_menu": true,
                "subscribe_to_newsletter": true,
                "nr_of_orders": 0,
                "phone_confirmation_code": 3276
            },
            "photo_path": null,
            "photo_thmb_path": null,
            "full_photo_path": null,
            "full_photo_thmb_path": null,
            "addresses": [],
            "is_confirmed": true,
            "is_email_confirmed": true,
            "bearer": {
                "token_type": "Bearer",
                "expires_in": 31535999,
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIn0.eyJhdWQiOiIzNSIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIiwiaWF0IjoxNTgyNTYzODAwLCJuYmYiOjE1ODI1NjM4MDAsImV4cCI6MTYxNDA5OTc5OSwic3ViIjoiMTQ0NSIsInNjb3BlcyI6WyIqIl19.Y9Um8beKE6Hm1iJnSESCQkz2EJXoT40yu4MpndSaDTvKwNpvrP586NOkhnqLLGD1Wc1y5-LfDTg57Ar5azs0QsR_3OBjCD6-jIaM2P9Jyk1OyA-ffw-usaL1ol8UZbQbvLWClUf0Dg7VJFdbZk4lB75OYgF-s2-ZXnmVwxQXUQui1q05YTiw4-mwf6BDg-PJDx4nDoOSNhIq0bOIaIDTHf8Dlckwt1bfIQCNTDav_-3LwxAJ3Ne1dMlvhQfK-Bl7GVaHdAI_Ht7T9cpPVDlxTUYVqEfPqVGiGseQhfDPzHa3ACKqgG-z6dBAbkqeQW0tJo8JgunvIIx7yXNb3AcRU_VcWUtcfx0cHqya8EpwNlTJZoLvMtraHp-SKAaeCBSC-GMkt6cEwu6hIoYkPnFHvu227VhdCiNb2bdmWkMZ_mAPNSAwGaMrZJW-MvFaLMPz5XEVb2DhI0NgSVFvHP3pA5ZKoWRYReYvqwEFHnK82u1RPrQfasMV1byMt2WyhNpsr7DYrksT1iApoOeZYeXjSGsRdVhXyEegl-H4GLGkPNV3RWMV2p50S2dJ9symgevu_OUpH5mTEDbIdBKB-wnL-Pi2zvVmODxW-GbDIAvzGK_y_YHfPem_EoxjT9V-IOQICqbx9ySlsmv8fJv2GAB6gtHSr-rh0kaV7wic-sIsY4w",
                "refresh_token": "def50200aa745948df08b98e1f0eea8eb26e5cc3e9d52747c7126f66b6b5c13feb81571d89acbbfb7c582cff75fcc555beb7a3cd15a990a864c5788b6853f4f16c4def0c0610ff6698bb21778060c8b4eee6a532e69683fb06f7387093d5aca7477ffe651197371970385426256290242f038346fae6e29d751730db502c5e1435605f80d072320da2f3dc9851826bb53eb80dd0b901d668ffd3cc3d062d1cac743c0ea7e4d06de177c0d513e960333d3d6459d0d181498d0f645f781810f9d5c19fdfa29a26ac0fba6437a4ee7050dc9e89e10b71243698117f16149d843562a9aa05b461998651bde0f684686fd38f393017d1c3284b849212399c255c0c7df4c21c4d6676590800b899e3fd354925351dfb440e6a0d5d446909360bc4a295f89bf11eaf88a42c0ea5dbe67c12ca3fc3419761eeaa3c0f2b3d8942c804564dc1064f4ac60498b113b484d337393fb38e249d79556fb8398f4c3ed6593bde1e077b13de9e1eb73a"
            }
        }
    ],
    "errors": []
}
```

::: danger Failed Response :::


General Error
Email field is not included.

```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
        [
            "The email field is required."
        ]
    ]
}
```

::: danger Failed Response :::

Provider field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "The provider field is required."
    ]
}
```

::: danger Failed Response :::

Token is invalid.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Invalid facebook access token."
    ]
}
```

::: danger Failed Response :::

Token is missing.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "You must provide an access token."
    ]
}
```


::: danger Failed Response :::

Token and email do not match.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Request email and facebook token do not match"
    ]
}
```

::: danger Failed Response :::

### Register with gmail
- Request Type: `POST`
- URL: `/api/register`
- Body params:

```json
{
	"email" : "muceku1@gmail.com",
	"first_name" : "Armando",
	"last_name": "Muceku",
	"provider":"gmail",
	"device_type": "android",
	"access_token" : "eyJhbGciOiJSUzI1NiIsImtpZCI6Ijc5YzgwOWRkMTE4NmNjMjI4YzRiYWY5MzU4NTk5NTMwY2U5MmI0YzgiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiMTA2MDQzMzU1OTM0Mi1yYWI1bDBoaXZpdXFiOWJxczk1Mzc2dHMyZ2Vlb2xqOS5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImF1ZCI6IjEwNjA0MzM1NTkzNDItcmFiNWwwaGl2aXVxYjlicXM5NTM3NnRzMmdlZW9sajkuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTQwNDYyMTc1NzMzMzY3OTMzMzMiLCJlbWFpbCI6Im11Y2VrdTFAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiI4TmtxZ2JMN1pwTWZPWGV5S0dJaW1nIiwibmFtZSI6IlNob3J0IFZpZGVvcyIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQUF1RTdtQ1F2MDhoQWZndGRaTTBjN0xpWDlqbU5aOVh0bUJqYl9CemVnNHg9czk2LWMiLCJnaXZlbl9uYW1lIjoiU2hvcnQiLCJmYW1pbHlfbmFtZSI6IlZpZGVvcyIsImxvY2FsZSI6ImVuIiwiaWF0IjoxNTgyNTYxMzcyLCJleHAiOjE1ODI1NjQ5NzIsImp0aSI6ImUwYzM2NTM5YjNhMGE5NWM0NzZjZjNlYTExYjViNTM5MDc3OWE0OTAifQ.PdcayCsoiX0iIXDn-qTNJmIgHgFeifu0UuyvVJUp8JS6cF-G1SG5eoslJh5uWetnuRzt8MEDLecAG4ZzgDoGu-VFlmtEZNCcyd6i6m5ges7aDoFh_F6ZnyD3wtK7NCEtIHatrY_nbBSbp3loCLQKPkxCgQ0j9uhsOjtfMPQWyt_ffU3iSfTh72ucqDIIwsFEchEEOvDpvjNZ5OSPiZNBXn7s677XPoBSo2nWCR4DIqHIt3rLX4tiVjHr3gaPRxSxTy_kfxax1tV1qUaOw9SbdlLd-V1NgKNYgF6REsqy-9fUsdScs80-eGmjlAdq3xUm-Y6d2QIHrHUJvrIQJaDXXw"
}
```

- provider should be "gmail".
- access token will be used as password
- device type can be : "android", "ios", "web"

::: tip Success Response :::

```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 1445,
            "uuid": "b9609980-d2c7-4a2d-99bd-d3188b3163cc",
            "first_name": "Armando",
            "last_name": "Muceku",
            "email": "muceku1@gmail.com",
            "birthday": "1996-07-18",
            "is_active": true,
            "client": {
                "id": 1486,
                "uuid": "366966f9-f964-4f22-931c-8c5438d3ca2c",
                "phone": "+35569144738f523",
                "mobile_phone": "+3556491478523d3453",
                "gender": "Male",
                "points": 0,
                "is_confirmed": true,
                "is_phone_confirmed": true,
                "subscribe_to_menu": true,
                "subscribe_to_newsletter": true,
                "nr_of_orders": 0,
                "phone_confirmation_code": 3276
            },
            "photo_path": null,
            "photo_thmb_path": null,
            "full_photo_path": null,
            "full_photo_thmb_path": null,
            "addresses": [],
            "is_confirmed": true,
            "is_email_confirmed": true,
            "bearer": {
                "token_type": "Bearer",
                "expires_in": 31535999,
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIn0.eyJhdWQiOiIzNSIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIiwiaWF0IjoxNTgyNTYzODAwLCJuYmYiOjE1ODI1NjM4MDAsImV4cCI6MTYxNDA5OTc5OSwic3ViIjoiMTQ0NSIsInNjb3BlcyI6WyIqIl19.Y9Um8beKE6Hm1iJnSESCQkz2EJXoT40yu4MpndSaDTvKwNpvrP586NOkhnqLLGD1Wc1y5-LfDTg57Ar5azs0QsR_3OBjCD6-jIaM2P9Jyk1OyA-ffw-usaL1ol8UZbQbvLWClUf0Dg7VJFdbZk4lB75OYgF-s2-ZXnmVwxQXUQui1q05YTiw4-mwf6BDg-PJDx4nDoOSNhIq0bOIaIDTHf8Dlckwt1bfIQCNTDav_-3LwxAJ3Ne1dMlvhQfK-Bl7GVaHdAI_Ht7T9cpPVDlxTUYVqEfPqVGiGseQhfDPzHa3ACKqgG-z6dBAbkqeQW0tJo8JgunvIIx7yXNb3AcRU_VcWUtcfx0cHqya8EpwNlTJZoLvMtraHp-SKAaeCBSC-GMkt6cEwu6hIoYkPnFHvu227VhdCiNb2bdmWkMZ_mAPNSAwGaMrZJW-MvFaLMPz5XEVb2DhI0NgSVFvHP3pA5ZKoWRYReYvqwEFHnK82u1RPrQfasMV1byMt2WyhNpsr7DYrksT1iApoOeZYeXjSGsRdVhXyEegl-H4GLGkPNV3RWMV2p50S2dJ9symgevu_OUpH5mTEDbIdBKB-wnL-Pi2zvVmODxW-GbDIAvzGK_y_YHfPem_EoxjT9V-IOQICqbx9ySlsmv8fJv2GAB6gtHSr-rh0kaV7wic-sIsY4w",
                "refresh_token": "def50200aa745948df08b98e1f0eea8eb26e5cc3e9d52747c7126f66b6b5c13feb81571d89acbbfb7c582cff75fcc555beb7a3cd15a990a864c5788b6853f4f16c4def0c0610ff6698bb21778060c8b4eee6a532e69683fb06f7387093d5aca7477ffe651197371970385426256290242f038346fae6e29d751730db502c5e1435605f80d072320da2f3dc9851826bb53eb80dd0b901d668ffd3cc3d062d1cac743c0ea7e4d06de177c0d513e960333d3d6459d0d181498d0f645f781810f9d5c19fdfa29a26ac0fba6437a4ee7050dc9e89e10b71243698117f16149d843562a9aa05b461998651bde0f684686fd38f393017d1c3284b849212399c255c0c7df4c21c4d6676590800b899e3fd354925351dfb440e6a0d5d446909360bc4a295f89bf11eaf88a42c0ea5dbe67c12ca3fc3419761eeaa3c0f2b3d8942c804564dc1064f4ac60498b113b484d337393fb38e249d79556fb8398f4c3ed6593bde1e077b13de9e1eb73a"
            }
        }
    ],
    "errors": []
}
```

::: danger Failed Response :::


General Error
Email field is not included.

```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "The email field is required."
    ]
}
```

::: danger Failed Response :::

Provider field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
        [
            "The provider field is required."
        ]
    ]
}
```

::: danger Failed Response :::

Token is invalid.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Invalid gmail access token."
    ]
}
```

::: danger Failed Response :::

Token is missing.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "You must provide an access token."
    ]
}
```


::: danger Failed Response :::

Token and email do not match.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Request email and gmail token do not match"
    ]
}
```

::: danger Failed Response :::

##Login

### Login With Username and password


-   Request Type: `POST`
-   URL: `/api/login`
-   Body params:
```json
{
  "device_type":"web",
  "email":"test@gmail.com",
  "password":"test",
  "provider":"local"
}
```

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 1558,
            "uuid": "9f8fb386-5738-49ea-8012-6ee1c9983e68",
            "first_name": "Paula",
            "last_name": "Strori",
            "email": "paulaas01@gmail.com",
            "birthday": "1996-07-18",
            "is_active": true,
            "client": {
                "id": 1589,
                "uuid": "badc9bab-d3ba-4840-b07f-fd4a693118b6",
                "name": "Paula Strori",
                "phone": "+355681035467",
                "mobile_phone": "+355681035467",
                "gender": null,
                "points": 0,
                "is_confirmed": false,
                "is_phone_confirmed": false,
                "subscribe_to_menu": false,
                "subscribe_to_newsletter": false,
                "nr_of_orders": 0,
                "phone_confirmation_code": 614791
            },
            "photo_path": null,
            "photo_thmb_path": null,
            "full_photo_path": null,
            "full_photo_thmb_path": null,
            "addresses": [],
            "is_confirmed": false,
            "is_email_confirmed": false,
            "bearer": {
                "token_type": "Bearer",
                "expires_in": 31535999,
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImUyOWIzOWIyODdhYmZiZWI4ZmRkM2FhNTkwN2VjNmRmOTk2MDVjYmZhZDE1ZTkyODk3MjEwMTE1NTBiZGJhMzAxNjIwN2QxNzFmMzM3YzVmIn0.eyJhdWQiOiIzNiIsImp0aSI6ImUyOWIzOWIyODdhYmZiZWI4ZmRkM2FhNTkwN2VjNmRmOTk2MDVjYmZhZDE1ZTkyODk3MjEwMTE1NTBiZGJhMzAxNjIwN2QxNzFmMzM3YzVmIiwiaWF0IjoxNTgyNzk2NzU0LCJuYmYiOjE1ODI3OTY3NTQsImV4cCI6MTYxNDMzMjc1Mywic3ViIjoiMTU1OCIsInNjb3BlcyI6WyIqIl19.XymiC0mWG1lUsH99O59gszE34EUXc5TIQUnhisMoedBT3aOHbdyC_4qWWfS0Y5-PH8V0ByuMR_Sx6f94rW5f2A5IIEbSfQTyA8nAG_ldR3WdhYCzLVjHG2VGBJby_wnR-L0rhRusNBbZI6JqdFWsoksKwYHfoI5W3XnAqtWmtunB_U-2J1JNDyka5W2qch9LsV64lOI9vu_Da9eTab6g04R8wMAgDZYrV9bzzqiVHe-Xe9EbGU2j5WjHMyVMjV4dG0JFfebc4WXsqfQD-N-bGF6XMyb_orWSVkhiwmTjAvEyYbX4ZcrLppjt5wJBmFdcUjXLSv4DOYEZOKuXVGnScoLyGydHvPnuscrMzDXHMDrVj1EMLsdb4pIdhQWZoD0N9OwR8D2dHV_VbRSY5yWkW5zv6UPWRue1ld47yicrNKAHdQ-EU1JyQKImXpts7Y1ujGdYHSx4-cT_NtSoOsChghRg4Cvjicux4ZGqSNfO40doQVQV1EVaRaeVFzFWrZl3Zfiq0rc4TTWyoB2DKKy9KaO0kccQBs4GcLmO4x26IJShNXyRKmQZ291rkHFHUD302Pg7JQCO_zZH46aISoTCTU35nQtYM7wQ0BKu5wycVtdlR1EXDpbVR-hwATH20MLZ3iiO6G3RWyYfC2hEH1dX62hjv5SzqleqxFtQkeNVjbY",
                "refresh_token": "def50200a005e6d057a25e549b06f9c6e686d464dd5e550cbf87254505379b4cecc9d8393f4372ccb3b44766af9d230ece6b011e7faeb64e7a6987fa8535050d32ca268238f72c079d2e43184c4adb4f591161378418b09db781f16d261e5602e0fb4fc83ca4520d1c96afb5b6a0d55bbf085773ce561a853a43e70c53c07678cb00687562e167a440215bd0ac7c69477c2704e2918f275c7d123fc838de349520045958972fc2cd5a52440a4cac52748dbc2aac1d0bcfe21141eb8db071f0380b7272661021aa1362671fd03c657efc1ac44693184df24ae5143d54c4222a94fe2e6b0e16546d751032fc27b1e752ec876f06b27e6269d592a750b324186041b836313188a62e6b2b20565b718df197b1e6715a04078e931a43973c5c3b30bf6581afef9637b99f8e9e9f77a38ee269e6df9b301198e37961c6d4fed2efc98c6799ff6ac9ed721697ba9fc017929d944e58e3e6719ee7ee923a5f8f0d104ee35bb3b4bdeeea011e"
            }
        }
    ],
    "errors": []
}
```

Each consecutive request should include the `Authorization` header, using the bearer scheme:


### Login with facebook

-   Request Type: `POST`
-   URL: `/api/login`

-   Body params:

```json
{
	"email" : "muceku1@hotmail.com",
	"provider":"facebook",
	"device_type": "android",
	"access_token" : "EAAFKkkxqOrcBAOBU9GhespZCpvy7obCZCSkY1ZCA6s2LrxSZBZA4URvXlkaCCMuKTZAGIspA2EPUwkuOaObFX4ddixfgzBEOEsmjZB3oqqWZBkVeXd2hctRgmju1IeiAKEhKb770w7FSdENH6rZCqNIyfgelkDhumfSnivDK1nHwgjiumIe0JVTNX"
}
```
- provider should be "facebook".
- access token will be used as password
- device type can be : "android", "ios", "web"

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 1445,
            "uuid": "b9609980-d2c7-4a2d-99bd-d3188b3163cc",
            "first_name": "Armando",
            "last_name": "Muceku",
            "email": "muceku1@hotmail.com",
            "birthday": "1996-07-18",
            "is_active": true,
            "client": {
                "id": 1486,
                "uuid": "366966f9-f964-4f22-931c-8c5438d3ca2c",
                "phone": "+35569144738f523",
                "mobile_phone": "+3556491478523d3453",
                "gender": "Male",
                "points": 0,
                "is_confirmed": true,
                "is_phone_confirmed": true,
                "subscribe_to_menu": true,
                "subscribe_to_newsletter": true,
                "nr_of_orders": 0,
                "phone_confirmation_code": 3276
            },
            "photo_path": null,
            "photo_thmb_path": null,
            "full_photo_path": null,
            "full_photo_thmb_path": null,
            "addresses": [],
            "is_confirmed": true,
            "is_email_confirmed": true,
            "bearer": {
                "token_type": "Bearer",
                "expires_in": 31535999,
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIn0.eyJhdWQiOiIzNSIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIiwiaWF0IjoxNTgyNTYzODAwLCJuYmYiOjE1ODI1NjM4MDAsImV4cCI6MTYxNDA5OTc5OSwic3ViIjoiMTQ0NSIsInNjb3BlcyI6WyIqIl19.Y9Um8beKE6Hm1iJnSESCQkz2EJXoT40yu4MpndSaDTvKwNpvrP586NOkhnqLLGD1Wc1y5-LfDTg57Ar5azs0QsR_3OBjCD6-jIaM2P9Jyk1OyA-ffw-usaL1ol8UZbQbvLWClUf0Dg7VJFdbZk4lB75OYgF-s2-ZXnmVwxQXUQui1q05YTiw4-mwf6BDg-PJDx4nDoOSNhIq0bOIaIDTHf8Dlckwt1bfIQCNTDav_-3LwxAJ3Ne1dMlvhQfK-Bl7GVaHdAI_Ht7T9cpPVDlxTUYVqEfPqVGiGseQhfDPzHa3ACKqgG-z6dBAbkqeQW0tJo8JgunvIIx7yXNb3AcRU_VcWUtcfx0cHqya8EpwNlTJZoLvMtraHp-SKAaeCBSC-GMkt6cEwu6hIoYkPnFHvu227VhdCiNb2bdmWkMZ_mAPNSAwGaMrZJW-MvFaLMPz5XEVb2DhI0NgSVFvHP3pA5ZKoWRYReYvqwEFHnK82u1RPrQfasMV1byMt2WyhNpsr7DYrksT1iApoOeZYeXjSGsRdVhXyEegl-H4GLGkPNV3RWMV2p50S2dJ9symgevu_OUpH5mTEDbIdBKB-wnL-Pi2zvVmODxW-GbDIAvzGK_y_YHfPem_EoxjT9V-IOQICqbx9ySlsmv8fJv2GAB6gtHSr-rh0kaV7wic-sIsY4w",
                "refresh_token": "def50200aa745948df08b98e1f0eea8eb26e5cc3e9d52747c7126f66b6b5c13feb81571d89acbbfb7c582cff75fcc555beb7a3cd15a990a864c5788b6853f4f16c4def0c0610ff6698bb21778060c8b4eee6a532e69683fb06f7387093d5aca7477ffe651197371970385426256290242f038346fae6e29d751730db502c5e1435605f80d072320da2f3dc9851826bb53eb80dd0b901d668ffd3cc3d062d1cac743c0ea7e4d06de177c0d513e960333d3d6459d0d181498d0f645f781810f9d5c19fdfa29a26ac0fba6437a4ee7050dc9e89e10b71243698117f16149d843562a9aa05b461998651bde0f684686fd38f393017d1c3284b849212399c255c0c7df4c21c4d6676590800b899e3fd354925351dfb440e6a0d5d446909360bc4a295f89bf11eaf88a42c0ea5dbe67c12ca3fc3419761eeaa3c0f2b3d8942c804564dc1064f4ac60498b113b484d337393fb38e249d79556fb8398f4c3ed6593bde1e077b13de9e1eb73a"
            }
        }
    ],
    "errors": []
}
```
::: danger Failed Response :::


General Error
Email field is not included.

```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "The email field is required."
    ]
}
```

::: danger Failed Response :::

Provider field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "The provider field is required."
    ]
}
```

::: danger Failed Response :::

Token is invalid.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Invalid facebook access token."
    ]
}
```

::: danger Failed Response :::

Token is missing.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "You must provide an access token."
    ]
}
```


::: danger Failed Response :::

Token and email do not match.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Request email and facebook token do not match"
    ]
}
```

::: danger Failed Response :::



### Login with gmail

-   Request Type: `POST`
-   URL: `/api/login`
-   Body params:

```json
{
	"email" : "muceku1@gmail.com",
	"provider":"gmail",
	"device_type":"android",
	"access_token" : "eyJhbGciOiJSUzI1NiIsImtpZCI6Ijc5YzgwOWRkMTE4NmNjMjI4YzRiYWY5MzU4NTk5NTMwY2U5MmI0YzgiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXpwIjoiMTA2MDQzMzU1OTM0Mi1yYWI1bDBoaXZpdXFiOWJxczk1Mzc2dHMyZ2Vlb2xqOS5hcHBzLmdvb2dsZXVzZXJjb250ZW50LmNvbSIsImF1ZCI6IjEwNjA0MzM1NTkzNDItcmFiNWwwaGl2aXVxYjlicXM5NTM3NnRzMmdlZW9sajkuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMTQwNDYyMTc1NzMzMzY3OTMzMzMiLCJlbWFpbCI6Im11Y2VrdTFAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiI4TmtxZ2JMN1pwTWZPWGV5S0dJaW1nIiwibmFtZSI6IlNob3J0IFZpZGVvcyIsInBpY3R1cmUiOiJodHRwczovL2xoMy5nb29nbGV1c2VyY29udGVudC5jb20vYS0vQUF1RTdtQ1F2MDhoQWZndGRaTTBjN0xpWDlqbU5aOVh0bUJqYl9CemVnNHg9czk2LWMiLCJnaXZlbl9uYW1lIjoiU2hvcnQiLCJmYW1pbHlfbmFtZSI6IlZpZGVvcyIsImxvY2FsZSI6ImVuIiwiaWF0IjoxNTgyNTYxMzcyLCJleHAiOjE1ODI1NjQ5NzIsImp0aSI6ImUwYzM2NTM5YjNhMGE5NWM0NzZjZjNlYTExYjViNTM5MDc3OWE0OTAifQ.PdcayCsoiX0iIXDn-qTNJmIgHgFeifu0UuyvVJUp8JS6cF-G1SG5eoslJh5uWetnuRzt8MEDLecAG4ZzgDoGu-VFlmtEZNCcyd6i6m5ges7aDoFh_F6ZnyD3wtK7NCEtIHatrY_nbBSbp3loCLQKPkxCgQ0j9uhsOjtfMPQWyt_ffU3iSfTh72ucqDIIwsFEchEEOvDpvjNZ5OSPiZNBXn7s677XPoBSo2nWCR4DIqHIt3rLX4tiVjHr3gaPRxSxTy_kfxax1tV1qUaOw9SbdlLd-V1NgKNYgF6REsqy-9fUsdScs80-eGmjlAdq3xUm-Y6d2QIHrHUJvrIQJaDXXw"
}
```

- provider should be "gmail".
- access token will be used as password
- device type can be : "android", "ios", "web"

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 1445,
            "uuid": "b9609980-d2c7-4a2d-99bd-d3188b3163cc",
            "first_name": "Armando",
            "last_name": "Muceku",
            "email": "muceku1@gmail.com",
            "birthday": "1996-07-18",
            "is_active": true,
            "client": {
                "id": 1486,
                "uuid": "366966f9-f964-4f22-931c-8c5438d3ca2c",
                "phone": "+35569144738f523",
                "mobile_phone": "+3556491478523d3453",
                "gender": "Male",
                "points": 0,
                "is_confirmed": true,
                "is_phone_confirmed": true,
                "subscribe_to_menu": true,
                "subscribe_to_newsletter": true,
                "nr_of_orders": 0,
                "phone_confirmation_code": 3276
            },
            "photo_path": null,
            "photo_thmb_path": null,
            "full_photo_path": null,
            "full_photo_thmb_path": null,
            "addresses": [],
            "is_confirmed": true,
            "is_email_confirmed": true,
            "bearer": {
                "token_type": "Bearer",
                "expires_in": 31535999,
                "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIn0.eyJhdWQiOiIzNSIsImp0aSI6IjEwY2NlZGUzMDhmOTdmMjNlMWQ3NjBhNjUxZjYzZDRmNDYxOWY3Mzg3Y2M5YjUxNDhmOTlhODM2YjIxZmJkOTM2YmZmMDBhZWQwNTU2ZjZiIiwiaWF0IjoxNTgyNTYzODAwLCJuYmYiOjE1ODI1NjM4MDAsImV4cCI6MTYxNDA5OTc5OSwic3ViIjoiMTQ0NSIsInNjb3BlcyI6WyIqIl19.Y9Um8beKE6Hm1iJnSESCQkz2EJXoT40yu4MpndSaDTvKwNpvrP586NOkhnqLLGD1Wc1y5-LfDTg57Ar5azs0QsR_3OBjCD6-jIaM2P9Jyk1OyA-ffw-usaL1ol8UZbQbvLWClUf0Dg7VJFdbZk4lB75OYgF-s2-ZXnmVwxQXUQui1q05YTiw4-mwf6BDg-PJDx4nDoOSNhIq0bOIaIDTHf8Dlckwt1bfIQCNTDav_-3LwxAJ3Ne1dMlvhQfK-Bl7GVaHdAI_Ht7T9cpPVDlxTUYVqEfPqVGiGseQhfDPzHa3ACKqgG-z6dBAbkqeQW0tJo8JgunvIIx7yXNb3AcRU_VcWUtcfx0cHqya8EpwNlTJZoLvMtraHp-SKAaeCBSC-GMkt6cEwu6hIoYkPnFHvu227VhdCiNb2bdmWkMZ_mAPNSAwGaMrZJW-MvFaLMPz5XEVb2DhI0NgSVFvHP3pA5ZKoWRYReYvqwEFHnK82u1RPrQfasMV1byMt2WyhNpsr7DYrksT1iApoOeZYeXjSGsRdVhXyEegl-H4GLGkPNV3RWMV2p50S2dJ9symgevu_OUpH5mTEDbIdBKB-wnL-Pi2zvVmODxW-GbDIAvzGK_y_YHfPem_EoxjT9V-IOQICqbx9ySlsmv8fJv2GAB6gtHSr-rh0kaV7wic-sIsY4w",
                "refresh_token": "def50200aa745948df08b98e1f0eea8eb26e5cc3e9d52747c7126f66b6b5c13feb81571d89acbbfb7c582cff75fcc555beb7a3cd15a990a864c5788b6853f4f16c4def0c0610ff6698bb21778060c8b4eee6a532e69683fb06f7387093d5aca7477ffe651197371970385426256290242f038346fae6e29d751730db502c5e1435605f80d072320da2f3dc9851826bb53eb80dd0b901d668ffd3cc3d062d1cac743c0ea7e4d06de177c0d513e960333d3d6459d0d181498d0f645f781810f9d5c19fdfa29a26ac0fba6437a4ee7050dc9e89e10b71243698117f16149d843562a9aa05b461998651bde0f684686fd38f393017d1c3284b849212399c255c0c7df4c21c4d6676590800b899e3fd354925351dfb440e6a0d5d446909360bc4a295f89bf11eaf88a42c0ea5dbe67c12ca3fc3419761eeaa3c0f2b3d8942c804564dc1064f4ac60498b113b484d337393fb38e249d79556fb8398f4c3ed6593bde1e077b13de9e1eb73a"
            }
        }
    ],
    "errors": []
}
```
::: danger Failed Response :::

General Error
Email field is not included.

```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "The email field is required."
    ]
}
```

::: danger Failed Response :::

Provider field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "The provider field is required."
    ]
}
```

::: danger Failed Response :::

Token is invalid.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Invalid gmail access token."
    ]
}
```

::: danger Failed Response :::

Token is missing.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "You must provide an access token."
    ]
}
```


::: danger Failed Response :::

Token and email do not match.
```json
{
    "message": "Error occurred",
    "status": 5,
    "data": [],
    "errors": [
        "Request email and gmail token do not match"
    ]
}
```

::: danger Failed Response :::

## Client-User update

-   Request Type: `POST`
-   URL: `clients/update`

-   Body params:

```json
{
        "id": 32,
        "uuid": "5b53b186-aaeb-4663-964e-e79a526ec951",
        "first_name": "Akil",
        "last_name": "Rajdho",
        "email": "akil.rajdho@gmail.com",
        "birthday": "1987-09-08",
        "is_active": true,
        "phone":"355686060063",
        "client": {
            "id": 24,
            "uuid": "db77f3ae-2320-41b9-ae71-3dfc3e15fc40",
            "phone": "+355686060063",
            "mobile_phone": "+355686060063",
            "gender": "Male",
            "points": 19224,
            "is_confirmed": false,
            "is_phone_confirmed": false,
            "subscribe_to_menu": true,
            "subscribe_to_newsletter": true,
            "nr_of_orders": 193,
            "phone_confirmation_code": 245998
        },
        "photo_path": "uploads/users/32/1537196786_user_photo.jpeg",
        "photo_thmb_path": "uploads/users/32/tn-1537196786_user_photo.jpeg",
        "full_photo_path": "http://ecommerce-api.test/uploads/users/32/1537196786_user_photo.jpeg",
        "full_photo_thmb_path": "http://ecommerce-api.test/uploads/users/32/tn-1537196786_user_photo.jpeg",
        "addresses": [
            {
                "id": 1094,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            },
            {
                "id": null,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            }
        ],
        "is_confirmed": true,
        "is_email_confirmed": true
    }
```
- The request body is the entire response you get when you register the user.
- This method updates all the information in the response body except photos, confirmation fields, is_active and number of orders
- If the user tries to update the email (sends another email in the request body other than the email registered the system will put the user in unconfirmed mode and will automatically resend the email for verification)
- If the user tires to update the mobile_phone number with a number other than the one registered the system will automatically put the client object in unconfirmed mode and will send the sms for verification
- If you want to create a new address in the update method just keep the address id null

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": {
        "id": 32,
        "uuid": "5b53b186-aaeb-4663-964e-e79a526ec951",
        "first_name": "Akil",
        "last_name": "Rajdho",
        "email": "akil.rajdho@gmail.com",
        "birthday": "1987-09-08",
        "is_active": true,
        "client": {
            "id": 24,
            "uuid": "db77f3ae-2320-41b9-ae71-3dfc3e15fc40",
            "phone": "355686060063",
            "mobile_phone": "+355686060063",
            "gender": "Male",
            "points": 19224,
            "is_confirmed": false,
            "is_phone_confirmed": false,
            "subscribe_to_menu": true,
            "subscribe_to_newsletter": true,
            "nr_of_orders": 193,
            "phone_confirmation_code": 236464
        },
        "photo_path": "uploads/users/32/1537196786_user_photo.jpeg",
        "photo_thmb_path": "uploads/users/32/tn-1537196786_user_photo.jpeg",
        "full_photo_path": "http://ecommerce-api.test/uploads/users/32/1537196786_user_photo.jpeg",
        "full_photo_thmb_path": "http://ecommerce-api.test/uploads/users/32/tn-1537196786_user_photo.jpeg",
        "addresses": [
            {
                "id": 1094,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            },
            {
                "id": 1095,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            }
        ],
        "is_confirmed": false,
        "is_email_confirmed": false
    },
    "errors": []
}


```
General Error
Email field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "The email field is required."
    ]
}
```


## Client Send Mobile Phone Confirmation Code

-   Request Type: `GET`
-   URL: `clients/send-mobile-confirmation-sms`

 The server will send a sms message to the authenticated user with the confirmation code.

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [{
        "id": 32,
        "uuid": "5b53b186-aaeb-4663-964e-e79a526ec951",
        "first_name": "Akil",
        "last_name": "Rajdho",
        "email": "akil.rajdho@gmail.com",
        "birthday": "1987-09-08",
        "is_active": true,
        "client": {
            "id": 24,
            "uuid": "db77f3ae-2320-41b9-ae71-3dfc3e15fc40",
            "phone": "355686060063",
            "mobile_phone": "+355686060063",
            "gender": "Male",
            "points": 19224,
            "is_confirmed": false,
            "is_phone_confirmed": false,
            "subscribe_to_menu": true,
            "subscribe_to_newsletter": true,
            "nr_of_orders": 193,
            "phone_confirmation_code": 236464
        },
        "photo_path": "uploads/users/32/1537196786_user_photo.jpeg",
        "photo_thmb_path": "uploads/users/32/tn-1537196786_user_photo.jpeg",
        "full_photo_path": "http://ecommerce-api.test/uploads/users/32/1537196786_user_photo.jpeg",
        "full_photo_thmb_path": "http://ecommerce-api.test/uploads/users/32/tn-1537196786_user_photo.jpeg",
        "addresses": [
            {
                "id": 1094,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            },
            {
                "id": 1095,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            }
        ],
        "is_confirmed": false,
        "is_email_confirmed": false
    }],
    "errors": []
}


```
General Error
Email field is not included.
```json
{
    "message": "Error Occurred",
    "status": 2,
    "data": [],
    "errors": [
            "Error message"
    ]

}
```

## Client Confirm Mobile Phone Confirmation Code

-   Request Type: `POST`
-   URL: `clients/confirm-mobile-number`
-   BodyParams : 
```json
{
   "confirmation_code" : 824059
}
```

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [{
        "id": 32,
        "uuid": "5b53b186-aaeb-4663-964e-e79a526ec951",
        "first_name": "Akil",
        "last_name": "Rajdho",
        "email": "akil.rajdho@gmail.com",
        "birthday": "1987-09-08",
        "is_active": true,
        "client": {
            "id": 24,
            "uuid": "db77f3ae-2320-41b9-ae71-3dfc3e15fc40",
            "phone": "355686060063",
            "mobile_phone": "+355686060063",
            "gender": "Male",
            "points": 19224,
            "is_confirmed": false,
            "is_phone_confirmed": false,
            "subscribe_to_menu": true,
            "subscribe_to_newsletter": true,
            "nr_of_orders": 193,
            "phone_confirmation_code": 236464
        },
        "photo_path": "uploads/users/32/1537196786_user_photo.jpeg",
        "photo_thmb_path": "uploads/users/32/tn-1537196786_user_photo.jpeg",
        "full_photo_path": "http://ecommerce-api.test/uploads/users/32/1537196786_user_photo.jpeg",
        "full_photo_thmb_path": "http://ecommerce-api.test/uploads/users/32/tn-1537196786_user_photo.jpeg",
        "addresses": [
            {
                "id": 1094,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            },
            {
                "id": 1095,
                "address": "Rruga Medar Shtylla",
                "city": "Tirane",
                "country": "Durresi",
                "zip_code": 1001,
                "is_default": true
            }
        ],
        "is_confirmed": false,
        "is_email_confirmed": false
    }],
    "errors": []
}
```
::: danger Failed Response :::

General Error
Confirmation code is incorrect
```json
{
    "message": "api.confirmation_code_incorrect",
    "status": 2000002,
    "data": [],
    "errors": [
        "api.confirmation_code_incorrect"
    ]
}
```
::: danger Failed Response :::

General Error
Email field is not included.
```json
{
    "message": "api.confirmation_code_incorrect",
    "status": 2000002,
    "data": [],
    "errors": [
        "api.confirmation_code_incorrect"
    ]
}
```

## Password Reset Functionality

The user can reset a forgotten password using two ways :
1- Mobile phone number
2- Email

In both scenarios the system only allows 5 password reset requests per 24 hours.
When the user requests a password reset the system sends a temp password to the users email or phone number. However the original password remains intact.
The temp password has an expiration time of 24 hours, after which a new password has to be generated.

-   Request Type: `POST`
-   URL: `api/reset-password`
-   BodyParams : email or mobile_phone (if both are passed mobile will be prioritized)

### Reset Via SMS
```json
{
   "mobile_phone" : "+355686060063"
}
```


::: tip Success Response :::

```json
{
    "message": "Passwordi i ri u dergua me sms",
    "status": 0,
    "data": [],
    "errors":[]
}
```

::: danger Failed Response :::


General Error
Confirmation code is incorrect

```json
{
    "message": "Ju nuk mund te kerkoni ndryshim passwordi me sms me shume se 5 here ne dite",
    "status": 1000004,
    "data": [],
    "errors": [
        "Ju nuk mund te kerkoni ndryshim fjalekalimi me sms me shume se 5 here ne dite"
    ]
}
```

Validation Error
Missing required parameters

```json
{
    "message": "Validation Failed",
    "status": 2,
    "data": [],
    "errors": [
        "Emaili ose numri i telefonit eshte i detyrueshem per te nderruar passwordin "
    ]
}
```


Resource not found
User not found

```json
{
    "message": "Entiteti i kërkuar nuk u gjet",
    "status": 1000005,
    "data": [],
    "errors": [
        "Nuk u gjet asnje perdorues me keto te dhena ne sistem"
    ]
}
```

### Reset Via email

```json
{
   "email" : "akilrajdho@gmail.com"
}
```
::: danger Failed Response :::


General Error
Confirmation code is incorrect

```json
{
    "message": "Ju nuk mund te kerkoni ndryshim passwordi me sms me shume se 5 here ne dite",
    "status": 1000004,
    "data": [],
    "errors": [
        "Ju nuk mund te kerkoni ndryshim fjalekalimi me sms me shume se 5 here ne dite"
    ]
}
```

Validation Error
Missing required parameters

```json
{
    "message": "Validation Failed",
    "status": 2,
    "data": [],
    "errors": [
        "Emaili ose numri i telefonit eshte i detyrueshem per te nderruar passwordin "
    ]
}
```

Resource not found
User not found

```json
{
    "message": "Entiteti i kërkuar nuk u gjet",
    "status": 1000005,
    "data": [],
    "errors": [
        "Nuk u gjet asnje perdorues me keto te dhena ne sistem"
    ]
}
```


## Business Sectors

-   Request Type: `GET`
-   URL: `/api/business-sectors`

::: tip Success Response :::
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
          "id": 1,
          "name": "Marketi"
        },
        {
          "id": 2,
          "name": "Restoranti"
        }
    ],
    "errors": []
}
```

## Product Groups (Categories)

-   Request Type: `GET`
-   URL: `/api/product-groups`
- Query params:
```text
 - depth | optional, number (to define the the hierarchy of categories)
```

::: tip Success Response :::
```json
{
    "message": "Sukses",
    "status": 0,
    "data": [
        {
            "id": 1,
            "name": "Grupi1",
            "description": "Grupi1",
            "parent_id": 0,
            "properties": [],
            "children": [
                {
                    "id": 2,
                    "name": "Grupi 1.1",
                    "description": "Grupi 1.1 sub",
                    "parent_id": 1,
                    "properties": [],
                    "children": [
                        {
                            "id": 3,
                            "name": "Grupi 1.1.1",
                            "description": "Grupi 1.1.1 sub sub",
                            "parent_id": 2,
                            "properties": [],
                            "children": []
                        }
                    ]
                }
            ]
        },
        {
            "id": 4,
            "name": "Grupi 2",
            "description": "a",
            "parent_id": 0,
            "properties": [
                {
                    "id": 1,
                    "product_group_id": 4,
                    "property": "test test cdascd",
                    "created_at": "2020-03-15 20:26:02",
                    "updated_at": "2020-03-15 20:26:02"
                },
                {
                    "id": 2,
                    "product_group_id": 4,
                    "property": "asc",
                    "created_at": "2020-03-15 20:26:02",
                    "updated_at": "2020-03-15 20:26:02"
                }
            ],
            "children": []
        }
    ],
    "errors": []
}
```


## Products

- Request Type: `GET`
- URL: `/api/products/`
- Query params:
```text
 - sector_id | optional
 - search_scope | optional in ('menu', 'greenhouse') 
 - group_id | optional  
 - qs | optional

 - ordering: ASC | DESC
 - sort_by: name | sale_price

 - page-size
 - page
```

::: tip Success Response Status code: 200 :::  
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 7,
            "code": null,
            "name": "Dardh",
            "description": null,
            "measurement_unit_id": 1,
            "measurement_unit": "COPE/KG",
            "min_sale_quantity": 1,
            "sale_price": 190,
            "old_price": 190,
            "total_quantity": 0,
            "groups": [
                {
                    "id": 3,
                    "name": "Fruta",
                    "description": "test2",
                    "properties": []
                }
            ],
            "image_default": null,
            "full_image_default": null,
            "photos": [],
            "full_photos": [],
            "rating": 5
        },
        {
            "id": 8,
            "code": null,
            "name": "Assd",
            "description": null,
            "measurement_unit_id": 1,
            "measurement_unit": "COPE/KG",
            "min_sale_quantity": 1,
            "sale_price": 190,
            "old_price": 190,
            "total_quantity": 0,
            "groups": [
                {
                    "id": 3,
                    "name": "Perime",
                    "description": "test2",
                    "properties": []
                }
            ],
            "image_default": null,
            "full_image_default": null,
            "photos": [],
            "full_photos": [],
            "rating": 5
        }
    ],
    "errors": []
}
```

## Similar Products
-   Request Type: `GET`
-   URL: `/api/products/{id}/similar`

::: tip Success Response :::  
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 4845,
            "code": null,
            "name": "AJKE QUMESHTI ME SPECA 560GR",
            "description": "Speca turshi,te trajtuara me ajke qumeshti,te fresketa,mjaft te shijshem 560gr",
            "measurement_unit_id": 1,
            "measurement_unit": "COPE/KG",
            "min_sale_quantity": 1,
            "sale_price": 190,
            "old_price": 190,
            "total_quantity": -1,
            "groups": [],
            "image_default": "uploads/products/4845/1536675141-1179915ad6159c7d0e0-5ad6159c7d132.png",
            "full_image_default": "uploads/products/4845/1536675141-1179915ad6159c7d0e0-5ad6159c7d132.png",
            "photos": [
                "uploads/products/4845/1536675141-1179915ad6159c7d0e0-5ad6159c7d132.png"
            ],
            "full_photos": [
                "uploads/products/4845/1536675141-1179915ad6159c7d0e0-5ad6159c7d132.png"
            ],
            "rating": 5
        },
        {
            "id": 5568,
            "code": "05568",
            "name": "AJKE QUMESHTI ME SPECA KORAL 1KG",
            "description": "Speca turshi,te trajtuara me ajke qumeshti,te fresketa,mjaft te shijshem 1kg",
            "measurement_unit_id": 1,
            "measurement_unit": "COPE/KG",
            "min_sale_quantity": 1,
            "sale_price": 190,
            "old_price": 190,
            "total_quantity": 2,
            "groups": [],
            "image_default": "uploads/products/5568/1540214957-images.jpg",
            "full_image_default": "uploads/products/5568/1540214957-images.jpg",
            "photos": [
                "uploads/products/5568/1540214957-images.jpg"
            ],
            "full_photos": [
                "uploads/products/5568/1540214957-images.jpg"
            ],
            "rating": 5
    ],
    "errors": []
}
```


## Same Group Products
-   Request Type: `GET`
-   URL: `/api/products/{id}/same-group`

::: tip Success Response :::  
```json
{
    "message": "SUCCESS",
    "status": 0,
    "data": [
        {
            "id": 2,
            "code": null,
            "name": "Kumbulla",
            "description": null,
            "measurement_unit_id": 1,
            "measurement_unit": "COPE/KG",
            "min_sale_quantity": 1,
            "sale_price": 190,
            "old_price": 190,
            "total_quantity": 0,
            "groups": [
                {
                    "id": 1,
                    "name": "product group po testoj 1",
                    "description": "test",
                    "properties": []
                }
            ],
            "image_default": null,
            "full_image_default": null,
            "photos": [],
            "full_photos": [],
            "rating": 5
        },
        {
            "id": 3,
            "code": null,
            "name": "Dardha",
            "description": null,
            "measurement_unit_id": 1,
            "measurement_unit": "COPE/KG",
            "min_sale_quantity": 1,
            "sale_price": 190,
            "old_price": 190,
            "total_quantity": 0,
            "groups": [
                {
                    "id": 2,
                    "name": "product group po testoj 2",
                    "description": "test2",
                    "properties": []
                }
            ],
            "image_default": null,
            "full_image_default": null,
            "photos": [],
            "full_photos": [],
            "rating": 5
        }
    ],
    "errors": []
}
```

::: danger Failed Response :::

General Error
There is no product with the given id
```json
{
    "message": "Resource not found",
    "status": 3,
    "data": [],
    "errors": "Product does not exist in our system"
}
```
