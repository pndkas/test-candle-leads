-- ================================================================
-- setup.sql — Create Database and Table for Lumière Candle Co.
--
-- How to use (MySQL Command Line):
--   mysql -u root -p < setup.sql
--
-- Or open MySQL shell and copy/paste the commands below sequentially
-- ================================================================

-- Create database (if it doesn't exist)
CREATE DATABASE IF NOT EXISTS candle_leads
    CHARACTER SET utf8mb4        -- Supports text and emojis
    COLLATE utf8mb4_unicode_ci;

-- Use this database
USE candle_leads;

-- Create leads table
CREATE TABLE IF NOT EXISTS leads (
    id         INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    name       VARCHAR(100)     NOT NULL,
    email      VARCHAR(254)     NOT NULL,
    scent      ENUM(
                   'Vanilla',
                   'Lavender',
                   'Sandalwood',
                   'Citrus',
                   'Unsure'
               )                NOT NULL,
    message    TEXT             NULL,
    created_at DATETIME         NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_email (email),          -- Speeds up query by email
    INDEX idx_created_at (created_at) -- Speeds up sorting by time
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;
