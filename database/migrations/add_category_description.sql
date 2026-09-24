-- Add the assessed Product Categories description field without replacing existing data.
ALTER TABLE product_categories
    ADD COLUMN description VARCHAR(500) NULL AFTER category_name;
