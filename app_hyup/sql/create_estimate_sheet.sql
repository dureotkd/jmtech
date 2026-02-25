-- estimate_sheet 테이블 생성 (견적서/수주서 시트 데이터 저장)
-- 사용: Estimate_service::create, update, delete

USE jmtech;

CREATE TABLE IF NOT EXISTS estimate_sheet (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  estimate_id INT UNSIGNED NOT NULL,
  sheets LONGTEXT,
  created_at DATETIME DEFAULT NULL,
  updated_at DATETIME DEFAULT NULL,
  KEY idx_estimate_id (estimate_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
