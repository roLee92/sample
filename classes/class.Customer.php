<?php

class customer {
    protected $customerID;
    public $customerName;
    protected $database;

    function __construct($customerID, $database) {
        $this->customerID = $customerID;
        $this->database = $database;
    
    $sql = file_get_contents('sql/getCustomer.sql');
	$params = array(
		'customerid' => $_SESSION["customerID"]
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$customers = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	//$customer = $customers[0];
        
    $customer = $customers[0];
    $this->customerName = $customer['name'];
        
    }
}
?>