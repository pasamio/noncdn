CREATE TABLE container_file
(
file_id INTEGER,
container_name VARCHAR(100),
fullpath VARCHAR(355),
PRIMARY KEY(file_id, container_name, fullpath)
);
