-- ============================================
-- PCTO · Maturità 2026 — setup database
-- Da eseguire UNA volta sola (vedi istruzioni)
-- ============================================

CREATE DATABASE IF NOT EXISTS pcto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pcto;

-- Registro degli accessi alla presentazione
CREATE TABLE IF NOT EXISTS accessi (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  data_accesso DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);
