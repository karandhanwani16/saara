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

-- Table Name: category
	CREATE TABLE 'category' (
 'category_id' bigint(20) NOT NULL,
 'category_name' varchar(500) NOT NULL,
 'category_created_by' bigint(20) NOT NULL,
 'category_created_at' timestamp NOT NULL DEFAULT current_timestamp(),
 'category_updated_by' bigint(20) NOT NULL,
 'category_updated_at' timestamp NOT NULL DEFAULT current_timestamp()
)
