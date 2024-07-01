-- init.sql

CREATE TABLE account (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    account_number VARCHAR(25) NOT NULL
);

CREATE TABLE account_balance (
    account_id VARCHAR(255) NOT NULL PRIMARY KEY,
    amount INT NOT NULL
);

CREATE TABLE transaction (
    id VARCHAR(255) NOT NULL PRIMARY KEY,
    account_number VARCHAR(25) NOT NULL,
    amount INT NOT NULL,
    type ENUM('P', 'C', 'D') NOT NULL
);
