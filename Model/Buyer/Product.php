<?php

declare(strict_types=1);

final class Product
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = db();
    }


    /*
    |--------------------------------------------------------------------------
    | Normalize product
    |--------------------------------------------------------------------------
    */

    private function normalizeProduct(array $product): array
    {
        if (
            mb_strtolower(
                trim((string)($product['name'] ?? ''))
            ) === 'coconut'
        ) {

            $product['image'] =
                url('assets/coconut-sri-lanka.jpeg');
        }

        return $product;
    }


    /*
    |--------------------------------------------------------------------------
    | Map product
    |--------------------------------------------------------------------------
    */

    private function map(array $product): array
    {
        $product['id'] =
            (int)($product['id'] ?? 0);

        /*
        | Farmer ID
        | Used by View Store button
        */

        $product['farmer_id'] =
            (int)($product['farmer_id'] ?? 0);


        $product['price'] =
            (float)($product['price'] ?? 0);

        $product['rating'] =
            (float)($product['rating'] ?? 0);

        $product['reviews'] =
            (int)($product['reviews'] ?? 0);

        $product['fresh'] =
            (bool)($product['fresh'] ?? false);

        $product['organic'] =
            (bool)($product['organic'] ?? false);

        $product['stock'] =
            (int)($product['stock'] ?? 0);

        $product['farmer_rating'] =
            (float)($product['farmer_rating'] ?? 0);


        $product['images'] =
            !empty($product['image'])
                ? [$product['image']]
                : [];


        $product['farmerImage'] = '';


        return $this->normalizeProduct($product);
    }


    /*
    |--------------------------------------------------------------------------
    | Get all products
    |--------------------------------------------------------------------------
    */

    public function getAllProducts(): array
    {
        $stmt = $this->db->query(
            'SELECT
                p.*,

                (
                    SELECT fp.farmer_id

                    FROM farmer_profiles fp

                    INNER JOIN users u
                        ON u.user_id = fp.farmer_id

                    WHERE
                        fp.farm_name = p.farm
                        OR u.full_name = p.farmer

                    LIMIT 1

                ) AS farmer_id

             FROM products p

             ORDER BY
                p.created_at DESC,
                p.id DESC'
        );


        return array_map(
            fn(array $row) =>
                $this->map($row),
            $stmt->fetchAll()
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Get one product
    |--------------------------------------------------------------------------
    */

    public function getProductById(int $id): ?array
    {
        /*
        |--------------------------------------------------------------------------
        | First get the product
        |--------------------------------------------------------------------------
        */

        $stmt = $this->db->prepare(
            'SELECT *
             FROM products
             WHERE id = ?
             LIMIT 1'
        );

        $stmt->execute([$id]);

        $product = $stmt->fetch();


        if (!$product) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Find related farmer
        |--------------------------------------------------------------------------
        |
        | Match either:
        |
        | products.farm
        |        -> farmer_profiles.farm_name
        |
        | OR
        |
        | products.farmer
        |        -> users.full_name
        |
        */

        $farmerStmt = $this->db->prepare(
            'SELECT
                fp.farmer_id

             FROM farmer_profiles fp

             INNER JOIN users u
                ON u.user_id = fp.farmer_id

             WHERE
                fp.farm_name = ?
                OR u.full_name = ?

             LIMIT 1'
        );


        $farmerStmt->execute([

            (string)($product['farm'] ?? ''),

            (string)($product['farmer'] ?? '')

        ]);


        $farmer = $farmerStmt->fetch();


        /*
        |--------------------------------------------------------------------------
        | Add farmer_id to product
        |--------------------------------------------------------------------------
        */

        $product['farmer_id'] =
            $farmer
                ? (int)$farmer['farmer_id']
                : 0;


        return $this->map($product);
    }


    /*
    |--------------------------------------------------------------------------
    | Get farmer store
    |--------------------------------------------------------------------------
    */

    public function getFarmerStore(string $farmer): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT
                p.*,

                (
                    SELECT fp.farmer_id

                    FROM farmer_profiles fp

                    INNER JOIN users u
                        ON u.user_id = fp.farmer_id

                    WHERE
                        fp.farm_name = p.farm
                        OR u.full_name = p.farmer

                    LIMIT 1

                ) AS farmer_id

             FROM products p

             WHERE p.farmer = ?

             ORDER BY
                p.created_at DESC,
                p.id DESC'
        );


        $stmt->execute([$farmer]);


        $rows = $stmt->fetchAll();


        if (!$rows) {
            return null;
        }


        $products = array_map(
            fn(array $row) =>
                $this->map($row),
            $rows
        );


        $ratings = array_values(
            array_filter(
                array_map(
                    fn(array $row) =>
                        (float)(
                            $row['farmer_rating'] ?? 0
                        ),
                    $products
                ),

                fn(float $rating) =>
                    $rating > 0
            )
        );


        $first = $products[0];


        $rating = $ratings
            ? array_sum($ratings) / count($ratings)
            : (float)$first['rating'];


        return [

            'name' =>
                $first['farmer'],

            'farmer_id' =>
                (int)(
                    $first['farmer_id'] ?? 0
                ),

            'rating' =>
                round($rating, 1),

            'district' =>
                $first['farm']
                ?: 'Sri Lanka',

            'farm' =>
                $first['farm']
                ?: '',

            'experience' =>
                $first['experience']
                ?: 'Local Farmer',

            'delivery' =>
                $first['delivery']
                ?: 'Delivery available',

            'products' =>
                $products,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO products
            (
                name,
                price,
                unit,
                farmer,
                rating,
                reviews,
                fresh,
                organic,
                stock,
                image,
                description,
                harvest_date,
                farm,
                farmer_rating,
                experience,
                delivery
            )

            VALUES
            (
                ?,
                ?,
                ?,
                ?,
                0,
                0,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                ?,
                0,
                ?,
                ?
            )'
        );


        $stmt->execute([

            trim(
                (string)$data['name']
            ),

            max(
                0,
                (float)$data['price']
            ),

            trim(
                (string)$data['unit']
            ),

            trim(
                (string)$data['farmer']
            ),

            !empty($data['fresh'])
                ? 1
                : 0,

            !empty($data['organic'])
                ? 1
                : 0,

            max(
                0,
                (int)($data['stock'] ?? 0)
            ),

            trim(
                (string)(
                    $data['image'] ?? ''
                )
            ),

            trim(
                (string)(
                    $data['description'] ?? ''
                )
            ),

            trim(
                (string)(
                    $data['harvest_date']
                    ?? 'Today'
                )
            ),

            trim(
                (string)(
                    $data['farm']
                    ?? $data['farmer']
                )
            ),

            trim(
                (string)(
                    $data['experience'] ?? ''
                )
            ),

            trim(
                (string)(
                    $data['delivery'] ?? ''
                )
            )

        ]);


        return (int)$this->db->lastInsertId();
    }


    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        int $id,
        array $data
    ): bool {

        $stmt = $this->db->prepare(
            'UPDATE products

             SET
                name = ?,
                price = ?,
                unit = ?,
                farmer = ?,
                stock = ?,
                image = ?,
                description = ?,
                organic = ?,
                fresh = ?,
                harvest_date = ?,
                farm = ?,
                experience = ?,
                delivery = ?

             WHERE id = ?'
        );


        return $stmt->execute([

            trim(
                (string)$data['name']
            ),

            max(
                0,
                (float)$data['price']
            ),

            trim(
                (string)$data['unit']
            ),

            trim(
                (string)$data['farmer']
            ),

            max(
                0,
                (int)$data['stock']
            ),

            trim(
                (string)(
                    $data['image'] ?? ''
                )
            ),

            trim(
                (string)(
                    $data['description'] ?? ''
                )
            ),

            !empty($data['organic'])
                ? 1
                : 0,

            !empty($data['fresh'])
                ? 1
                : 0,

            trim(
                (string)(
                    $data['harvest_date']
                    ?? 'Today'
                )
            ),

            trim(
                (string)(
                    $data['farm']
                    ?? $data['farmer']
                )
            ),

            trim(
                (string)(
                    $data['experience'] ?? ''
                )
            ),

            trim(
                (string)(
                    $data['delivery'] ?? ''
                )
            ),

            $id

        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Delete
    |--------------------------------------------------------------------------
    */

    public function delete(int $id): bool
    {
        try {

            $stmt = $this->db->prepare(
                'DELETE FROM products
                 WHERE id = ?'
            );


            return $stmt->execute([$id]);

        } catch (Throwable $e) {

            return false;
        }
    }
}