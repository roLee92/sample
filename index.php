<?php

// Create and include a configuration file with the database connection
include('config.php');

// Include functions for application
include('functions.php');

// Get search term from URL using the get function
$term = get('search-term');

// Get a list of books using the searchBooks function
// Print the results of search results
// Add a link printed for each book to book.php with an passing the isbn
// Add a link printed for each book to form.php with an action of edit and passing the isbn
$books = searchBooks($term, $database);
?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	
  	<title>Books</title>
	<meta name="description" content="The HTML5 Herald">
	<meta name="author" content="SitePoint">

	<link rel="stylesheet" href="css/style.css">

	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script>
  	<![endif]-->
</head>
<body>
	<div class="page">
		<h1>Books</h1>
		<form method="GET">
			<input type="text" name="search-term" placeholder="Search..." />
			<input type="submit" />
		</form>
		<?php foreach($books as $book) : ?>
			<p>
				<?php echo $book['title']; ?><br />
				<?php echo $book['author']; ?> <br />
				<?php echo $book['price']; ?> <br />
				<a href="form.php?action=edit&isbn=<?php echo $book['isbn'] ?>">Edit Book</a><br />
				<a href="book.php?isbn=<?php echo $book['isbn'] ?>">View Book</a>
			</p>
		<?php endforeach; ?>
		
		<!-- print currently accessed by the current username -->
		<p>Currently logged in as: <?php echo $customer01->customerName; ?></p>
		
		<!-- A link to the logout.php file -->
		<p>
			<a href="logout.php">Log Out</a>
		</p>
	</div>
</body>
</html>