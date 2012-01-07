CREATE TABLE containers
(
container_id INTEGER PRIMARY KEY,
container_name VARCHAR(100),
description TEXT,
expiry DATETIME,
last_access DATETIME,
UNIQUE (container_name)
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


CREATE TABLE upload_queue
(
temp_filepath VARCHAR(255),
container_id INTEGER,
destination VARCHAR(255),
add_date DATETIME
);


CREATE TABLE container_file
(
container_id INTEGER,
file_id INTEGER,
path VARCHAR(255),
filename VARCHAR(100),
PRIMARY KEY(container_id, file_id, path)
);