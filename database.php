<?php
include 'product.php';
/*
    Class implementing Singleton pattern to get a cursor to the current database.
*/
class MysqlDatabase {

    /* cursor to DB connection */
    private $cursor = null;

    /* Singleton instance - not needed in class methods */
    private static $instance = null;

    /*
        Use this method to get access to the database connection.
    */
    public static function get_instance(){
        if(self::$instance == null){
            self::$instance = new MysqlDatabase();
        }
        return self::$instance;
    }

    /*
        Private constructor to implement Singleton. Do not use this method for instatiation!
    */
	private function __construct(){
		$host = '127.0.0.1';
		$db = 'realdb';
		$user = 'wt1_prakt';
		$pw = 'abcd';
		
		$dsn = "mysql:host=$host;port=3306;dbname=$db";
		
		$options = [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_CASE => PDO::CASE_NATURAL
		];

		try{
            $this->cursor = new PDO($dsn, $user, $pw, $options);
		} 
		catch(PDOException $e){
			echo "Verbindungsaufbau gescheitert: " . $e->getMessage();
		}
    }
    
    /*
        Do not call this method directly.
    */
	public function __destruct(){
		$this->cursor = NULL;	
    }
    

    public function read_products() {
        // Hier einfache Abfrage und Ausgabe


    //     $sql = "SELECT product_id, product_name, unit_price FROM Product;";

    //     $abfrage = $this->cursor->prepare($sql);
    //     $abfrage->execute();

    //     $ergebnismenge = $abfrage->fetchAll(PDO::FETCH_ASSOC);

    //     foreach($ergebnismenge as $row) {
    //         echo "Produkt ID: " . $row["product_id"] . " " . "Produktname: " . $row["product_name"] . " " . "Produktpreis: " . $row["unit_price"] . "<br>";
            
    //     }
    // }
		// Hier als Array Rückgabe
        $sql = "SELECT product_id, product_name, unit_price FROM Product;";

        $abfrage = $this->cursor->prepare($sql);
        $abfrage->execute();
        $ergebnismenge =$abfrage->fetchAll(PDO::FETCH_ASSOC);

        $products = [];

        foreach($ergebnismenge as $row) {
            $product = new Product();
            $product->id = $row["product_id"];
            $product->name = $row["product_name"];
            $product->unit_price = $row["unit_price"];

            $products[] = $product;
        }

        return $products;
    }

    public function update_product($product_id, $new_unit_price) {
        try {
            // SQL-Statement mit Platzhaltern für Prepared Statement
            $sql = "UPDATE Product SET unit_price = :new_unit_price WHERE product_id = :product_id";
            
            // Prepared Statement vorbereiten
            $abfrage = $this->cursor->prepare($sql);
    
            // Platzhalter mit den tatsächlichen Werten ersetzen
            $abfrage->bindParam(':new_unit_price', $new_unit_price, PDO::PARAM_STR);
            $abfrage->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    
            // Prepared Statement ausführen
            $abfrage->execute();
    
            // Erfolgreiches Update
            echo "Produkt erfolgreich aktualisiert.";
        } catch (PDOException $e) {
            // Fehler beim Update
            echo "Fehler beim Aktualisieren des Produkts: " . $e->getMessage();
        }
    }
    




}



?>
