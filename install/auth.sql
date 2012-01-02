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

CREATE TABLE users
(
user_id INTEGER PRIMARY KEY,
username VARCHAR(100),
expiry DATETIME,
last_login DATETIME,
password VARCHAR(255),
state INTEGER
);

CREATE TABLE user_role
(
user_id INTEGER,
role_id INTEGER,
PRIMARY KEY (user_id, role_id)
);
