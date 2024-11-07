<?php

class Product
{
    public $id_producte;
    public $nom;
    public $descripcio_curta;
    public $descripcio_llarga;
    public $imatge;
    public $preu;
    public $usuari_id;
    public $observacions;
    public $status;
    public $likesCount = 0;
    public $hasLiked = false;
    public $subhastaNom;


    public static function createProduct(PDO $pdo, string $nom, string $descripcioCurta, string $descripcioLlarga, ?string $imatge, float $preu, int $usuari_id, ?string $observacions): bool
    {
        $stmt = $pdo->prepare("INSERT INTO product (nom, descripcio_curta, descripcio_llarga, imatge, preu, usuari_id, observacions) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$nom, $descripcioCurta, $descripcioLlarga, $imatge, $preu, $usuari_id, $observacions]);
    }

    public static function getAllProducts(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT p.id_producte, p.nom, p.descripcio_curta, p.descripcio_llarga, p.imatge, p.preu, u.username, p.observacions, p.status 
                               FROM product p 
                               JOIN usuari u WHERE p.usuari_id = u.id_usuari");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function getProductsDashboard(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT p.id_producte, p.nom, p.imatge, p.preu 
                           FROM product p WHERE p.status != 'retirat' AND p.status != 'rebutjat'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function getProductsByDepuracio($pdo, $search, $order, $limit, $offset)
    {
        $stmt = $pdo->prepare('SELECT * FROM product WHERE nom LIKE :search ORDER BY ' . $order . ' LIMIT :limit OFFSET :offset');
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public static function getProductsByStatusPendentAssignacio(PDO $pdo): array
    {
        $stmt = $pdo->prepare("
        SELECT p.id_producte, p.nom, p.preu
        FROM product p
        JOIN usuari u ON p.usuari_id = u.id_usuari
        WHERE p.status = 'pendent-assignacio'
        ");

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getProductsByUserId(PDO $pdo, int $userId): array
    {
        $stmt = $pdo->prepare("SELECT * FROM product WHERE usuari_id = :usuari_id AND status != 'retirat'");
        $stmt->execute(['usuari_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function deleteProductById(PDO $pdo, int $productId): bool
    {
        $stmt = $pdo->prepare("UPDATE product SET status = 'retirat' WHERE id_producte = :id_producte");
        return $stmt->execute(['id_producte' => $productId]);
    }

    public static function updateProduct(PDO $pdo, int $product_id, array $productData): bool
    {
        $stmt = $pdo->prepare("UPDATE product SET nom = ?, descripcio_curta = ?, descripcio_llarga = ?, imatge = ?, preu = ?, usuari_id = ?, observacions = ? WHERE id_producte = ?");
        return $stmt->execute([
            $productData['nom'],
            $productData['descripcio_curta'],
            $productData['descripcio_llarga'],
            $productData['imatge'],
            $productData['preu'],
            $productData['usuari_id'],
            $productData['observacions'],
            $product_id
        ]);
    }

    public static function updateStatus(PDO $pdo, int $product_id, string $status): bool
    {
        $stmt = $pdo->prepare("UPDATE product SET status = ? WHERE id_producte = ?");
        return $stmt->execute([$status, $product_id]);
    }

    public static function getCreatorId(PDO $pdo, int $product_id): int
    {
        $stmt = $pdo->prepare("SELECT usuari_id FROM product WHERE id_producte = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetchColumn();
    }

    public static function updateDescriptions(PDO $pdo, int $product_id, string $nom, string $descripcioCurta, string $descripcioLlarga): bool
    {
        $stmt = $pdo->prepare("UPDATE product SET nom = ?, descripcio_curta = ?, descripcio_llarga = ? WHERE id_producte = ?");
        return $stmt->execute([$nom, $descripcioCurta, $descripcioLlarga, $product_id]);
    }


    public static function getProductById(PDO $pdo, int $product_id): ?stdClass
    {
        $stmt = $pdo->prepare("SELECT * FROM product WHERE id_producte = ?");
        $stmt->execute([$product_id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getPendingProducts(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT p.id_producte, p.nom, p.descripcio_curta, p.descripcio_llarga, p.imatge, p.preu, u.username, p.observacions, p.status 
                               FROM product p 
                               JOIN usuari u ON p.usuari_id = u.id_usuari
                               WHERE p.status = 'pendent'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getPendingCount(PDO $pdo): int
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) as pending_count FROM product WHERE status = 'pendent'");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['pending_count'];
    }

    public static function searchProducts(PDO $pdo, string $search = '', string $order = 'nom'): array
    {
        try {
            $sql = "SELECT * FROM product WHERE nom LIKE :search ORDER BY $order";
            $statement = $pdo->prepare($sql);
            $statement->execute(['search' => '%' . $search . '%']);

            return $statement->fetchAll(PDO::FETCH_CLASS, "Product");
        } catch (PDOException $ex) {
            // TODO: Log de l'error
            header("Location: index.php?controller=error");
        } catch (Exception $ex) {
            // TODO: Log de l'error
            header("Location: index.php?controller=error");
        }

        return [];
    }


    // Funcions de m'agrada el producte

    public static function likeProduct(PDO $pdo, int $productId, int $userId): bool
    {
        $stmt = $pdo->prepare("INSERT INTO product_likes (product_id, user_id) VALUES (:product_id, :user_id)");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function unlikeProduct(PDO $pdo, int $productId, int $userId): bool
    {
        $stmt = $pdo->prepare("DELETE FROM product_likes WHERE product_id = :product_id AND user_id = :user_id");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function hasUserLiked(PDO $pdo, int $productId, int $userId): bool
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_likes WHERE product_id = ? AND user_id = ?");
        $stmt->execute([$productId, $userId]);
        return (bool) $stmt->fetchColumn();
    }

    public static function getLikesCount(PDO $pdo, int $productId): int
    {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM product_likes WHERE product_id = ?");
        $stmt->execute([$productId]);
        return (int) $stmt->fetchColumn();
    }
    public static function incrementLikesCount(PDO $pdo, int $productId): bool
    {
        $stmt = $pdo->prepare("INSERT INTO product_likes (product_id, user_id) VALUES (:product_id, 0)");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function decrementLikesCount(PDO $pdo, int $productId): bool
    {
        $stmt = $pdo->prepare("DELETE FROM product_likes WHERE product_id = :product_id AND user_id = 0");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function getLikedProductsByUser(PDO $pdo, int $userId): array
    {
        $stmt = $pdo->prepare("
            SELECT p.*,pl.*
            FROM product p
            JOIN product_likes pl ON p.id_producte = pl.product_id
            WHERE pl.user_id = :user_id
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    // Ordenar pagines
    public static function searchProductsPaginated(PDO $pdo, string $search = '', string $order = 'nom', int $limit = 9, int $offset = 0): array
    {
        try {
            $stmt = $pdo->prepare('SELECT * FROM product WHERE status NOT IN ("retirat", "pendent") AND nom LIKE :search ORDER BY ' . $order . ' LIMIT :limit OFFSET :offset');
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            header("Location: index.php?controller=error");
        } catch (Exception $ex) {
            header("Location: index.php?controller=error");
        }
        return [];
    }


    public static function getTotalProductsCount($pdo, $search)
    {
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM product WHERE nom LIKE :search');
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }


}
