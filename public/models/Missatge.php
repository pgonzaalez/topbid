<?php
class Missatge
{
    public $id_missatge;
    public $producte_id;
    public $missatge;
    public $data;
    public $emisor;
    public $receptor;
    public $llegit;


    public static function createMessage(PDO $pdo, int $product_id, string $missatge, string $data, int $emisor, int $receptor): bool
    {
        $stmt = $pdo->prepare("INSERT INTO missatges (producte_id, missatge, data, emisor, receptor) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$product_id, $missatge, $data, $emisor, $receptor]);
    }

    public static function getAllMessages(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT m.*, u.username AS emisor, p.nom AS producte_id FROM missatges m JOIN usuari u ON m.emisor = u.id_usuari JOIN product p ON m.producte_id = p.id_producte WHERE m.llegit = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function marcarLlegit(PDO $pdo, int $id_missatge): bool
    {
        $stmt = $pdo->prepare("UPDATE missatges SET llegit = 1 WHERE id_missatge = ?");
        return $stmt->execute([$id_missatge]);
    }
}
