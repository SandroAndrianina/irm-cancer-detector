USE irm_cancer;

CREATE TABLE IF NOT EXISTS scans (
  id               INT AUTO_INCREMENT PRIMARY KEY,
  patient_ref      VARCHAR(100) NOT NULL,
  zone             VARCHAR(20)  NOT NULL DEFAULT 'brain',
  contrast_used    TINYINT(1)   NOT NULL DEFAULT 0,
  signal_intensity INT          NOT NULL DEFAULT 0,
  resultat         VARCHAR(20)  NOT NULL DEFAULT 'en_attente',
  created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO scans (patient_ref, zone, contrast_used, signal_intensity, resultat) VALUES
  ('PAT-001', 'brain',  0,  25, 'benin'),
  ('PAT-002', 'breast', 1,  85, 'malin'),
  ('PAT-003', 'liver',  1,  55, 'en_attente');