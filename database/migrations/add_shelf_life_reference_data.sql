-- Migration: Add scientific shelf-life reference columns and seed data
-- Source: Rajapaksha L, Gunathilake DMCC, Pathirana SM, et al. Reducing post-harvest losses in fruits and vegetables for ensuring food security – Case of Sri Lanka. MOJ Food Processing & Technology. 2021;9(1):7–16. Table 1.

USE harvestly;

-- 1. Safely add missing columns to shelf_life_references table if not existing
ALTER TABLE shelf_life_references
ADD COLUMN IF NOT EXISTS temperature_min_c DECIMAL(5,2) NULL AFTER product_reference_name,
ADD COLUMN IF NOT EXISTS temperature_max_c DECIMAL(5,2) NULL AFTER temperature_min_c,
ADD COLUMN IF NOT EXISTS humidity_min_percent DECIMAL(5,2) NULL AFTER temperature_max_c,
ADD COLUMN IF NOT EXISTS humidity_max_percent DECIMAL(5,2) NULL AFTER humidity_min_percent,
ADD COLUMN IF NOT EXISTS storage_life_min_days SMALLINT UNSIGNED NULL AFTER humidity_max_percent,
ADD COLUMN IF NOT EXISTS storage_life_max_days SMALLINT UNSIGNED NULL AFTER storage_life_min_days;

-- 2. Insert or update the 25 scientific shelf-life reference records (Rajapaksha et al. 2021 Table 1)
INSERT INTO shelf_life_references 
(product_reference_name, temperature_min_c, temperature_max_c, humidity_min_percent, humidity_max_percent, storage_life_min_days, storage_life_max_days, default_shelf_life_days, storage_guidance, source_reference, is_active)
VALUES
('Avocado', 3.00, 13.00, 85.00, 90.00, 14, 56, 56, 'Recommended reference storage: 3–13°C, 85–90% relative humidity. Reference storage life: 14–56 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Banana - Plantain', 13.00, 15.00, 90.00, 95.00, 7, 28, 28, 'Recommended reference storage: 13–15°C, 90–95% relative humidity. Reference storage life: 7–28 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Beet (topped)', 0.00, 0.00, 98.00, 100.00, 120, 180, 180, 'Recommended reference storage: 0°C, 98–100% relative humidity. Reference storage life: 120–180 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Chinese Cabbage', 0.00, 0.00, 95.00, 100.00, 60, 90, 90, 'Recommended reference storage: 0°C, 95–100% relative humidity. Reference storage life: 60–90 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Eggplant', 8.00, 12.00, 90.00, 95.00, 7, 7, 7, 'Recommended reference storage: 8–12°C, 90–95% relative humidity. Reference storage life: 7 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Ginger', 13.00, 13.00, 65.00, 65.00, 180, 180, 180, 'Recommended reference storage: 13°C, 65% relative humidity. Reference storage life: 180 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Guava', 5.00, 10.00, 90.00, 90.00, 14, 21, 21, 'Recommended reference storage: 5–10°C, 90% relative humidity. Reference storage life: 14–21 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Leek', 0.00, 0.00, 95.00, 100.00, 60, 90, 90, 'Recommended reference storage: 0°C, 95–100% relative humidity. Reference storage life: 60–90 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Lemon', 10.00, 13.00, 85.00, 90.00, 30, 180, 180, 'Recommended reference storage: 10–13°C, 85–90% relative humidity. Reference storage life: 30–180 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Lime', 9.00, 10.00, 85.00, 90.00, 42, 56, 56, 'Recommended reference storage: 9–10°C, 85–90% relative humidity. Reference storage life: 42–56 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Mango', 13.00, 13.00, 90.00, 95.00, 14, 21, 21, 'Recommended reference storage: 13°C, 90–95% relative humidity. Reference storage life: 14–21 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Melon', 7.00, 10.00, 90.00, 95.00, 12, 21, 21, 'Recommended reference storage: 7–10°C, 90–95% relative humidity. Reference storage life: 12–21 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Okra', 7.00, 10.00, 90.00, 95.00, 7, 10, 10, 'Recommended reference storage: 7–10°C, 90–95% relative humidity. Reference storage life: 7–10 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Onions (dry)', 20.00, 25.00, 65.00, 70.00, 30, 240, 240, 'Recommended reference storage: 20–25°C, 65–70% relative humidity. Reference storage life: 30–240 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Orange', 0.00, 9.00, 85.00, 90.00, 56, 84, 84, 'Recommended reference storage: 0–9°C, 85–90% relative humidity. Reference storage life: 56–84 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Papaya', 7.00, 13.00, 85.00, 90.00, 7, 21, 21, 'Recommended reference storage: 7–13°C, 85–90% relative humidity. Reference storage life: 7–21 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Cucumber', 5.00, 10.00, 95.00, 95.00, 28, 28, 28, 'Recommended reference storage: 5–10°C, 95% relative humidity. Reference storage life: 28 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Bell Pepper', 7.00, 13.00, 90.00, 95.00, 14, 21, 21, 'Recommended reference storage: 7–13°C, 90–95% relative humidity. Reference storage life: 14–21 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Pineapple', 7.00, 13.00, 85.00, 90.00, 14, 28, 28, 'Recommended reference storage: 7–13°C, 85–90% relative humidity. Reference storage life: 14–28 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Potato (early)', 7.00, 13.00, 90.00, 95.00, 10, 14, 14, 'Recommended reference storage: 7–13°C, 90–95% relative humidity. Reference storage life: 10–14 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Radish', 0.00, 0.00, 95.00, 95.00, 21, 21, 21, 'Recommended reference storage: 0°C, 95% relative humidity. Reference storage life: 21 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Spinach', 0.00, 0.00, 95.00, 100.00, 10, 14, 14, 'Recommended reference storage: 0°C, 95–100% relative humidity. Reference storage life: 10–14 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Sweet Potato', 13.00, 15.00, 85.00, 90.00, 120, 210, 210, 'Recommended reference storage: 13–15°C, 85–90% relative humidity. Reference storage life: 120–210 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Watermelon', 10.00, 15.00, 90.00, 90.00, 14, 21, 21, 'Recommended reference storage: 10–15°C, 90% relative humidity. Reference storage life: 14–21 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1),
('Yam', 16.00, 16.00, 70.00, 80.00, 60, 210, 210, 'Recommended reference storage: 16°C, 70–80% relative humidity. Reference storage life: 60–210 days under recommended conditions.', 'Rajapaksha et al. (2021), Table 1', 1)
ON DUPLICATE KEY UPDATE
temperature_min_c = VALUES(temperature_min_c),
temperature_max_c = VALUES(temperature_max_c),
humidity_min_percent = VALUES(humidity_min_percent),
humidity_max_percent = VALUES(humidity_max_percent),
storage_life_min_days = VALUES(storage_life_min_days),
storage_life_max_days = VALUES(storage_life_max_days),
default_shelf_life_days = VALUES(default_shelf_life_days),
storage_guidance = VALUES(storage_guidance),
source_reference = VALUES(source_reference),
is_active = 1;

-- 3. Safely link explicit sample products to shelf_life_references
UPDATE products p
JOIN shelf_life_references r ON r.product_reference_name = 'Mango'
SET p.shelf_life_reference_id = r.shelf_life_reference_id
WHERE p.product_name LIKE '%Mango%';
