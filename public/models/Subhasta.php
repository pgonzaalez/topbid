<?php

class Subhasta
{
    public $id_subhasta;
    public $nom;
    public $date_hora;
    public $lloc;
    public $descripcio;
    public $percentatge;
    public $inici;

    public function save(PDO $pdo): bool
    {
        try {
            $sql = "INSERT INTO subhasta (nom, date_hora, lloc, descripcio, percentatge) VALUES (:nom, :date_hora, :lloc, :descripcio, :percentatge)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':nom', $this->nom);
            $statement->bindParam(':date_hora', $this->date_hora);
            $statement->bindParam(':lloc', $this->lloc);
            $statement->bindParam(':descripcio', $this->descripcio);
            $statement->bindParam(':percentatge', $this->percentatge);

            return $statement->execute();
        } catch (PDOException $ex) {
            return false;
        }
    }

    public static function getAllSubhastes(PDO $pdo): array
    {
        $query = "SELECT * FROM subhasta";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public static function getSubhastaSenseIniciar(PDO $pdo): array
    {
        $query = "SELECT * FROM subhasta WHERE inici = 'senseIniciar'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getAllSubhastesSenseFeroIniciades(PDO $pdo): array
    {
        $query = "SELECT * FROM subhasta WHERE inici = 'senseIniciar' OR inici = 'iniciat'";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }



    public static function addProducts(PDO $pdo, int $id_subhasta, array $productes): bool
    {
        try {
            $pdo->beginTransaction();

            $stmtInsert = $pdo->prepare("INSERT INTO subhasta_producte (id_subhasta, id_producte) VALUES (?, ?)");
            $stmtUpdate = $pdo->prepare("UPDATE product SET status = 'asignat' WHERE id_producte = ?");

            foreach ($productes as $id_producte) {
                $stmtInsert->execute([$id_subhasta, $id_producte]);
                $stmtUpdate->execute([$id_producte]);
            }

            $pdo->commit();
            return true;
        } catch (PDOException $ex) {
            $pdo->rollBack();
            return false;
        }
    }

    public static function iniciaSubhasta(PDO $pdo, int $id_subhasta): void
    {
        $stmt = $pdo->prepare("UPDATE subhasta SET inici = 'iniciat' WHERE id_subhasta = ?");
        $stmt->execute([$id_subhasta]);
    }

    public static function getSubhastaById(PDO $pdo, int $id_subhasta): ?stdClass
    {
        $stmt = $pdo->prepare("SELECT * FROM subhasta WHERE id_subhasta = ?");
        $stmt->execute([$id_subhasta]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public static function getSubhastaNameByProductId(PDO $pdo, int $productId): ?string
    {
        $stmt = $pdo->prepare("
            SELECT s.nom
            FROM subhasta s
            JOIN subhasta_producte sp ON s.id_subhasta = sp.id_subhasta
            WHERE sp.id_producte = :product_id
        ");
        $stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function getProductForSubhasta(PDO $pdo, int $id_subhasta): array
    {
        $stmt = $pdo->prepare("SELECT p.id_producte, p.nom, p.preu, p.imatge, u.username
                               FROM product p
                               INNER JOIN subhasta_producte sp ON p.id_producte = sp.id_producte
                               JOIN usuari u ON p.usuari_id = u.id_usuari
                               WHERE sp.id_subhasta = ?");
        $stmt->execute([$id_subhasta]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function visualitzaSubhastaIniciada($pdo, $id_subhasta)
    {
        $stmt = $pdo->prepare("
            SELECT p.id_producte, p.nom, p.preu, p.imatge, u.username, sp.comprador_id, sp.preu_final, r.username AS receptor_username
            FROM product p
            JOIN usuari u ON p.usuari_id = u.id_usuari
            JOIN subhasta_producte sp ON p.id_producte = sp.id_producte
            LEFT JOIN usuari r ON sp.comprador_id = r.id_usuari
            WHERE sp.id_subhasta = :id_subhasta
        ");
        $stmt->execute(['id_subhasta' => $id_subhasta]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getEstatComptes(PDO $pdo, int $id_subhasta): array
    {
        try {
            $stmt = $pdo->prepare("
            SELECT 
                u.username AS usuari,
                SUM(CASE WHEN sp.comprador_id = u.id_usuari THEN -sp.preu_final ELSE 0 END) AS guany_comprador,
                SUM(CASE WHEN p.usuari_id = u.id_usuari THEN ROUND(sp.preu_final * (1 - s.percentatge / 100), 2) ELSE 0 END) AS guany_propietari,
                SUM(
                    CASE 
                        WHEN sp.comprador_id = u.id_usuari THEN -sp.preu_final
                        ELSE 0 
                    END
                ) + 
                SUM(
                    CASE 
                        WHEN p.usuari_id = u.id_usuari THEN ROUND(sp.preu_final * (1 - s.percentatge / 100), 2)
                        ELSE 0 
                    END
                ) AS total_guany
            FROM 
                subhasta_producte sp
            JOIN 
                product p ON sp.id_producte = p.id_producte
            JOIN 
                subhasta s ON sp.id_subhasta = s.id_subhasta
            JOIN 
                usuari u ON sp.comprador_id = u.id_usuari OR p.usuari_id = u.id_usuari
            WHERE 
                sp.id_subhasta = ?
            GROUP BY 
                u.username
        ");
            $stmt->execute([$id_subhasta]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $ex) {
            error_log('Error: ' . $ex->getMessage());
            return [];
        }
    }

    public static function saveEstatComptes(PDO $pdo, int $id_subhasta): bool
    {
        try {
            $stmt = $pdo->prepare("
            INSERT INTO compres (id_subhasta, id_producte, comprador_id, propietari_id, preu_inicial, preu_final, guany_comprador, guany_propietari, total_guany)
            SELECT 
                sp.id_subhasta,
                sp.id_producte,
                sp.comprador_id,
                p.usuari_id AS propietari_id,
                p.preu AS preu_inicial,
                sp.preu_final,
                -sp.preu_final AS guany_comprador,
                ROUND(sp.preu_final * (1 - s.percentatge / 100), 2) AS guany_propietari,
                ROUND(
                    -sp.preu_final + (sp.preu_final * (1 - s.percentatge / 100)), 2
                ) AS total_guany
            FROM 
                subhasta_producte sp
            JOIN 
                product p ON sp.id_producte = p.id_producte
            JOIN 
                subhasta s ON sp.id_subhasta = s.id_subhasta
            WHERE 
                sp.id_subhasta = ?
        ");
            return $stmt->execute([$id_subhasta]);
        } catch (PDOException $ex) {
            error_log('Error: ' . $ex->getMessage());
            return false;
        }
    }

    public static function getIniciById(PDO $pdo, int $id_subhasta): string
    {
        $stmt = $pdo->prepare("SELECT inici FROM subhasta WHERE id_subhasta = ?");
        $stmt->execute([$id_subhasta]);
        return $stmt->fetchColumn();
    }


    public static function tancaSubhasta(PDO $pdo, int $id_subhasta): void
    {
        $stmt = $pdo->prepare("UPDATE subhasta SET inici = 'finalitzat' WHERE id_subhasta = ?");
        $stmt->execute([$id_subhasta]);

        $stmt = $pdo->prepare("
        UPDATE product p
        JOIN subhasta_producte sp ON p.id_producte = sp.id_producte
        JOIN usuari u ON sp.comprador_id = u.id_usuari
        SET p.status = 'retirat'
        WHERE sp.id_subhasta = ? AND u.username = 'dessert'
    ");
        $stmt->execute([$id_subhasta]);

        // Actualizar el estado de los productos a 'venut' para todos los demás
        $stmt = $pdo->prepare("
        UPDATE product p
        JOIN subhasta_producte sp ON p.id_producte = sp.id_producte
        JOIN usuari u ON sp.comprador_id = u.id_usuari
        SET p.status = 'venut'
        WHERE sp.id_subhasta = ? AND u.username != 'dessert'
    ");
        $stmt->execute([$id_subhasta]);
    }

    public static function buscarSubhastes(PDO $pdo, string $search): array
    {
        $query = "SELECT * FROM subhasta WHERE nom LIKE :search OR descripcio LIKE :search";
        $stmt = $pdo->prepare($query);
        $searchTerm = '%' . $search . '%';
        $stmt->bindParam(':search', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function assignComprador(PDO $pdo, int $product_id, int $comprador_id): bool
    {
        try {
            $stmt = $pdo->prepare("UPDATE subhasta_producte SET comprador_id = ? WHERE id_producte = ?");
            return $stmt->execute([$comprador_id, $product_id]);
        } catch (PDOException $ex) {
            error_log('Error: ' . $ex->getMessage());
            return false;
        }
    }

    public static function assignPreu(PDO $pdo, int $product_id, float $preu_final): bool
    {
        try {
            $stmt = $pdo->prepare("UPDATE subhasta_producte SET preu_final = ? WHERE id_producte = ?");
            return $stmt->execute([$preu_final, $product_id]);
        } catch (PDOException $ex) {
            error_log('Error: ' . $ex->getMessage());
            return false;
        }
    }
}
