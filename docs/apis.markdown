List of API's
==============


Authentication API - LDAP, SSO
Authorisation API - Moodle? (existing custom USQ API)

Distributed authentication?

File Management:

- get file list
- add/edit/delete file
- retrieve file (relies upon authentication API/authorisation API)



Distributed Authorisation/Authentication API - token validator:

- create token for service
- validate token


Deduplication layer also handles file auditing
Two levels of auditing:

 - first level is for access to the real item
 - second level is for access to the virtual item



# Edge Node API Notes


Edge Node Download:
http://edge.noncdn.org/auth/[user]/[token]/container/path/to/file


Token Composition (MD5:38 char/SHA1: 46 char):

- edge identifier in base 36 (2 char for up to 1296 server [0 index; 0 pad])
- date + hour (ymdH) in base 36 (5 chars)
- MD5 or SHA1 encoded username, date + hour, edge shared secret (token validation) (32 chars/40 chars)



Note: 

- Get binary (raw output, second arg to md5/sha1) and base64_encode instead of hex to save space
- Base 36 to compress characters by 3 in date + hour for URL
- edge identifier is a unique number in base 36, one character for up to 35 distinct nodes


## API

#### /auth/user/token/container/path/to/file
- user: username
- token: request validation token
- container: logical container for content
- path/to/file: path to the file within the logical container

#### /server/invalidate_content
- container: Container to invalidate
- filepath: File path to invalidate (can utilise a regexp, or exact path: /path/to/file) [Optional]
Note: if filepath is not specified the entire container is invalidated

#### /server/invalidate_user
- username: Username to invalidate. Any cached information is purged.

#### /server/invalidate_authorisations
- container: Container to invalidate, if not specified all authorisations are invalidated [Optional]



# Master Node API Notes


## API
#### /content/get_content_id
- container
- path/to/file

Response

- file unqiue ID (deduplified identifier)


#### /content/get_content
- file_unique_id: file unique ID

Response

- raw file contents


NOTE: Send content map for file? As headers?
|-> what if a lot of replicas? top 5?


#### /content/get_content_map
- file unique id
- max age - max age in days
- age field - age validation field; last modified date, last accessed date

Response

- replicas: replica map


#### /user/get_user_authorisations
- username
- container - search term to limit (if more than 20 ACLs; OPTIONAL)
- offset - Used for paging
- page_size - number of entries in a page

Response:

- authorisations: array of authorisations


#### /manage/data/container/path/to/file
- Auth: Header (Basic, Token, OAuth)
- Manage file contents honouring HTTP verbs (GET, PUT, DELETE)

#### /manage/webdav/container/path/to/file
- Auth: Header
- WebDAV interface to files


#### /manage/users/username(.json|.xml|.html)
- Manage a given user (GET to retrieve, PUT to replace, POST to modify fields, DELETE to remove)

#### /content/task/container/path
- /content/file/container/path/to/file
- /content/basic/container/path/to/file
- /content/oauth/container/path/to/file

Break Down:

- Controller: content
- Task: file|basic|oauth - maps to authentication type
- container: container identifier
- path/to/file: path to the file

May redirect the user to:

- edge node with an authentication token
- to an SSO server for authentication

#### /user/login - Login entry point
- username
- password/token
- OPTIONAL: if suffix (.json|.xml|.html) then result is returned per format

Response:

- session token
- cookie is set


# Auth Node API
Handles both authentication and authorisation requests. An individual node may implement only authentication or only authorisation checks. Unimplemented checks should return the default response for the API call.



Authentication:

 - /user/validate_credentials


Authorisation:

 - /user/get_roles
 - /container/check_access
 - /container/get_roles

#### /user/validate_credentials
- credentials: an associative array of credentials to validate (e.g. username and password, username and token, etc)

Response:

- boolean result of request
- status code/message for more details

The default return value for this should be false.

#### /user/get_roles
- username: username to get the roles from

Responses:

- array of roles

The default return value for this should be an empty array.

#### /container/check_role_access
- container: Container to validate access against
- roles: List of roles to check if are available

Response:

- check for access: can be one of three states:
  - unknown: does not permit or deny access
  - deny: refuse access to the resource
  - allow: permit access to the resource
- status code/message for more details

Any deny is a hard deny. Access checks stop at that point. Multiple access checks can be layered. The default is a deny.


#### /container/get_roles
- container: Container to validate access

Response:

- array of roles split into two categories:
  - permit roles: roles that permit access to this container
  - deny roles: roles that deny access to this container

Note: any deny is a hard deny. Access checks against a resource that has a deny role should deny access.


#### /container/check_user_access
- container: Container to validate access against
- username: A username to check access against

Response:

- check for access: can be one of three states:
  - unknown: does not permit or deny access
  - deny: refuse access to the resource
  - allow: permit access to the resource
- status code/message for more details

Any deny is a hard deny. Access checks stop at that point. Multiple access checks can be layered. The default is a deny.