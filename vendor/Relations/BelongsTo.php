<?php

class BelongsTo
{
    public static function belongsTo($relatedModel, $foreignKey, $ownerKey, $id)
    {
        $db = new PDO("mysql:host=localhost;dbname=mo-spp", "root", "");

        $query = "SELECT $relatedModel.* 
                  FROM siswa 
                  INNER JOIN {$relatedModel} ON siswa.{$foreignKey} = {$relatedModel}.{$ownerKey} 
                  WHERE siswa.id = :id";

        $statement = $db->prepare($query);
        $statement->execute([':id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>
<!-- 
$db = new PDO("mysql:host=localhost;dbname=mo-spp", "root", "");

$query = "SELECT kelas.* 
          FROM siswa
          INNER JOIN kelas ON siswa.id_kelas = kelas.id
          WHERE siswa.id = :id";


$statement = $db->prepare($query);
$statement->execute([':id' => $id]);

// Mengambil hasil query
$result = $statement->fetch(PDO::FETCH_ASSOC);

// Mengembalikan hasil query
return $result; 
-->