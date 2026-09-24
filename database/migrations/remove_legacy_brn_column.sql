-- Remove the retired BRN field from the legacy schema if that table exists.
-- The active Harvestly schema uses verification_documents for Courier Partner evidence.
SET @has_legacy_brn = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'courier_partners'
      AND COLUMN_NAME = 'brn_number'
);
SET @remove_legacy_brn = IF(
    @has_legacy_brn = 1,
    'ALTER TABLE courier_partners DROP COLUMN brn_number',
    'SELECT 1'
);
PREPARE remove_legacy_brn_statement FROM @remove_legacy_brn;
EXECUTE remove_legacy_brn_statement;
DEALLOCATE PREPARE remove_legacy_brn_statement;
