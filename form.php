<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Get type of form either add or edit from the URL (ex. form.php?action=add) using the newly written get function
$action = $_GET['action'];

// Get the book isbn from the URL if it exists using the newly written get function
$isbn = get('isbn');

// Initially set $book to null;
$book = null;

// Initially set $book_categories to an empty array;
$book_categories = array();

// If book isbn is not empty, get book record into $book variable from the database
//     Set $book equal to the first book in $books
// 	   Set $book_categories equal to a list of categories associated to a book from the database
if(!empty($isbn)) {
	$sql = file_get_contents('sql/getBook.sql');
	$params = array(
		'isbn' => $isbn
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$books = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	$book = $books[0];
	
	// Get book categories
	$sql = file_get_contents('sql/getBookCategories.sql');
	$params = array(
		'isbn' => $isbn
	);
	$statement = $database->prepare($sql);
	$statement->execute($params);
	$book_categories_associative = $statement->fetchAll(PDO::FETCH_ASSOC);
	
	foreach($book_categories_associative as $category) {
		$book_categories[] = $category['categoryid'];
	}
}

// Get an associative array of categories
$sql = file_get_contents('sql/getCategories.sql');
$statement = $database->prepare($sql);
$statement->execute();
$categories = $statement->fetchAll(PDO::FETCH_ASSOC); 

// If form submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$isbn = $_POST['isbn'];
	$title = $_POST['book-title'];
	$book_categories = $_POST['book-category'];
	$author = $_POST['book-author'];
	$price = $_POST['book-price'];
	
	if($action == 'add') {
		// Insert book
		$sql = file_get_contents('sql/insertBook.sql');
		$params = array(
			'isbn' => $isbn,
			'title' => $title,
			'author' => $author,
			'price' => $price
		);
	
		$statement = $database->prepare($sql);
		$statement->execute($params);
		
		// Set categories for book
		$sql = file_get_contents('sql/insertBookCategory.sql');
		$statement = $database->prepare($sql);
		
		foreach($book_categories as $category) {
			$params = array(
				'isbn' => $isbn,
				'categoryid' => $category
			);
			$statement->execute($params);
		}
	}
	
	elseif ($action == 'edit') {
		$sql = file_get_contents('sql/updateBook.sql');
        $params = array( 
            'isbn' => $isbn,
            'title' => $title,
            'author' => $author,
            'price' => $price
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //remove current category info 
        $sql = file_get_contents('sql/removeCategories.sql');
        $params = array(
            'isbn' => $isbn
        );
        
        $statement = $database->prepare($sql);
        $statement->execute($params);
        
        //set categories for book
        $sql = file_get_contents('sql/insertBookCategory.sql');
        $statement = $database->prepare($sql);
        
        foreach($book_categories as $category) {
            $params = array(
                'isbn' => $isbn,
                'categoryid' => $category
            );
            $statement->execute($params);
        };	
	}
	
	// Redirect to book listing page
	header('location: index.php');
}

// In the HTML, if an edit form:
	// Populate textboxes with current data of book selected 
	// Print the checkbox with the book's current categories already checked (selected)
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>Manage Book</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>Manage Book</h1>
		<form action="" method="POST">
			<div class="form-element">
				<label>ISBN:</label>
				<?php if($action == 'add') : ?>
					<input type="text" name="isbn" class="textbox" value="<?php echo $book['isbn'] ?>" />
				<?php else : ?>
					<input readonly type="text" name="isbn" class="textbox" value="<?php echo $book['isbn'] ?>" />
				<?php endif; ?>
			</div>
			<div class="form-element">
				<label>Title:</label>
				<input type="text" name="book-title" class="textbox" value="<?php echo $book['title'] ?>" />
			</div>
			<div class="form-element">
				<label>Category:</label>
				<?php foreach($categories as $category) : ?>
					<?php if(in_array($category['categoryid'], $book_categories)) : ?>
						<input checked class="radio" type="checkbox" name="book-category[]" value="<?php echo $category['categoryid'] ?>" /><span class="radio-label"><?php echo $category['name'] ?></span><br />
					<?php else : ?>
						<input class="radio" type="checkbox" name="book-category[]" value="<?php echo $category['categoryid'] ?>" /><span class="radio-label"><?php echo $category['name'] ?></span><br />
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
			<div class="form-element">
				<label>Author</label>
				<input type="text" name="book-author" class="textbox" value="<?php echo $book['author'] ?>" />
			</div>
			<div class="form-element">
				<label>Price:</label>
				<input type="number" step="any" name="book-price" class="textbox" value="<?php echo $book['price'] ?>" />
			</div>
			<div class="form-element">
				<input type="submit" class="button" />&nbsp;
				<input type="reset" class="button" />
			</div>
		</form>
	</div>
</body>
</html>