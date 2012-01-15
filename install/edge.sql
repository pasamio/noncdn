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
