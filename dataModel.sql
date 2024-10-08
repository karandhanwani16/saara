-- Database Name: saara
--
-- #################
--
-- Table Name: Users
CREATE TABLE `users` (
    `user_id` bigint(20) NOT NULL,
    `user_email` varchar(300) NOT NULL,
    `user_password` varchar(300) NOT NULL,
    `user_first_name` varchar(200) NOT NULL,
    `user_last_name` varchar(200) NOT NULL,
    `user_type` varchar(200) NOT NULL,
    `user_last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    PRIMARY KEY (`user_id`)
);

-- Table Name: category
CREATE TABLE `category` (
    `category_id` bigint(20) NOT NULL,
    `category_name` varchar(500) NOT NULL,
    `category_created_by` bigint(20) NOT NULL,
    `category_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `category_updated_by` bigint(20) NOT NULL,
    `category_updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) --Table Name: logs
CREATE TABLE `logs` (
    `log_timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `action_type` varchar(200) NOT NULL,
    `log_action` varchar(200) NOT NULL,
    `log_user` varchar(200) NOT NULL,
    `log_description` varchar(7900) NOT NULL
) -- Table Name: products
create table `products` (
    `product_id` bigint(20) NOT NULL,
    `category_id` bigint(20) NOT NULL,
    `brand_id` bigint(20) NOT NULL,
    `product_name` varchar(500) NOT NULL,
    `product_barcode` varchar(500) NOT NULL,
    `product_quantity` varchar(500) NOT NULL,
    `product_code` varchar(500) NOT NULL,
    `product_cost_price` varchar(500) NOT NULL,
    `product_cgst_percentage` varchar(500) NOT NULL,
    `product_sgst_percentage` varchar(500) NOT NULL,
    `product_igst_percentage` varchar(500) NOT NULL,
    `product_cost_post_gst` varchar(500) NOT NULL,
    `product_selling_price` varchar(500) NOT NULL,
    `product_parlour_price` varchar(500) NOT NULL,
    `product_description` varchar(2000) NOT NULL,
    `product_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `product_created_by` bigint(20) NOT NULL,
    `product_updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `product_updated_by` bigint(20) NOT NULL
);

--Table Name: supplier
CREATE TABLE `supplier` (
    `supplier_id` bigint(20) NOT NULL,
    `supplier_name` varchar(500) NOT NULL,
    `supplier_location` varchar(500) NOT NULL,
    `supplier_mobile` varchar(500) NOT NULL,
    `supplier_description` varchar(500) NOT NULL,
    `supplier_created_by` bigint(20) NOT NULL,
    `supplier_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `supplier_updated_by` bigint(20) NOT NULL,
    `supplier_updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) --Table Name: brand
CREATE TABLE `brand` (
    `brand_id` bigint(20) NOT NULL,
    `brand_name` varchar(500) NOT NULL,
    `brand_created_by` bigint(20) NOT NULL,
    `brand_created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `brand_updated_by` bigint(20) NOT NULL,
    `brand_updated_at` timestamp NOT NULL DEFAULT current_timestamp()
)

-- Table Name: sale

CREATE TABLE `sales` (
    sale_id BIGINT(20) PRIMARY KEY,
    sale_date DATE,
    customer_name VARCHAR(255),
    customer_phone VARCHAR(20),
    customer_email VARCHAR(255),
    discount varchar(20),
    discount_type VARCHAR(20),
    cash_amount varchar(20),
    upi_amount varchar(20)
);

-- Table Name: sale_items

CREATE TABLE `sale_items` (
    sale_item_id BIGINT(20) PRIMARY KEY,
    sale_id BIGINT(20),
    product_id BIGINT(20),
    product_name VARCHAR(255),
    product_price_id BIGINT(20),
    product_price VARCHAR(20),
    quantity INT,
    discount VARCHAR(20),
    total VARCHAR(20),
    gst_percentage VARCHAR(20),
    is_igst VARCHAR(20),
    discount_type VARCHAR(20),
    FOREIGN KEY (sale_id) REFERENCES sales(sale_id)
);
