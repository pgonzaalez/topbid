<?php
class Notificacio
{
    public $id_notificacio;
    public $producte_id;
    public $id_usuari;
    public $status;
    public $data;
    public $llegida;

    public static function createNotification(PDO $pdo, int $product_id, int $id_usuari, string $data): bool
    {
        $stmt = $pdo->prepare("INSERT INTO notificacions (producte_id, id_usuari, data) VALUES (?, ?, ?)");
        return $stmt->execute([$product_id, $id_usuari, $data]);
    }

    public static function marcarLlegit(PDO $pdo, int $notificacio_id): bool
    {
        $stmt = $pdo->prepare("UPDATE notificacions SET llegida = 1 WHERE id_notificacio = ?");
        return $stmt->execute([$notificacio_id]);
    }

    public static function getAllNotifications(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM notificacions WHERE llegida = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getNotificationsByUsuari(PDO $pdo, int $usuari_id): array
    {
        $stmt = $pdo->prepare("SELECT n.id_notificacio, n.producte_id, p.nom, p.status,n.llegida FROM notificacions n JOIN product p ON n.producte_id = p.id_producte WHERE p.usuari_id = ? AND n.llegida = 0" );
        $stmt->execute([$usuari_id]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}