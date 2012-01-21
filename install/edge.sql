CREATE TABLE container_file
(
file_id INTEGER,
container_name VARCHAR(100),
fullpath VARCHAR(355),
PRIMARY KEY(file_id, container_name, fullpath)
);

CREATE TABLE files
(
file_id INTEGER PRIMARY KEY,
file_hash VARCHAR(100),
file_size INTEGER,
use_count INTEGER,
file_mime VARCHAR(100),
last_access DATE
);

CREATE TABLE user_role
(
user_name VARCHAR(100),
role_id INTEGER,
PRIMARY KEY (user_name, role_id)
);

CREATE TABLE roles
(
role_id INTEGER PRIMARY KEY,
role_name VARCHAR(100),
admin_username VARCHAR(100)
);

CREATE TABLE container_role
(
container_name VARCHAR(100),
role_id INTEGER,
type VARCHAR(50),
PRIMARY KEY (container_name, role_id)
);
