-- Database Name: saara
--
-- #################
--
-- Table Name: Users
CREATE TABLE 'users' (
    'user_id' bigint(20) NOT NULL,
    'user_email' varchar(300) NOT NULL,
    'user_password' varchar(300) NOT NULL,
    'user_first_name' varchar(200) NOT NULL,
    'user_last_name' varchar(200) NOT NULL,
    'user_type' varchar(200) NOT NULL,
    'user_last_login' timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY ('user_id')
);